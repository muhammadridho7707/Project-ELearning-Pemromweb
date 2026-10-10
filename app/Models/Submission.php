<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Submission extends Model
{
    protected $fillable = [
        'assignment_id', 'student_id', 'file_path',
        'catatan', 'nilai', 'feedback',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function isGraded(): bool
    {
        return $this->nilai !== null;
    }

    public function extension(): string
    {
        return strtoupper(pathinfo($this->file_path, PATHINFO_EXTENSION));
    }

    // Contoh nama unduhan: budi-tugas-1.pdf
    public function downloadName(): string
    {
        return Str::slug($this->student->name . ' ' . $this->assignment->judul)
            . '.' . pathinfo($this->file_path, PATHINFO_EXTENSION);
    }
}