<?php

// app/Http/Controllers/API/TagihanApiController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;

class TagihanApiController extends Controller
{
    /**
     * Menampilkan semua data tagihan.
     */
    public function index()
    {
        // Ambil semua data tagihan dengan relasi ke pelanggan dan data_paket
        $tagihan = Tagihan::with('pelanggan.user', 'pelanggan.data_paket')->get();

        // Format data agar hanya mengirimkan data yang diperlukan
        $tagihan = $tagihan->map(function ($item) {
            return [
                'id' => $item->id,
                'id_pelanggan' => $item->id_pelanggan,
                'nama_pelanggan' => $item->pelanggan->nama_pelanggan,
                'paket' => $item->pelanggan->data_paket->nama_paket,
                'jumlah' => (string) $item->jumlah,  // Pastikan jumlah dikirim sebagai string
                'bulan' => $item->bulan,
                'tahun' => $item->tahun,
                'status' => $item->status,
            ];
        });

        // Kirimkan data sebagai respons JSON
        return response()->json([
            'success' => true,
            'tagihan' => $tagihan,
        ]);
    }

    /**
     * Menampilkan detail tagihan berdasarkan ID.
     */
    public function show($id)
    {
        // Mencari tagihan berdasarkan ID pelanggan
        $item = Tagihan::with('pelanggan.user', 'pelanggan.data_paket')->find($id);

        if (!$item) {
            // Jika tagihan tidak ditemukan, kirimkan pesan error
            return response()->json([
                'success' => false,
                'message' => 'Tagihan tidak ditemukan'
            ], 404);
        }

        // Kirimkan data tagihan dengan detail
        return response()->json([
            'success' => true,
            'tagihan' => [
                'id' => $item->id,
                'id_pelanggan' => $item->id_pelanggan,
                'nama_pelanggan' => $item->pelanggan->nama_pelanggan,
                'paket' => $item->pelanggan->data_paket->nama_paket,
                'jumlah' => (string) $item->jumlah,  // Pastikan jumlah dikirim sebagai string
                'bulan' => $item->bulan,
                'tahun' => $item->tahun,
                'status' => $item->status,
            ],
        ]);
    }
}

