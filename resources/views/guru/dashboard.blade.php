@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h3 class="mb-0">Kelas yang Saya Ajar</h3>
        <small class="text-muted">{{ $courses->count() }} kelas</small>
    </div>
    <a href="{{ route('guru.courses.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kelas
    </a>
</div>

<div class="row g-3">
    @forelse ($courses as $course)
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5>{{ $course->judul }}</h5>
                    <small class="text-muted mb-2">
                        <i class="bi bi-people-fill"></i> {{ $course->students_count }} siswa terdaftar
                    </small>
                    <p class="small text-muted flex-grow-1">
                        {{ \Illuminate\Support\Str::limit($course->deskripsi, 90) ?: 'Tanpa deskripsi.' }}
                    </p>
                    <a href="{{ route('guru.courses.show', $course) }}" class="btn btn-outline-primary btn-sm">Kelola Kelas</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info mb-0">
                Kamu belum punya kelas. Klik <strong>Tambah Kelas</strong> untuk membuat yang pertama.
            </div>
        </div>
    @endforelse
</div>
@endsection