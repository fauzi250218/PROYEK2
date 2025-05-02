<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Midtrans\Snap;
use Midtrans\Config;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $tagihans = Tagihan::with(['pelanggan.user', 'pelanggan.data_paket'])
            ->when($search, function ($query, $search) {
                $query->whereHas('pelanggan', function ($q) use ($search) {
                    $q->where('nama_pelanggan', 'like', '%' . $search . '%');
                });
            })
            ->when($bulan, function ($query, $bulan) {
                $query->where('bulan', $bulan);
            })
            ->when($tahun, function ($query, $tahun) {
                $query->where('tahun', $tahun);
            })
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(5)
            ->withQueryString();

        return view('tagihan.index', compact('tagihans', 'namaBulan'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::with('data_paket')->get();
        return view('tagihan.create', compact('pelanggans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|numeric|min:1|max:12',
            'tahun' => 'required|numeric|min:2000',
        ]);

        $pelanggans = Pelanggan::with('data_paket', 'user')->get();

        foreach ($pelanggans as $pelanggan) {
            $cek = Tagihan::where('id_pelanggan', $pelanggan->id_pelanggan)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->first();

            if (!$cek) {
                $tagihan = Tagihan::create([
                    'id_pelanggan' => $pelanggan->id_pelanggan,
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'jumlah' => $pelanggan->data_paket->harga,
                    'status' => 'Belum Lunas'
                ]);

                // Kirim WA otomatis saat buat tagihan
                if ($pelanggan->no_telp) {
                    $no = preg_replace('/^0/', '62', $pelanggan->no_telp);
                    $message = "Halo {$pelanggan->user->nama_user}, berikut adalah tagihan Anda:\n"
                        . "Paket: {$pelanggan->data_paket->nama_paket}\n"
                        . "Jumlah: Rp" . number_format($pelanggan->data_paket->harga, 0, ',', '.') . "\n"
                        . "Bulan: {$request->bulan} / {$request->tahun}\n"
                        . "Status: Belum Lunas";

                    Http::withHeaders([
                        'Authorization' => '1HcFqZEKiuCvJiK6oAuw' // Ganti dengan token kamu
                    ])->asForm()->post('https://api.fonnte.com/send', [
                        'target' => $no,
                        'message' => $message,
                        'countryCode' => '62'
                    ]);
                }
            }
        }

        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil dibuat dan dikirim via WhatsApp!');
    }

    public function updateStatus($id)
    {
        $tagihan = Tagihan::with(['pelanggan.user'])->findOrFail($id);
        $tagihan->status = 'Lunas';
        $tagihan->save();

        // Cegah data ganda di kas
        $sudahAda = Kas::where('keterangan', 'LIKE', '%Tagihan ID: ' . $tagihan->id . '%')->exists();

        if (!$sudahAda) {
            Kas::create([
                'tanggal' => now()->toDateString(),
                'keterangan' => 'Pembayaran tagihan oleh ' . optional(optional($tagihan->pelanggan)->user)->nama_user . ' (Tagihan ID: ' . $tagihan->id . ')',
                'kas_masuk' => $tagihan->jumlah,
                'kas_keluar' => 0,
            ]);
        }

        // Kirim WA konfirmasi pembayaran
        if ($tagihan->pelanggan && $tagihan->pelanggan->no_telp) {
            $no = preg_replace('/^0/', '62', $tagihan->pelanggan->no_telp);
            $message = "Halo {$tagihan->pelanggan->user->nama_user}, pembayaran tagihan Anda telah dikonfirmasi.\n"
                . "Status: Lunas\n"
                . "Terima kasih atas pembayarannya 🙏";

            Http::withHeaders([
                'Authorization' => '1HcFqZEKiuCvJiK6oAuw'
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $no,
                'message' => $message,
                'countryCode' => '62'
            ]);
        }

        return back()->with('success', 'Status tagihan diperbarui, data kas dicatat, dan notifikasi WhatsApp dikirim.');
    }
    public function kirimwa($id)
    {
        $tagihan = Tagihan::with(['pelanggan.user', 'pelanggan.data_paket'])->findOrFail($id);
    
        if (!$tagihan->pelanggan || !$tagihan->pelanggan->no_telp) {
            return back()->with('error', 'Nomor telepon pelanggan tidak tersedia.');
        }
    
        $no = preg_replace('/^0/', '62', $tagihan->pelanggan->no_telp);
        $message = "Halo {$tagihan->pelanggan->user->nama_user}, berikut adalah tagihan Anda:\n"
            . "Paket: {$tagihan->pelanggan->data_paket->nama_paket}\n"
            . "Jumlah: Rp" . number_format($tagihan->jumlah, 0, ',', '.') . "\n"
            . "Bulan: {$tagihan->bulan} / {$tagihan->tahun}\n"
            . "Status: {$tagihan->status}";
    
        Http::withHeaders([
            'Authorization' => '1HcFqZEKiuCvJiK6oAuw' // Ganti dengan token API Fonnte milikmu
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $no,
            'message' => $message,
            'countryCode' => '62'
        ]);
    
        return back()->with('success', 'Pesan WhatsApp berhasil dikirim.');
    }
    
    public function payWithMidtrans($id)
    {
        $tagihan = Tagihan::findOrFail($id);
    
        // Mengambil konfigurasi dari file config/midtrans.php
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    
        // Membuat detail transaksi
        $orderId = 'TAGIHAN-' . $tagihan->id . '-' . time(); // order_id harus unik setiap kali
    
        $transaction_details = [
            'order_id'     => $orderId,
            'gross_amount' => $tagihan->jumlah,
        ];
    
        $customer_details = [
            'first_name'    => optional($tagihan->pelanggan->user)->nama_user,
            'email'         => optional($tagihan->pelanggan->user)->email,
            'phone'         => $tagihan->pelanggan->no_telp,
        ];
    
        $payment_type = 'credit_card';
    
        $transaction_data = [
            'transaction_details' => $transaction_details,
            'customer_details'    => $customer_details,
            'payment_type'        => $payment_type,
        ];
    
        $snap_token = Snap::getSnapToken($transaction_data);
    
        return view('tagihan.bayar', compact('snap_token', 'tagihan'));
    }
    
    public function midtransNotification(Request $request)
    {
        $transaction = $request->all(); // Data yang diterima dari Midtrans
    
        $orderId = $transaction['order_id'];
        $status = $transaction['transaction_status'];
    
        // Cari tagihan berdasarkan order_id
        $tagihan = Tagihan::where('order_id', $orderId)->first();
    
        if (!$tagihan) {
            return response()->json(['error' => 'Tagihan tidak ditemukan.'], 404);
        }
    
        if ($status === 'settlement') {
            // Pembayaran berhasil, tandai lunas
            $tagihan->status = 'Lunas';
            $tagihan->save();
    
            // Cegah data ganda di kas
            $sudahAda = Kas::where('keterangan', 'LIKE', '%Tagihan ID: ' . $tagihan->id . '%')->exists();
    
            if (!$sudahAda) {
                Kas::create([
                    'tanggal' => now()->toDateString(),
                    'keterangan' => 'Pembayaran tagihan oleh ' . optional($tagihan->pelanggan->user)->nama_user . ' (Tagihan ID: ' . $tagihan->id . ')',
                    'kas_masuk' => $tagihan->jumlah,
                    'kas_keluar' => 0,
                ]);
            }
    
            // Kirim WA konfirmasi pembayaran
            if ($tagihan->pelanggan && $tagihan->pelanggan->no_telp) {
                $no = preg_replace('/^0/', '62', $tagihan->pelanggan->no_telp);
                $message = "Halo {$tagihan->pelanggan->user->nama_user}, pembayaran tagihan Anda telah dikonfirmasi.\n"
                    . "Status: Lunas\n"
                    . "Terima kasih atas pembayarannya 🙏";
    
                Http::withHeaders([
                    'Authorization' => '1HcFqZEKiuCvJiK6oAuw'
                ])->asForm()->post('https://api.fonnte.com/send', [
                    'target' => $no,
                    'message' => $message,
                    'countryCode' => '62'
                ]);
            }
    
            return redirect()->route('tagihan.index')->with('success', 'Pembayaran berhasil dan tagihan telah dilunasi!');
        }
    
        return redirect()->route('tagihan.index')->with('error', 'Pembayaran gagal, silakan coba lagi.');
    }    
    
    public function cetak($id)
    {
        $tagihan = Tagihan::with(['pelanggan.user', 'pelanggan.data_paket'])->findOrFail($id);
        $pdf = PDF::loadView('tagihan.cetak', compact('tagihan'));
        return $pdf->stream('tagihan_' . $tagihan->id . '.pdf');
    }
}
