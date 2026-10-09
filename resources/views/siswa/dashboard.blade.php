@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="row g-4">
    {{-- Profil --}}
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center p-4">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:80px;height:80px;font-size:2rem;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <h5 class="mb-0">{{ auth()->user()->name }}</h5>
                <small class="text-muted">{{ auth()->user()->email }}</small>
                <div class="mt-2"><span class="badge bg-primary">Siswa</span></div>
                <p class="mt-3 mb-0 text-muted small">
                    {{ auth()->user()->bio ?: 'Belum ada bio.' }}
                </p>
                <hr>
                <div class="fs-4">{{ $myCourses->count() }}</div>
                <small class="text-muted">Kelas diambil</small>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        {{-- Kelas yang diambil --}}
        <h4 class="mb-3">Kelas yang Saya Ambil</h4>

        @forelse ($myCourses as $course)
            <div class="card shadow-sm mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">{{ $course->judul }}</h5>
                        <small class="text-muted">Pengajar: {{ $course->teacher->name }}</small>
                    </div>
                    <a href="{{ route('siswa.courses.show', $course) }}" class="btn btn-outline-primary btn-sm">Buka</a>
                </div>
            </div>
        @empty
            <div class="alert alert-info">
                Kamu belum mengambil kelas apa pun. Pilih dari daftar kelas di bawah.
            </div>
        @endforelse

        {{-- Daftar kelas --}}
        <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
            <h4 class="mb-0">Daftar Kelas</h4>
            <a href="{{ route('siswa.courses.index') }}" class="btn btn-sm btn-primary">Lihat semua</a>
        </div>

        <div class="row g-3">
            @forelse ($availableCourses as $course)
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h6>{{ $course->judul }}</h6>
                            <small class="text-muted d-block mb-2">
                                {{ $course->teacher->name }} &middot; {{ $course->students_count }} siswa
                            </small>
                            <p class="small text-muted mb-3">{{ \Illuminate\Support\Str::limit($course->deskripsi, 80) ?: 'Tanpa deskripsi.' }}</p>
                            <a href="{{ route('siswa.courses.show', $course) }}" class="btn btn-sm btn-outline-primary">Lihat detail</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><p class="text-muted">Tidak ada kelas lain yang tersedia.</p></div>
            @endforelse
        </div>
    </div>
</div>
@endsection