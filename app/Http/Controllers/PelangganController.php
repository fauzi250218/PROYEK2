<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Users::where('level', 'pelanggan')->get();
        return view('pelanggan.index', compact('pelanggan'));
    }    

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_pelanggan' => 'required|string|max:255',
        'alamat' => 'required|string',
        'no_telp' => 'required|string|max:15',
        'paket' => 'required|string',
        'username' => 'required|string|unique:tb_user,username',
    ]);

    // 1️⃣ Buat User dulu (tanpa id_pelanggan)
    $pelanggan = Pelanggan::create([
        'nama_pelanggan' => $request->nama_pelanggan,
        'alamat' => $request->alamat,
        'no_telp' => $request->no_telp,
        'paket' => $request->paket,
    ]);
    
    $user = Users::create([
        'username' => $request->username,
        'nama_user' => $request->nama_pelanggan,
        'password' => bcrypt('password123'),
        'level' => 'pelanggan',
        'id_pelanggan' => $pelanggan->id, // Pakai ID pelanggan yang baru dibuat
    ]);
    
    // 3️⃣ Update id_pelanggan di tabel Users
    $user->update(['id_pelanggan' => $pelanggan->id_pelanggan]);

    return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan!');
}

    public function edit($id_pelanggan)
    {
        $pelanggan = Pelanggan::findOrFail($id_pelanggan);
        return view('pelanggan.edit', compact('pelanggan'));
    }
    
    public function update(Request $request, $id_pelanggan)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
            'paket' => 'required|string',
        ]);
    
        $pelanggan = Pelanggan::findOrFail($id_pelanggan);
        $pelanggan->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'paket' => $request->paket,
        ]);
    
        // Update juga di Users
        $user = Users::where('id_pelanggan', $id_pelanggan)->first();
        if ($user) {
            $user->update([
                'nama_user' => $request->nama_pelanggan,
            ]);
        }
    
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui!');
    }    
    public function destroy($id_pelanggan)
    {
        $pelanggan = Pelanggan::findOrFail($id_pelanggan);
    
        // Hapus data user yang terkait
        $user = Users::where('id_pelanggan', $id_pelanggan)->first();
        if ($user) {
            $user->delete();
        }
    
        // Hapus pelanggan
        $pelanggan->delete();
    
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus!');
    }
    
}
