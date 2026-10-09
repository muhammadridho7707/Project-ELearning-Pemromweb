@extends('layouts.app')

@section('title', $course->judul)

@section('content')
<a href="{{ route('siswa.courses.index') }}" class="text-decoration-none">&larr; Daftar kelas</a>

<div class="d-flex justify-content-between align-items-start mt-2 mb-3">
    <div>
        <h3 class="mb-1">{{ $course->judul }}</h3>
        <small class="text-muted">
            Pengajar: {{ $course->teacher->name }} &middot; {{ $course->students_count }} siswa terdaftar
        </small>
    </div>

    @if ($isEnrolled)
        <form action="{{ route('siswa.courses.unenroll', $course) }}" method="POST" class="m-0"
              onsubmit="return confirm('Yakin ingin keluar dari kelas ini?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger">Keluar dari Kelas</button>
        </form>
    @else
        <form action="{{ route('siswa.courses.enroll', $course) }}" method="POST" class="m-0">
            @csrf
            <button class="btn btn-primary">Daftar Kelas</button>
        </form>
    @endif
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h6>Deskripsi</h6>
        <p class="mb-0">{{ $course->deskripsi ?: 'Belum ada deskripsi.' }}</p>
    </div>
</div>

@if ($isEnrolled)
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white"><strong>Materi ({{ $materials->count() }})</strong></div>
                <ul class="list-group list-group-flush">
                    @forelse ($materials as $material)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                {{ $material->judul }}
                                <span class="badge bg-secondary">{{ $material->extension() }}</span>
                                @if ($material->deskripsi)
                                    <div class="small text-muted">{{ $material->deskripsi }}</div>
                                @endif
                                <div class="small text-muted">{{ $material->created_at->format('d M Y') }}</div>
                            </div>
                            <a href="{{ route('siswa.materials.download', [$course, $material]) }}"
                               class="btn btn-sm btn-outline-primary">Unduh</a>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Guru belum mengunggah materi.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white"><strong>Tugas</strong></div>
                <div class="card-body text-muted">Daftar tugas dan pengumpulan akan tampil di sini (Fase 5B).</div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-secondary">
        Daftar ke kelas ini untuk mengakses materi dan tugas.
    </div>
@endif
@endsection