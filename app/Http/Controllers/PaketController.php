<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan Auth

class PaketController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Ambil data user yang login
        $pakets = Paket::all();
        return view('paket.index', compact('pakets', 'user'));
    }

    public function create()
    {
        $user = Auth::user(); // Pastikan user dikirim ke view
        return view('paket.create', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required',
            'kecepatan' => 'required|integer',
            'harga' => 'required|integer',
            'kategori' => 'required'
        ]);

        Paket::create($request->all());

        return redirect()->route('paket.index')->with('success', 'Paket berhasil ditambahkan!');
    }

    public function edit(Paket $paket)
    {
        $user = Auth::user(); // Tambahkan user
        return view('paket.edit', compact('paket', 'user'));
    }

    public function update(Request $request, Paket $paket)
    {
        $request->validate([
            'nama_paket' => 'required',
            'kecepatan' => 'required|integer',
            'harga' => 'required|integer',
            'kategori' => 'required'
        ]);

        $paket->update($request->all());

        return redirect()->route('paket.index')->with('success', 'Paket berhasil diperbarui!');
    }

    public function destroy(Paket $paket)
    {
        $paket->delete();
        return redirect()->route('paket.index')->with('success', 'Paket berhasil dihapus!');
    }
}
