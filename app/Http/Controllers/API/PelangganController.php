<?php

// app/Http/Controllers/Api/PelangganController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Users;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function store(Request $request)
{
    // Cek apakah request data valid
    dd($request->all()); // Ini akan menampilkan semua data yang dikirimkan

    $request->validate([
        'nama_pelanggan' => 'required|string|max:255',
        'alamat' => 'required|string',
        'no_telp' => 'required|string|max:15',
        'paket' => 'required|exists:data_paket,id', // foreign key ke tabel paket
        'username' => 'required|string|unique:tb_user,username',
    ]);

    // Simpan data pelanggan terlebih dahulu
    $pelanggan = Pelanggan::create([
        'nama_pelanggan' => $request->nama_pelanggan,
        'alamat' => $request->alamat,
        'no_telp' => $request->no_telp,
        'paket' => $request->paket,
    ]);

    // Simpan data user yang terhubung ke pelanggan
    Users::create([
        'username' => $request->username,
        'nama_user' => $request->nama_pelanggan,
        'password' => bcrypt('password123'), // default password
        'level' => 'pelanggan',
    ]);

    return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan!');
}
}
