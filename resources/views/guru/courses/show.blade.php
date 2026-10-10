@extends('layouts.app')

@section('title', $course->judul)

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <a href="{{ route('guru.courses.index') }}" class="text-decoration-none">&larr; Semua kelas</a>
        <h3 class="mt-1 mb-0">{{ $course->judul }}</h3>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('guru.courses.edit', $course) }}" class="btn btn-outline-secondary">Edit</a>
        <form action="{{ route('guru.courses.destroy', $course) }}" method="POST" class="m-0"
              onsubmit="return confirm('Hapus kelas ini beserta semua materi dan tugasnya?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger">Hapus</button>
        </form>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <p class="mb-0">{{ $course->deskripsi ?: 'Belum ada deskripsi.' }}</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-center shadow-sm"><div class="card-body">
            <div class="fs-3">{{ $course->students->count() }}</div><small class="text-muted">Siswa</small>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card text-center shadow-sm"><div class="card-body">
            <div class="fs-3">{{ $course->materials->count() }}</div><small class="text-muted">Materi</small>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card text-center shadow-sm"><div class="card-body">
            <div class="fs-3">{{ $course->assignments->count() }}</div><small class="text-muted">Tugas</small>
        </div></div>
    </div>
</div>

{{-- ===== Materi ===== --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white"><strong>Materi</strong></div>
    <div class="card-body">
        <form action="{{ route('guru.materials.store', $course) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Judul Materi</label>
                    <input type="text" name="judul" value="{{ old('judul') }}"
                           class="form-control @error('judul') is-invalid @enderror" required>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">File (maks. 10 MB)</label>
                    <input type="file" name="file"
                           class="form-control @error('file') is-invalid @enderror" required>
                    @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi (opsional)</label>
                    <textarea name="deskripsi" rows="2"
                              class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">Unggah Materi</button>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr><th>Judul</th><th>Tipe</th><th>Diunggah</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($course->materials as $material)
                    <tr>
                        <td>
                            {{ $material->judul }}
                            @if ($material->deskripsi)
                                <div class="small text-muted">{{ $material->deskripsi }}</div>
                            @endif
                        </td>
                        <td><span class="badge bg-secondary">{{ $material->extension() }}</span></td>
                        <td>{{ $material->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('guru.materials.download', [$course, $material]) }}"
                               class="btn btn-sm btn-outline-primary">Unduh</a>
                            <form action="{{ route('guru.materials.destroy', [$course, $material]) }}" method="POST"
                                  class="d-inline" onsubmit="return confirm('Hapus materi ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada materi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ===== Tugas ===== --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white"><strong>Tugas</strong></div>
    <div class="card-body">
        <form action="{{ route('guru.assignments.store', $course) }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Judul Tugas</label>
                    <input type="text" name="judul" value="{{ old('judul') }}"
                           class="form-control @error('judul') is-invalid @enderror" required>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Deadline</label>
                    <input type="datetime-local" name="deadline" value="{{ old('deadline') }}"
                           class="form-control @error('deadline') is-invalid @enderror" required>
                    @error('deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi / Instruksi</label>
                    <textarea name="deskripsi" rows="3"
                              class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">Buat Tugas</button>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr><th>Judul</th><th>Deadline</th><th class="text-center">Terkumpul</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($course->assignments as $assignment)
                    <tr>
                        <td>{{ $assignment->judul }}</td>
                        <td>
                            {{ $assignment->deadline->format('d M Y, H:i') }}
                            @if ($assignment->isClosed())
                                <span class="badge bg-danger">Ditutup</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $assignment->submissions_count }} / {{ $course->students->count() }}</td>
                        <td class="text-end">
                            <a href="{{ route('guru.assignments.show', [$course, $assignment]) }}"
                               class="btn btn-sm btn-outline-primary">Detail &amp; Nilai</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada tugas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ===== Daftar siswa ===== --}}
<div class="card shadow-sm">
    <div class="card-header bg-white"><strong>Daftar Siswa</strong></div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Nama</th><th>Email</th><th>Tanggal Daftar</th></tr>
            </thead>
            <tbody>
                @forelse ($course->students as $i => $student)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->pivot->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada siswa yang mendaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection