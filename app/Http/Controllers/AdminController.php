<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;

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

        // Kas
        $totalMasuk = Kas::sum('kas_masuk');
        $totalKeluar = Kas::sum('kas_keluar');
        $saldo = $totalMasuk - $totalKeluar;

        // Jumlah transaksi lunas
        $jumlahTransaksi = Tagihan::where('status', 'Lunas')->count();

        return view('beranda_admin', compact('user', 'totalMasuk', 'totalKeluar', 'saldo', 'jumlahTransaksi'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login_admin');
    }
}
