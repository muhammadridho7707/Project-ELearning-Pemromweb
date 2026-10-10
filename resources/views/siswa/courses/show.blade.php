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
        {{-- Materi --}}
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

        {{-- Tugas --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white"><strong>Tugas ({{ $assignments->count() }})</strong></div>
                <ul class="list-group list-group-flush">
                    @forelse ($assignments as $assignment)
                        @php $sub = $assignment->submissions->first(); @endphp
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                {{ $assignment->judul }}
                                @if ($sub && $sub->isGraded())
                                    <span class="badge bg-success">Nilai: {{ $sub->nilai }}</span>
                                @elseif ($sub)
                                    <span class="badge bg-info text-dark">Terkumpul</span>
                                @elseif ($assignment->isClosed())
                                    <span class="badge bg-danger">Ditutup</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum dikumpulkan</span>
                                @endif
                                <div class="small text-muted">Deadline: {{ $assignment->deadline->format('d M Y, H:i') }}</div>
                            </div>
                            <a href="{{ route('siswa.assignments.show', [$course, $assignment]) }}"
                               class="btn btn-sm btn-outline-primary">Buka</a>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Belum ada tugas.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-secondary">
        Daftar ke kelas ini untuk mengakses materi dan tugas.
    </div>
@endif
@endsection