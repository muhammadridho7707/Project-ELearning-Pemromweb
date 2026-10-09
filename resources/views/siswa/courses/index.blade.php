@extends('layouts.app')

@section('title', 'Daftar Kelas')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h3 class="mb-0">Daftar Kelas</h3>

    <form action="{{ route('siswa.courses.index') }}" method="GET" class="d-flex gap-2">
        <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari kelas...">
        <button class="btn btn-primary">Cari</button>
        @if ($q !== '')
            <a href="{{ route('siswa.courses.index') }}" class="btn btn-outline-secondary">Reset</a>
        @endif
    </form>
</div>

<div class="row g-3">
    @forelse ($courses as $course)
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start">
                        <h5>{{ $course->judul }}</h5>
                        @if (in_array($course->id, $enrolledIds))
                            <span class="badge bg-success">Terdaftar</span>
                        @endif
                    </div>
                    <small class="text-muted mb-2">
                        {{ $course->teacher->name }} &middot; {{ $course->students_count }} siswa
                    </small>
                    <p class="text-muted small flex-grow-1">
                        {{ \Illuminate\Support\Str::limit($course->deskripsi, 100) ?: 'Tanpa deskripsi.' }}
                    </p>
                    <a href="{{ route('siswa.courses.show', $course) }}" class="btn btn-outline-primary btn-sm">Lihat detail</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-warning">
                {{ $q !== '' ? 'Tidak ada kelas yang cocok dengan pencarianmu.' : 'Belum ada kelas yang dibuat guru.' }}
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $courses->links() }}
</div>
@endsection