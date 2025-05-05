<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Users;  // Model untuk tb_user
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;  // Untuk menyimpan file
use Illuminate\Support\Str;

class PenggunaController extends Controller
{
    // Registrasi Pengguna
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:tb_user,username',
            'nama_user' => 'required',
            'password' => 'required|min:6',
            'email' => 'required|email|unique:tb_user,email',  // Pastikan email unik
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Foto opsional, hanya validasi jika ada
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Proses upload foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('profiles', 'public'); // Simpan foto ke storage
        }

        // Membuat pengguna baru di tabel tb_user
        $user = Users::create([
            'username' => $request->username,
            'nama_user' => $request->nama_user,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => 'pelanggan',  // Default level pelanggan
            'foto' => $fotoPath, // Menyimpan path foto di database
        ]);

        // Generate token untuk pengguna yang baru terdaftar
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Pendaftaran berhasil!',
            'user' => $user,
            'token' => $token,
        ], 201);
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
        return response()->json(Auth::user(), 200);
    }
}
