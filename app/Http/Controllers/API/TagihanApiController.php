<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Kas;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;

class TagihanApiController extends Controller
{
    // Tampilkan list tagihan pelanggan (bisa filter by id_pelanggan)
    public function index(Request $request)
    {
        $idPelanggan = $request->id_pelanggan;

        $query = Tagihan::with('pelanggan.user', 'pelanggan.data_paket')
            ->orderByRaw("FIELD(status, 'Belum Lunas', 'Lunas')")
            ->orderByDesc('tahun')
            ->orderByDesc('bulan');

        if ($idPelanggan) {
            $query->where('id_pelanggan', $idPelanggan);
        }

        $tagihans = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'id_pelanggan' => $item->id_pelanggan,
                'nama_pelanggan' => $item->pelanggan->nama_pelanggan,
                'paket' => $item->pelanggan->data_paket->nama_paket,
                'jumlah' => (string) $item->jumlah,
                'bulan' => $item->bulan,
                'tahun' => $item->tahun,
                'status' => $item->status,
            ];
        });

        return response()->json([
            'success' => true,
            'tagihan' => $tagihans,
        ]);
    }

    // Tampilkan detail tagihan by ID
    public function show($id)
    {
        $tagihan = Tagihan::with('pelanggan.user', 'pelanggan.data_paket')->find($id);

        if (!$tagihan) {
            return response()->json([
                'success' => false,
                'message' => 'Tagihan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'tagihan' => [
                'id' => $tagihan->id,
                'id_pelanggan' => $tagihan->id_pelanggan,
                'nama_pelanggan' => $tagihan->pelanggan->nama_pelanggan,
                'paket' => $tagihan->pelanggan->data_paket->nama_paket,
                'jumlah' => (string) $tagihan->jumlah,
                'bulan' => $tagihan->bulan,
                'tahun' => $tagihan->tahun,
                'status' => $tagihan->status,
            ],
        ]);
    }

    // Generate Snap Token Midtrans untuk pembayaran mobile
    public function createPaymentToken($id)
    {
        $tagihan = Tagihan::with('pelanggan.user')->find($id);

        if (!$tagihan) {
            return response()->json([
                'success' => false,
                'message' => 'Tagihan tidak ditemukan'
            ], 404);
        }

        if ($tagihan->status == 'Lunas') {
            return response()->json([
                'success' => false,
                'message' => 'Tagihan sudah lunas'
            ]);
        }

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderId = 'TAGIHAN-' . $tagihan->id . '-' . time();

        $transaction_details = [
            'order_id'     => $orderId,
            'gross_amount' => $tagihan->jumlah,
        ];

        $customer_details = [
            'first_name' => optional($tagihan->pelanggan->user)->nama_user,
            'email'      => optional($tagihan->pelanggan->user)->email,
            'phone'      => $tagihan->pelanggan->no_telp,
        ];

        try {
            $snapToken = Snap::getSnapToken([
                'transaction_details' => $transaction_details,
                'customer_details' => $customer_details,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat Snap Token: ' . $e->getMessage(),
            ], 500);
        }

        // Simpan order_id ke tagihan
        $tagihan->order_id = $orderId;
        $tagihan->save();

        return response()->json([
            'success' => true,
            'snap_token' => $snapToken,
            'order_id' => $orderId,
        ]);
    }

    // Handle Midtrans webhook notification
    public function midtransNotification(Request $request)
    {
        $transaction = $request->all();

        $orderId = $transaction['order_id'] ?? null;
        $status = $transaction['transaction_status'] ?? null;
        $paymentType = $transaction['payment_type'] ?? null;

        if (!$orderId) {
            return response()->json(['error' => 'Order ID tidak ditemukan'], 400);
        }

        $tagihan = Tagihan::where('order_id', $orderId)->first();

        if (!$tagihan) {
            return response()->json(['error' => 'Tagihan tidak ditemukan'], 404);
        }

        if (in_array($status, ['settlement', 'capture'])) {
            $tagihan->status = 'Lunas';
            $tagihan->metode_pembayaran = $paymentType;
            $tagihan->save();

            Kas::updateOrCreate(
                ['keterangan' => 'Pembayaran tagihan oleh ' . optional(optional($tagihan->pelanggan)->user)->nama_user . ' (Tagihan ID: ' . $tagihan->id . ')'],
                ['tanggal' => now()->toDateString(), 'kas_masuk' => $tagihan->jumlah, 'kas_keluar' => 0]
            );
        } elseif (in_array($status, ['pending', 'deny', 'expire', 'cancel'])) {
            $tagihan->status = 'Belum Lunas';
            $tagihan->save();
        }

        return response()->json(['success' => true]);
    }
}
