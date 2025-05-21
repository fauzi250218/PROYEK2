<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan Auth

class PaketController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
    
        // Filter data paket berdasarkan pencarian
        $pakets = Paket::when($search, function ($query, $search) {
            return $query->where('nama_paket', 'like', "%{$search}%")
                         ->orWhere('kecepatan', 'like', "%{$search}%")
                         ->orWhere('harga', 'like', "%{$search}%")
                         ->orWhere('kategori', 'like', "%{$search}%");
        })->paginate(5)->withQueryString(); // ✅ Tetap bawa query pencarian saat pindah halaman
    
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
            'harga' => 'required|integer|min:0',
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
        'harga' => 'required|numeric|min:0',
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
