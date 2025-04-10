<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KasController extends Controller
{
    public function index()
    {
        $kas = Kas::all();
        $totalMasuk = $kas->sum('kas_masuk');
        $totalKeluar = $kas->sum('kas_keluar');
        $saldo = $totalMasuk - $totalKeluar;

        return view('kas.index', compact('kas', 'totalMasuk', 'totalKeluar', 'saldo'));
    }

    public function create()
    {
        return view('kas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'kas_masuk' => 'nullable|integer',
            'kas_keluar' => 'nullable|integer',
        ]);

        Kas::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'kas_masuk' => $request->kas_masuk ?? 0,
            'kas_keluar' => $request->kas_keluar ?? 0,
        ]);

        return redirect()->route('kas.index')->with('success', 'Data kas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kas = Kas::findOrFail($id);
        return view('kas.edit', compact('kas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'kas_masuk' => 'nullable|integer',
            'kas_keluar' => 'nullable|integer',
        ]);

        $kas = Kas::findOrFail($id);
        $kas->update([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'kas_masuk' => $request->kas_masuk ?? 0,
            'kas_keluar' => $request->kas_keluar ?? 0,
        ]);

        return redirect()->route('kas.index')->with('success', 'Data kas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kas = Kas::findOrFail($id);
        $kas->delete();

        return redirect()->route('kas.index')->with('success', 'Data kas berhasil dihapus.');
    }

    // Menampilkan form laporan kas
    public function laporanForm()
    {
        return view('laporan.laporan_form');
    }

    // Generate dan tampilkan PDF
    public function laporanCetak(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $kas = Kas::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])->get();
        $totalMasuk = $kas->sum('kas_masuk');
        $totalKeluar = $kas->sum('kas_keluar');
        $saldo = $totalMasuk - $totalKeluar;

        $pdf = Pdf::loadView('laporan.laporan_pdf', compact(
            'kas', 'totalMasuk', 'totalKeluar', 'saldo', 'tanggalAwal', 'tanggalAkhir'
        ));

        return $pdf->stream('laporan-kas-' . now()->format('YmdHis') . '.pdf');
    }
}
