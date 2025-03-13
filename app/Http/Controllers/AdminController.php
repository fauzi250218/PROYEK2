<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('login_admin');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->level === 'admin') {
                return redirect()->route('beranda_admin');
            }

            Auth::logout(); // Logout jika bukan admin
        }

        return back()->withErrors(['login' => 'Username atau password salah']);
    }

    public function showBeranda()
    {
        return view('beranda_admin');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login_admin');
    }
}
