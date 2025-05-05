<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paket;

class PaketApiController extends Controller
{
    /**
     * Menampilkan semua data paket.
     */
    public function index()
    {
        // Ambil semua data paket
        $paket = Paket::all();

        // Format data agar hanya mengirimkan data yang diperlukan
        $paket = $paket->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama_paket,
                'kategori' => $item->kategori,
                'kecepatan' => $item->kecepatan,
                'harga' => (string) $item->harga,  // Pastikan harga dikirim sebagai string
            ];
        });

        // Kirimkan data sebagai respons JSON
        return response()->json([
            'success' => true,
            'paket' => $paket,
        ]);
    }

    /**
     * Menampilkan detail paket berdasarkan ID.
     */
    public function show($id)
    {
        // Mencari paket berdasarkan ID
        $item = Paket::find($id);

        if (!$item) {
            // Jika paket tidak ditemukan, kirimkan pesan error
            return response()->json([
                'success' => false,
                'message' => 'Paket tidak ditemukan'
            ], 404);
        }

        // Kirimkan data paket dengan detail
        return response()->json([
            'success' => true,
            'paket' => [
                'id' => $item->id,
                'nama' => $item->nama_paket,
                'kategori' => $item->kategori,
                'kecepatan' => $item->kecepatan,
                'harga' => (string) $item->harga,  // Pastikan harga dikirim sebagai string
            ],
        ]);
    }
}
