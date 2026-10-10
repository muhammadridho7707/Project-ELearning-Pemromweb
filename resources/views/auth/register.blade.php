@extends('layouts.guest')

@section('title', 'Daftar ' . ucfirst($role))

@section('content')
<div class="card shadow-sm">
    <div class="card-body p-4">
        <h4 class="mb-4 text-center">Daftar sebagai {{ ucfirst($role) }}</h4>

        <form action="{{ route('register.store', $role) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="form-control @error('name') is-invalid @enderror" required autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            @if ($role === 'guru')
                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-key-fill me-1"></i> Kode Undangan Guru
                    </label>
                    <input type="text" name="kode_undangan" value="{{ old('kode_undangan') }}"
                           class="form-control @error('kode_undangan') is-invalid @enderror"
                           autocomplete="off" required>
                    @error('kode_undangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Kode diberikan oleh pihak sekolah kepada guru.</div>
                </div>
            @endif

            <button class="btn btn-primary w-100">Daftar</button>
        </form>

        <p class="text-center mt-3 mb-0">
            <a href="{{ route('register') }}">&larr; Ganti peran</a>
        </p>
    </div>
</div>
@endsection