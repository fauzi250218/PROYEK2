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

    public function edit($id)
    {
        $user = Auth::user();
        $paket = Paket::findOrFail($id); // Pastikan paket ditemukan
    
        return view('paket.edit', compact('paket', 'user'));
    }    

    public function update(Request $request, $id)
{
    $request->validate([
        'nama_paket' => 'required',
        'kecepatan' => 'required|numeric',
        'harga' => 'required|numeric',
        'kategori' => 'required',
    ]);

    $paket = Paket::findOrFail($id);
    $paket->update([
        'nama_paket' => $request->nama_paket,
        'kecepatan' => $request->kecepatan,
        'harga' => $request->harga,
        'kategori' => $request->kategori,
    ]);

    return redirect()->route('paket.index')->with('success', 'Paket berhasil diperbarui.');
}

public function destroy($id)
{
    $paket = Paket::findOrFail($id);
    $paket->delete();

    return redirect()->route('paket.index')->with('success', 'Paket berhasil dihapus.');
}
}
