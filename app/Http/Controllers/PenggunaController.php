<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
    
        $users = Users::query()
            ->when($search, function ($query, $search) {
                $query->where('nama_user', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('level', 'like', "%{$search}%");
            })
            ->paginate(3)
            ->withQueryString(); // agar search tetap saat pindah halaman
    
        return view('pengguna.index', compact('users', 'user'));
    }
    
    public function create()
    {
        return view('pengguna.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:tb_user,username',
            'nama_user' => 'required',
            'password' => 'required|min:6',
            'level' => 'required|in:admin,pelanggan',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validasi foto
        ]);
    
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('profiles', 'public'); // Simpan ke storage
        }
    
        Users::create([
            'username' => $request->username,
            'nama_user' => $request->nama_user,
            'password' => bcrypt($request->password),
            'level' => $request->level,
            'foto' => $fotoPath, // Simpan path foto
        ]);
    
        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }
    
    public function update(Request $request, $id)
    {
        $user = Users::findOrFail($id);
    
        $request->validate([
            'username' => 'required|unique:tb_user,username,' . $id,
            'nama_user' => 'required',
            'level' => 'required|in:admin,pelanggan',
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validasi foto
        ]);
    
        $user->username = $request->username;
        $user->nama_user = $request->nama_user;
        $user->level = $request->level;
    
        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
    
        // Jika ada foto baru, upload dan ganti
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('profiles', 'public'); // Simpan ke storage
            $user->foto = $fotoPath;
        }
    
        // Simpan perubahan
        $user->save();    
    
        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil diperbarui.');
    }    
    public function edit($id)
    {
        $user = Users::findOrFail($id);
        return view('pengguna.edit', compact('user'));
    }

    public function destroy($id)
    {
        $user = Users::findOrFail($id);
        $user->delete();

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
