@extends('layouts.app')

@section('title', 'Edit Tugas')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="mb-4">Edit Tugas</h4>

                <form action="{{ route('guru.assignments.update', [$course, $assignment]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Judul Tugas</label>
                        <input type="text" name="judul" value="{{ old('judul', $assignment->judul) }}"
                               class="form-control @error('judul') is-invalid @enderror" required>
                        @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deadline</label>
                        <input type="datetime-local" name="deadline"
                               value="{{ old('deadline', $assignment->deadline->format('Y-m-d\TH:i')) }}"
                               class="form-control @error('deadline') is-invalid @enderror" required>
                        @error('deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi / Instruksi</label>
                        <textarea name="deskripsi" rows="5"
                                  class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $assignment->deskripsi) }}</textarea>
                        @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button class="btn btn-primary">Perbarui</button>
                    <a href="{{ route('guru.assignments.show', [$course, $assignment]) }}" class="btn btn-link">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection