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

        // Ambil tahun unik dari data tagihan
        $tahunList = Tagihan::select('tahun')
                    ->distinct()
                    ->orderBy('tahun', 'desc')
                    ->pluck('tahun');

        $tagihans = Tagihan::with(['pelanggan.user', 'pelanggan.data_paket'])
            ->when($search, fn($query) => 
                $query->whereHas('pelanggan', fn($q) => 
                    $q->where('nama_pelanggan', 'like', '%' . $search . '%')
                )
            )
            ->when($bulan, fn($query) => $query->where('bulan', $bulan))
            ->when($tahun, fn($query) => $query->where('tahun', $tahun))
            ->orderByRaw("FIELD(status, 'Belum Lunas', 'Lunas')")
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->paginate(5)
            ->withQueryString();

        return view('tagihan.index', compact('tagihans', 'namaBulan', 'tahunList'));
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

                // Kirim WhatsApp
                if ($pelanggan->no_telp && $pelanggan->user) {
                    $no = preg_replace('/^0/', '62', $pelanggan->no_telp);
                    $message = "🌐 Halo {$pelanggan->user->nama_user}, pelanggan setia *Lilik.net*!\n\n"
                        . "Berikut ini adalah detail tagihan internet Anda:\n"
                        . "📦 Paket: {$pelanggan->data_paket->nama_paket}\n"
                        . "💰 Jumlah: *Rp" . number_format($pelanggan->data_paket->harga, 0, ',', '.') . "*\n"
                        . "📅 Periode: *{$request->bulan} / {$request->tahun}*\n"
                        . "📌 Status: *Belum Lunas*\n\n"
                        . "Mohon segera melakukan pembayaran agar layanan tetap aktif.\n"
                        . "Terima kasih atas kepercayaan Anda 🙏";

                    Http::withHeaders([
                        'Authorization' => '1HcFqZEKiuCvJiK6oAuw'
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
        $tagihan = Tagihan::with('pelanggan.user')->findOrFail($id);
        $tagihan->status = 'Lunas';
        $tagihan->save();

        $sudahAda = Kas::where('keterangan', 'LIKE', '%Tagihan ID: ' . $tagihan->id . '%')->exists();

        if (!$sudahAda) {
            Kas::create([
                'tanggal' => now()->toDateString(),
                'keterangan' => 'Pembayaran tagihan oleh ' . optional(optional($tagihan->pelanggan)->user)->nama_user . ' (Tagihan ID: ' . $tagihan->id . ')',
                'kas_masuk' => $tagihan->jumlah,
                'kas_keluar' => 0,
            ]);
        }

        if ($tagihan->pelanggan && $tagihan->pelanggan->no_telp) {
            $no = preg_replace('/^0/', '62', $tagihan->pelanggan->no_telp);
           $message = "✅ Halo {$tagihan->pelanggan->user->nama_user}, pelanggan *Lilik.net*!\n\n"
            . "Pembayaran Anda telah *berhasil dikonfirmasi* 🎉\n"
            . "📌 Status Tagihan: *Lunas*\n\n"
            . "Terima kasih telah membayar tepat waktu. Semoga layanan kami selalu memuaskan!\n🙏";

            Http::withHeaders([
                'Authorization' => '1HcFqZEKiuCvJiK6oAuw'
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $no,
                'message' => $message,
                'countryCode' => '62'
            ]);
        }

        return back()->with('success', 'Status tagihan diperbarui dan notifikasi dikirim.');
    }

    public function kirimwa($id)
    {
        $tagihan = Tagihan::with(['pelanggan.user', 'pelanggan.data_paket'])->findOrFail($id);

        if (!$tagihan->pelanggan || !$tagihan->pelanggan->no_telp) {
            return back()->with('error', 'Nomor telepon pelanggan tidak tersedia.');
        }

        $no = preg_replace('/^0/', '62', $tagihan->pelanggan->no_telp);
        $message = "🌐 Halo {$tagihan->pelanggan->user->nama_user}, pelanggan *Lilik.net*!\n\n"
        . "Berikut ini detail tagihan internet Anda:\n"
        . "📦 Paket: {$tagihan->pelanggan->data_paket->nama_paket}\n"
        . "💰 Jumlah: *Rp" . number_format($tagihan->jumlah, 0, ',', '.') . "*\n"
        . "📅 Periode: *{$tagihan->bulan} / {$tagihan->tahun}*\n"
        . "📌 Status: *{$tagihan->status}*\n\n"
        . "Segera lakukan pembayaran untuk menikmati koneksi tanpa gangguan.\n"
        . "Terima kasih atas kerjasamanya 🙏";

        Http::withHeaders([
            'Authorization' => '1HcFqZEKiuCvJiK6oAuw'
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $no,
            'message' => $message,
            'countryCode' => '62'
        ]);

        return back()->with('success', 'Pesan WhatsApp berhasil dikirim.');
    }

    public function payWithMidtrans($id)
    {
        $tagihan = Tagihan::with('pelanggan.user')->findOrFail($id);

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

        $snap_token = Snap::getSnapToken([
            'transaction_details' => $transaction_details,
            'customer_details'    => $customer_details,
        ]);

        // Simpan order_id ke tagihan
        $tagihan->order_id = $orderId;
        $tagihan->save();

        return view('tagihan.bayar', compact('snap_token', 'tagihan'));
    }

    public function bayarManual($id)
    {
        $tagihan = Tagihan::with('pelanggan.user')->findOrFail($id);

        if (!$tagihan->pelanggan || !$tagihan->pelanggan->no_telp) {
            return back()->with('error', 'Nomor telepon pelanggan tidak tersedia.');
        }

        $no = preg_replace('/^0/', '62', $tagihan->pelanggan->no_telp);
        $message = "💡 Halo {$tagihan->pelanggan->user->nama_user}, pelanggan *Lilik.net*!\n\n"
        . "Berikut ini adalah rincian tagihan Anda:\n"
        . "📦 Paket: {$tagihan->pelanggan->data_paket->nama_paket}\n"
        . "💳 Jumlah Tagihan: *Rp" . number_format($tagihan->jumlah, 0, ',', '.') . "*\n"
        . "📅 Periode: *{$tagihan->bulan} / {$tagihan->tahun}*\n\n"
        . "Silakan melakukan pembayaran ke rekening berikut:\n"
        . "🏦 *BCA 123456789 a.n. Admin Lilik.net*\n\n"
        . "Setelah transfer, mohon kirim bukti pembayaran kepada admin.\n"
        . "Layanan Anda akan segera kami aktifkan setelah konfirmasi diterima. 🙏";

        $response = Http::withHeaders([
            'Authorization' => '1HcFqZEKiuCvJiK6oAuw'
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $no,
            'message' => $message,
            'countryCode' => '62'
        ]);

        return $response->successful()
            ? back()->with('success', 'Instruksi pembayaran berhasil dikirim via WhatsApp.')
            : back()->with('error', 'Gagal mengirim pesan WhatsApp.');
    }

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
    
        // Update status pembayaran
        if ($status == 'settlement' || $status == 'capture') {
            $tagihan->status = 'Lunas';
            $tagihan->metode_pembayaran = $paymentType; // Simpan metode pembayaran
            $tagihan->save();
            
            // Catat di kas
            Kas::updateOrCreate(
                ['keterangan' => 'Pembayaran tagihan oleh ' . optional(optional($tagihan->pelanggan)->user)->nama_user . ' (Tagihan ID: ' . $tagihan->id . ')'],
                ['tanggal' => now()->toDateString(), 'kas_masuk' => $tagihan->jumlah, 'kas_keluar' => 0]
            );
        }
    
        return response()->json(['success' => true]);
    }    

    public function handleCallback(Request $request)
    {
        $notif = new \Midtrans\Notification();
    
        $status = $notif->transaction_status;
        $type = $notif->payment_type;
        $fraud = $notif->fraud_status;
        $order_id = $notif->order_id;
    
        $tagihan = Tagihan::where('order_id', $order_id)->first();
    
        if ($status == 'capture') {
            if ($type == 'credit_card') {
                $tagihan->status = $fraud === 'challenge' ? 'Belum Lunas' : 'Lunas';
            }
        } elseif ($status == 'settlement') {
            $tagihan->status = 'Lunas';
        } elseif (in_array($status, ['pending', 'deny', 'expire', 'cancel'])) {
            $tagihan->status = 'Belum Lunas';
        }
    
        // Simpan metode pembayaran
        $tagihan->metode_pembayaran = $type;
    
        $tagihan->save();
    
        return response()->json(['message' => 'Callback handled'], 200);
    }

    public function cetak($id)
    {
        $tagihan = Tagihan::with(['pelanggan.user', 'pelanggan.data_paket'])->findOrFail($id);

        $nama = str_replace(' ', '', $tagihan->pelanggan->user->nama_user ?? 'pengguna'); // Hilangkan spasi
        $bulan = $tagihan->bulan;
        $tahun = $tagihan->tahun;

        $fileName = 'tagihan-' . $nama . '-' . $bulan . '-' . $tahun . '.pdf';

        $pdf = Pdf::loadView('tagihan.cetak', compact('tagihan'));

        return $pdf->stream($fileName);
    }

}