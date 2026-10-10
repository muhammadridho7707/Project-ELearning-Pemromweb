@extends('layouts.app')

@section('title', $assignment->judul)

@section('content')
<a href="{{ route('siswa.courses.show', $course) }}" class="text-decoration-none">&larr; {{ $course->judul }}</a>

<h3 class="mt-2 mb-1">{{ $assignment->judul }}</h3>
<small class="text-muted">
    Deadline: {{ $assignment->deadline->format('d M Y, H:i') }}
    @if ($assignment->isClosed()) <span class="badge bg-danger">Ditutup</span> @endif
</small>

<div class="card shadow-sm my-4">
    <div class="card-body">
        <h6>Instruksi</h6>
        <p class="mb-0">{{ $assignment->deskripsi ?: 'Tidak ada instruksi.' }}</p>
    </div>
</div>

{{-- Pengumpulan saya --}}
@if ($submission)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white"><strong>Pengumpulan Saya</strong></div>
        <div class="card-body">
            <p class="mb-1">
                Dikumpulkan: {{ $submission->updated_at->format('d M Y, H:i') }}
                <a href="{{ route('siswa.assignments.download', [$course, $assignment]) }}"
                   class="btn btn-sm btn-outline-primary ms-2">Unduh file ({{ $submission->extension() }})</a>
            </p>
            @if ($submission->catatan)
                <p class="text-muted mb-1">Catatan: {{ $submission->catatan }}</p>
            @endif

            <hr>
            @if ($submission->isGraded())
                <h4 class="mb-1">Nilai: {{ $submission->nilai }}<small class="text-muted fs-6"> / 100</small></h4>
                <p class="mb-0">Feedback guru: {{ $submission->feedback ?: '-' }}</p>
            @else
                <span class="badge bg-info text-dark">Menunggu penilaian</span>
            @endif
        </div>
    </div>
@endif

{{-- Form kumpul / kumpul ulang --}}
@if ($submission && $submission->isGraded())
    <div class="alert alert-secondary">Tugas sudah dinilai, tidak bisa dikumpulkan ulang.</div>
@elseif ($assignment->isClosed())
    <div class="alert alert-danger">Deadline sudah lewat, pengumpulan ditutup.</div>
@else
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <strong>{{ $submission ? 'Kumpulkan Ulang' : 'Kumpulkan Tugas' }}</strong>
        </div>
        <div class="card-body">
            @if ($submission)
                <div class="alert alert-warning py-2">File lama akan diganti dengan file yang baru.</div>
            @endif

            <form action="{{ route('siswa.assignments.submit', [$course, $assignment]) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">File (maks. 10 MB)</label>
                    <input type="file" name="file"
                           class="form-control @error('file') is-invalid @enderror" required>
                    @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Catatan (opsional)</label>
                    <textarea name="catatan" rows="3"
                              class="form-control @error('catatan') is-invalid @enderror">{{ old('catatan', $submission->catatan ?? '') }}</textarea>
                    @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button class="btn btn-primary">{{ $submission ? 'Kirim Ulang' : 'Kumpulkan' }}</button>
            </form>
        </div>
    </div>
@endif
@endsection