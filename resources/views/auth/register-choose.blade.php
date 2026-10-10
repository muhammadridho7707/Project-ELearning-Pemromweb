@extends('layouts.guest')

@section('title', 'Daftar')
@section('width', '760px')

@section('content')
<h4 class="text-center mb-4">Daftar sebagai apa?</h4>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm h-100 text-center">
            <div class="card-body p-4">
                <i class="bi bi-backpack2-fill text-primary fs-1"></i>
                <h5 class="mt-2">Siswa</h5>
                <p class="text-muted">Ikuti kelas, pelajari materi, dan kumpulkan tugas.</p>
                <a href="{{ route('register.form', 'siswa') }}" class="btn btn-primary">Daftar sebagai Siswa</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm h-100 text-center">
            <div class="card-body p-4">
                <i class="bi bi-easel2-fill text-success fs-1"></i>
                <h5 class="mt-2">Guru</h5>
                <p class="text-muted">Buat kelas, unggah materi, dan nilai tugas siswa.</p>
                <a href="{{ route('register.form', 'guru') }}" class="btn btn-success">Daftar sebagai Guru</a>
            </div>
        </div>
    </div>
</div>

<p class="text-center mt-4 mb-0">
    Sudah punya akun? <a href="{{ route('login') }}">Login</a>
</p>
@endsection