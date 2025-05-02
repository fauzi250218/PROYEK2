<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('login_admin');
    }

        public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Hanya izinkan admin untuk masuk
            if ($user->level !== 'admin') {
                Auth::logout(); // Langsung logout jika bukan admin
                return back()->with('error', 'Anda tidak memiliki akses sebagai admin.');
            }

            return redirect()->route('beranda_admin');
        }

        return back()->with('error', 'Nama pengguna atau kata sandi salah.');
    }

    public function showBeranda()
    {
        $user = Auth::user();
        
        // Ambil data kas yang sudah valid (tanggal tidak null)
        $kas = Kas::whereNotNull('tanggal')
                  ->selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan, SUM(kas_masuk) as total_masuk, SUM(kas_keluar) as total_keluar')
                  ->groupBy('tahun', 'bulan')
                  ->orderBy('tahun', 'asc')
                  ->orderBy('bulan', 'asc')
                  ->get();
        
        // Total keseluruhan
        $totalMasuk = Kas::sum('kas_masuk');
        $totalKeluar = Kas::sum('kas_keluar');
        $saldo = $totalMasuk - $totalKeluar;
        
        // Jumlah transaksi lunas dan belum lunas
        $jumlahTransaksiLunas = Tagihan::where('status', 'Lunas')->count();
        $jumlahTransaksiBelumLunas = Tagihan::where('status', 'Belum Lunas')->count();
        
        // Siapkan data grafik
        $labels = $kas->map(function ($item) {
            return str_pad($item->bulan, 2, '0', STR_PAD_LEFT) . '-' . $item->tahun; // Format MM-YYYY
        });
        
        $totalMasukPerBulan = $kas->map(function ($item) {
            return (float) $item->total_masuk;
        });
        
        $totalKeluarPerBulan = $kas->map(function ($item) {
            return (float) $item->total_keluar;
        });
    
        // Menentukan target kas (misalnya target pemasukan bulanan)
        $totalTargetKas = 10000000; // Misalnya target kas bulanan adalah Rp 10.000.000
        
        return view('beranda_admin', compact(
            'user',
            'totalMasuk',
            'totalKeluar',
            'saldo',
            'jumlahTransaksiLunas',
            'jumlahTransaksiBelumLunas',
            'labels',
            'totalMasukPerBulan',
            'totalKeluarPerBulan',
            'totalTargetKas'
        ));
    }
    
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login_admin');
    }
}
