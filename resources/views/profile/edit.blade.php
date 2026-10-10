@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<h3 class="mb-4">Profil Saya</h3>

<div class="row g-4">
    {{-- ===== Kolom kiri: foto ===== --}}
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body text-center p-4">
                @include('partials.avatar', ['user' => $user, 'size' => 120])

                <h5 class="mt-3 mb-0">{{ $user->name }}</h5>
                <small class="text-muted">{{ $user->email }}</small>
                <div class="mt-2">
                    <span class="badge text-bg-{{ $user->isGuru() ? 'success' : 'primary' }} text-uppercase">{{ $user->role }}</span>
                </div>

                <hr>

                <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="text-start">
                    @csrf
                    <label class="form-label">Ganti foto (maks. 2 MB)</label>
                    <input type="file" name="foto" accept="image/*"
                           class="form-control @error('foto') is-invalid @enderror" required>
                    @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <button class="btn btn-primary btn-sm w-100 mt-2">
                        <i class="bi bi-upload me-1"></i> Unggah Foto
                    </button>
                </form>

                @if ($user->foto)
                    <form action="{{ route('profile.photo.destroy') }}" method="POST" class="mt-2"
                          onsubmit="return confirm('Hapus foto profil?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm w-100">Hapus Foto</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== Kolom kanan: data diri dan password ===== --}}
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header"><strong>Informasi Profil</strong></div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" value="{{ $user->email }}" class="form-control" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" rows="4" maxlength="500"
                                  class="form-control @error('bio') is-invalid @enderror"
                                  placeholder="Ceritakan sedikit tentang dirimu...">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header"><strong>Ganti Password</strong></div>
            <div class="card-body">
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="current_password"
                               class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button class="btn btn-warning">Ganti Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection