<div class="mb-3">
    <label class="form-label">Judul Kelas</label>
    <input type="text" name="judul" value="{{ old('judul', $course->judul ?? '') }}"
           class="form-control @error('judul') is-invalid @enderror" required autofocus>
    @error('judul')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea name="deskripsi" rows="5"
              class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $course->deskripsi ?? '') }}</textarea>
    @error('deskripsi')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>