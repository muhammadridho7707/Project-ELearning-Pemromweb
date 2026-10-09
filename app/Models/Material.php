<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Material extends Model
{
    protected $fillable = ['course_id', 'judul', 'deskripsi', 'file_path'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Nama file saat diunduh: judul materi + ekstensi aslinya
    public function downloadName(): string
    {
        return Str::slug($this->judul) . '.' . pathinfo($this->file_path, PATHINFO_EXTENSION);
    }

    public function extension(): string
    {
        return strtoupper(pathinfo($this->file_path, PATHINFO_EXTENSION));
    }
}