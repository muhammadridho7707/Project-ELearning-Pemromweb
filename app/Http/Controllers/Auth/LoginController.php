<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Parameter kedua = remember me (membuat cookie remember_web_xxx)
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Ganti ID sesi setelah login (mencegah session fixation)
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Login berhasil.');
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();      // hapus data sesi
        $request->session()->regenerateToken(); // token CSRF baru

        return redirect()->route('login')
            ->with('success', 'Anda berhasil logout.');
    }
}