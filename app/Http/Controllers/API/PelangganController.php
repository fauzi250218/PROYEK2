<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Paket;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    // Fungsi untuk menambah data pelanggan (bukan bagian dari registrasi pengguna)
    public function store(Request $request)
    {
        // Validasi data pelanggan
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
            'paket' => 'required|exists:paket,id',  // Memastikan paket ada di tabel paket
            'username' => 'required|exists:tb_user,username',  // Memastikan username ada di tb_user
        ]);

        // Ambil ID pengguna berdasarkan username yang terdaftar di tb_user
        $user = \App\Models\Users::where('username', $request->username)->first();

        // Jika pengguna ditemukan, simpan data pelanggan
        if ($user) {
            $pelanggan = Pelanggan::create([
                'nama_pelanggan' => $request->nama_pelanggan,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
                'paket' => $request->paket,  // Simpan paket sesuai ID
            ]);

            // Update hubungan antara pengguna dengan pelanggan
            $user->id_pelanggan = $pelanggan->id_pelanggan;  // Menyimpan id pelanggan di tabel tb_user
            $user->save();

            return response()->json([
                'message' => 'Pelanggan berhasil ditambahkan!',
                'pelanggan' => $pelanggan,
            ], 201);
        } else {
            return response()->json([
                'message' => 'Username tidak ditemukan!',
            ], 404);
        }
    }
}
