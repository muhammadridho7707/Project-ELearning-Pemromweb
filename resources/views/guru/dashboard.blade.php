@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="row g-4">
    {{-- Profil --}}
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center p-4">
                <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:80px;height:80px;font-size:2rem;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <h5 class="mb-0">{{ auth()->user()->name }}</h5>
                <small class="text-muted">{{ auth()->user()->email }}</small>
                <div class="mt-2"><span class="badge bg-success">Guru</span></div>
                <p class="mt-3 mb-0 text-muted small">
                    {{ auth()->user()->bio ?: 'Belum ada bio.' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Kelas yang diajar --}}
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Kelas yang Saya Ajar ({{ $courses->count() }})</h4>
            <a href="{{ route('guru.courses.create') }}" class="btn btn-primary">+ Tambah Kelas</a>
        </div>

        @forelse ($courses as $course)
            <div class="card shadow-sm mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">{{ $course->judul }}</h5>
                        <small class="text-muted">{{ $course->students_count }} siswa terdaftar</small>
                    </div>
                    <a href="{{ route('guru.courses.show', $course) }}" class="btn btn-outline-primary btn-sm">Buka</a>
                </div>
            </div>
        @empty
            <div class="alert alert-info">
                Kamu belum punya kelas. Klik <strong>+ Tambah Kelas</strong> untuk membuat yang pertama.
            </div>
        @endforelse
    </div>
</div>
@endsection