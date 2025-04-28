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
        $paket = Paket::all();

        $paket = $paket->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama_paket,
                'kategori' => $item->kategori,
                'kecepatan' => $item->kecepatan,
                'harga' => $item->harga,
            ];
        });

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
        $item = Paket::find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Paket tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'paket' => [
                'id' => $item->id,
                'nama' => $item->nama_paket,
                'kategori' => $item->kategori,
                'kecepatan' => $item->kecepatan,
                'harga' => $item->harga,
            ],
        ]);
    }
}
