<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    // Halaman pilihan: daftar sebagai guru atau siswa
    public function choose()
    {
        return view('auth.register-choose');
    }

    // Form pendaftaran sesuai role
    public function showForm(string $role)
    {
        return view('auth.register', ['role' => $role]);
    }

    public function store(Request $request, string $role)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
        ]);

        // Role diambil dari URL (sudah dibatasi guru|siswa di route),
        // BUKAN dari input form, supaya tidak bisa dimanipulasi.
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'], // di-hash otomatis oleh cast di model
            'role'     => $role,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Pendaftaran berhasil. Selamat datang, ' . $user->name . '!');
    }
}