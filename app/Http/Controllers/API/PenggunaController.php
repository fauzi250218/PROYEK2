<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;  // Pastikan Auth sudah diimport
use Illuminate\Support\Facades\Log;  // Untuk logging
use Illuminate\Support\Facades\Storage;

class PenggunaController extends Controller
{
    // Registrasi Pengguna
    public function register(Request $request)
    {
        // Log data request untuk debugging
        Log::info('Data yang diterima untuk registrasi: ', $request->all());

        // Validasi input tanpa email
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:tb_user,username',
            'nama_user' => 'required',
            'password' => 'required|min:6',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Foto opsional
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Proses upload foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            try {
                $fotoPath = $request->file('foto')->store('profiles', 'public'); // Simpan foto ke storage
                Log::info('Foto berhasil di-upload ke: ', ['fotoPath' => $fotoPath]);
            } catch (\Exception $e) {
                // Log error saat meng-upload foto
                Log::error('Error saat meng-upload foto: ', ['error' => $e->getMessage()]);
                return response()->json(['message' => 'Terjadi kesalahan saat meng-upload foto.'], 500);
            }
        }

        // Membuat pengguna baru di tabel tb_user tanpa email
        try {
            $user = Users::create([
                'username' => $request->username,
                'nama_user' => $request->nama_user,
                'password' => Hash::make($request->password),
                'level' => 'pelanggan',  // Default level pelanggan
                'foto' => $fotoPath,  // Menyimpan path foto di database
            ]);

            // Generate token untuk pengguna yang baru terdaftar
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => 'Pendaftaran berhasil!',
                'user' => $user,
                'token' => $token,
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat registrasi pengguna.'], 500);
        }
    }

    // Login Pengguna
    public function login(Request $request)
    {
        // Validasi input login
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Cek kredensial pengguna
        $user = Users::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Kredensial tidak valid'], 401);
        }

        // Generate token untuk pengguna yang berhasil login
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil!',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    // Menampilkan data pengguna yang sedang login
    public function userProfile()
    {
        // Cek jika pengguna telah terautentikasi
        if (Auth::check()) {
            return response()->json(Auth::user(), 200);  // Mengembalikan data pengguna yang terautentikasi
        } else {
            return response()->json(['message' => 'User not authenticated'], 401);  // Jika tidak terautentikasi
        }
    }
}
