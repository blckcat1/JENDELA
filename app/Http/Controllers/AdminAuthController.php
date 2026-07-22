<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Menampilkan halaman login admin.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/admin/kurasi');
        }
        return view('admin.login');
    }

    /**
     * Memproses otorisasi autentikasi admin.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'required' => ':attribute wajib diisi.',
            'email' => 'Format email tidak valid.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            if ($user->peran_pengguna !== 'Admin Perpustakaan') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'Akses ditolak. Akun Anda bukan Admin Perpustakaan.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended('/admin/kurasi');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi admin tidak cocok.',
        ])->onlyInput('email');
    }

    /**
     * Mengakhiri sesi login admin.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
