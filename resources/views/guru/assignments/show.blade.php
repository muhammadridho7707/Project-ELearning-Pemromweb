@extends('layouts.app')

@section('title', $assignment->judul)

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <a href="{{ route('guru.courses.show', $course) }}" class="text-decoration-none">&larr; {{ $course->judul }}</a>
        <h3 class="mt-1 mb-1">{{ $assignment->judul }}</h3>
        <small class="text-muted">
            Deadline: {{ $assignment->deadline->format('d M Y, H:i') }}
            @if ($assignment->isClosed()) <span class="badge bg-danger">Ditutup</span> @endif
        </small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('guru.assignments.edit', [$course, $assignment]) }}" class="btn btn-outline-secondary">Edit</a>
        <form action="{{ route('guru.assignments.destroy', [$course, $assignment]) }}" method="POST" class="m-0"
              onsubmit="return confirm('Hapus tugas ini beserta semua pengumpulannya?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger">Hapus</button>
        </form>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <p class="mb-0">{{ $assignment->deskripsi ?: 'Tidak ada instruksi.' }}</p>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <strong>Pengumpulan Siswa</strong>
        <span class="text-muted small">({{ $submissions->count() }} dari {{ $students->count() }} terkumpul)</span>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Siswa</th>
                    <th>Status</th>
                    <th>File</th>
                    <th style="min-width:330px">Nilai &amp; Feedback</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    @php $sub = $submissions->get($student->id); @endphp
                    <tr>
                        <td>
                            {{ $student->name }}
                            <div class="small text-muted">{{ $student->email }}</div>
                        </td>
                        <td>
                            @if ($sub)
                                <span class="badge bg-success">Terkumpul</span>
                                <div class="small text-muted">{{ $sub->updated_at->format('d M, H:i') }}</div>
                                @if ($sub->catatan)
                                    <div class="small text-muted">Catatan: {{ $sub->catatan }}</div>
                                @endif
                            @else
                                <span class="badge bg-secondary">Belum</span>
                            @endif
                        </td>
                        <td>
                            @if ($sub)
                                <a href="{{ route('guru.submissions.download', [$course, $assignment, $sub]) }}"
                                   class="btn btn-sm btn-outline-primary">Unduh ({{ $sub->extension() }})</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($sub)
                                <form action="{{ route('guru.submissions.grade', [$course, $assignment, $sub]) }}"
                                      method="POST" class="d-flex gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="nilai" min="0" max="100" value="{{ $sub->nilai }}"
                                           class="form-control form-control-sm" style="width:80px" placeholder="0-100" required>
                                    <input type="text" name="feedback" value="{{ $sub->feedback }}"
                                           class="form-control form-control-sm" placeholder="Feedback">
                                    <button class="btn btn-sm btn-success">Simpan</button>
                                </form>
                            @else
                                <span class="text-muted">Menunggu pengumpulan</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada siswa di kelas ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
@endif
@endsection