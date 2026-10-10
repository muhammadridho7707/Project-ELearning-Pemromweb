@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h3 class="mb-0">Kelas yang Saya Ambil</h3>
        <small class="text-muted">{{ $myCourses->count() }} kelas</small>
    </div>
    <a href="{{ route('siswa.courses.index') }}" class="btn btn-primary">
        <i class="bi bi-search me-1"></i> Cari Kelas
    </a>
</div>

<div class="row g-3">
    @forelse ($myCourses as $course)
        <div class="col-md-6 col-xl-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5>{{ $course->judul }}</h5>
                    <small class="text-muted mb-2">
                        <i class="bi bi-person-fill"></i> {{ $course->teacher->name }}
                    </small>
                    <p class="small text-muted flex-grow-1">
                        {{ \Illuminate\Support\Str::limit($course->deskripsi, 90) ?: 'Tanpa deskripsi.' }}
                    </p>
                    <a href="{{ route('siswa.courses.show', $course) }}" class="btn btn-outline-primary btn-sm">Buka Kelas</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info mb-0">
                Kamu belum mengambil kelas apa pun. Klik <strong>Cari Kelas</strong> untuk mulai.
            </div>
        </div>
    @endforelse
</div>
@endsection