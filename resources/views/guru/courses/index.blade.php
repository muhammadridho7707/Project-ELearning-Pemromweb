@extends('layouts.app')

@section('title', 'Kelas Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Kelas Saya</h3>
    <a href="{{ route('guru.courses.create') }}" class="btn btn-primary">+ Tambah Kelas</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Judul</th>
                    <th class="text-center">Siswa</th>
                    <th>Dibuat</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($courses as $course)
                    <tr>
                        <td>{{ $course->judul }}</td>
                        <td class="text-center">{{ $course->students_count }}</td>
                        <td>{{ $course->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('guru.courses.show', $course) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            <a href="{{ route('guru.courses.edit', $course) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Belum ada kelas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection