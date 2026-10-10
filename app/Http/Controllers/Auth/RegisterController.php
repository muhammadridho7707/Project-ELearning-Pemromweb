<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function choose()
    {
        return view('auth.register-choose');
    }

    public function showForm(string $role)
    {
        return view('auth.register', ['role' => $role]);
    }

    public function store(Request $request, string $role)
    {
        $rules = [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ];

        // Pendaftaran guru wajib kode undangan
        if ($role === 'guru') {
            $rules['kode_undangan'] = ['required', 'string'];
        }

        $data = $request->validate($rules, [
            'name.required'          => 'Nama wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email sudah terdaftar.',
            'password.required'      => 'Password wajib diisi.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
            'password.min'           => 'Password minimal 8 karakter.',
            'kode_undangan.required' => 'Kode undangan wajib diisi.',
        ]);

        if ($role === 'guru') {
            $this->verifyInviteCode($data['kode_undangan']);
        }

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'role'     => $role,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Pendaftaran berhasil. Selamat datang, ' . $user->name . '!');
    }

    private function verifyInviteCode(string $input): void
    {
        $valid = (string) config('app.teacher_invite_code');

        // Jika kode belum diset di .env, pendaftaran guru ditutup (aman secara default)
        // hash_equals: perbandingan waktu konstan, tahan serangan timing
        if ($valid === '' || ! hash_equals($valid, $input)) {
            throw ValidationException::withMessages([
                'kode_undangan' => 'Kode undangan tidak valid.',
            ]);
        }
    }
}