<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\Users;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $pelanggan = Users::where('level', 'pelanggan')
        ->with(['pelanggan.data_paket']) 
        ->when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('username', 'like', "%{$search}%")
                    ->orWhereHas('pelanggan', function ($query) use ($search) {
                        $query->where('nama_pelanggan', 'like', "%{$search}%")
                            ->orWhere('alamat', 'like', "%{$search}%")
                            ->orWhere('no_telp', 'like', "%{$search}%");
                    });
            });
        })
        ->paginate(5)
        ->withQueryString();    
        return view('pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        $data_paket = Paket::all();
        return view('pelanggan.create', compact('data_paket'));
    }

    public function store(Request $request)
    {
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
            'id_pelanggan' => $pelanggan->id_pelanggan,
        ]);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    public function edit($id_pelanggan)
    {
        $pelanggan = Pelanggan::with('user')->findOrFail($id_pelanggan); // include user-nya
        $data_paket = Paket::all();
    
        return view('pelanggan.edit', compact('pelanggan', 'data_paket'));
    }      

    public function update(Request $request, $id_pelanggan)
    {
        $pelanggan = Pelanggan::with('user')->findOrFail($id_pelanggan);
        $user = $pelanggan->user;
    
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
            'paket' => 'required|exists:data_paket,id',
            'username' => 'required|string|unique:tb_user,username,' . ($user->id ?? 'NULL'),
        ]);
    
        $pelanggan->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'paket' => $request->paket,
        ]);
    
        if ($user) {
            $user->update([
                'nama_user' => $request->nama_pelanggan,
                'username' => $request->username,
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

        // Hapus data pelanggan
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus!');
    }

    public function sinkronDataLama()
    {
        $users = Users::where('level', 'pelanggan')->whereNull('id_pelanggan')->get();

        foreach ($users as $user) {
            $pelanggan = Pelanggan::where('nama_pelanggan', $user->nama_user)->first();
            if ($pelanggan) {
                $user->id_pelanggan = $pelanggan->id_pelanggan;
                $user->save();
            }
        }

        return "Sinkronisasi selesai. Cek halaman Data Pelanggan lagi.";
    }
}
