<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function download(Course $course, Assignment $assignment, Submission $submission)
    {
        $this->check($course, $assignment, $submission);

        abort_unless(Storage::exists($submission->file_path), 404, 'File tidak ditemukan.');

        return Storage::download($submission->file_path, $submission->downloadName());
    }

    public function grade(Request $request, Course $course, Assignment $assignment, Submission $submission)
    {
        $this->check($course, $assignment, $submission);

        $data = $request->validate([
            'nilai'    => ['required', 'integer', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string', 'max:1000'],
        ], [
            'nilai.required' => 'Nilai wajib diisi.',
            'nilai.integer'  => 'Nilai harus berupa angka.',
            'nilai.min'      => 'Nilai minimal 0.',
            'nilai.max'      => 'Nilai maksimal 100.',
        ]);

        $submission->update($data);

        return back()->with('success', 'Nilai untuk ' . $submission->student->name . ' tersimpan.');
    }

    // Pastikan guru pemilik kelas dan rantai kelas > tugas > pengumpulan cocok
    private function check(Course $course, Assignment $assignment, Submission $submission): void
    {
        abort_if($course->teacher_id !== auth()->id(), 403, 'Ini bukan kelas Anda.');
        abort_if($assignment->course_id !== $course->id, 404);
        abort_if($submission->assignment_id !== $assignment->id, 404);
    }
}