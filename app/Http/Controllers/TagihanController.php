<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
            ->paginate(10)
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

        $pelanggans = Pelanggan::with('data_paket')->get();

        foreach ($pelanggans as $pelanggan) {
            $cek = Tagihan::where('id_pelanggan', $pelanggan->id_pelanggan)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->first();

            if (!$cek) {
                Tagihan::create([
                    'id_pelanggan' => $pelanggan->id_pelanggan,
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'jumlah' => $pelanggan->data_paket->harga,
                    'status' => 'Belum Lunas'
                ]);
            }
        }

        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil dibuat!');
    }

    public function updateStatus($id)
    {
        $tagihan = Tagihan::with(['pelanggan.user'])->findOrFail($id);
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
    
        return back()->with('success', 'Status tagihan diperbarui dan data kas berhasil dicatat.');
    }

    public function kirimwa($id)
    {
        $tagihan = Tagihan::with('pelanggan.user', 'pelanggan.data_paket')->findOrFail($id);
        $no = $tagihan->pelanggan->no_telp;

        $message = "Halo {$tagihan->pelanggan->user->nama_user}, berikut adalah tagihan Anda:\n"
                . "Paket: {$tagihan->pelanggan->data_paket->nama_paket}\n"
                . "Jumlah: Rp" . number_format($tagihan->jumlah, 0, ',', '.') . "\n"
                . "Bulan: {$tagihan->bulan} / {$tagihan->tahun}\n"
                . "Status: {$tagihan->status}";

        Http::get('https://api.whatsapp.example.com/send', [
            'phone' => $no,
            'text' => $message,
            'token' => 'API_TOKEN_ANDA'
        ]);

        return back()->with('success', 'Tagihan berhasil dikirim via WhatsApp.');
    }

    public function cetak($id)
    {
        $tagihan = Tagihan::with(['pelanggan.user', 'pelanggan.data_paket'])->findOrFail($id);

        return view('tagihan.cetak', compact('tagihan'));
    }
}
