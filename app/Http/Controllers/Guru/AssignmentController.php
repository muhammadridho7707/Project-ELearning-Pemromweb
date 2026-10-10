<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $this->authorizeOwner($course);

        $data = $request->validate([
            'judul'     => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'deadline'  => ['required', 'date', 'after:now'],
        ], $this->messages());

        $assignment = $course->assignments()->create($data);

        return redirect()->route('guru.assignments.show', [$course, $assignment])
            ->with('success', 'Tugas berhasil dibuat.');
    }

    // Detail tugas + status pengumpulan semua siswa
    public function show(Course $course, Assignment $assignment)
    {
        $this->authorizeOwner($course);
        $this->ensureBelongs($course, $assignment);

        $students = $course->students()->orderBy('name')->get();
        $submissions = $assignment->submissions()->get()->keyBy('student_id');

        return view('guru.assignments.show', compact('course', 'assignment', 'students', 'submissions'));
    }

    public function edit(Course $course, Assignment $assignment)
    {
        $this->authorizeOwner($course);
        $this->ensureBelongs($course, $assignment);

        return view('guru.assignments.edit', compact('course', 'assignment'));
    }

    public function update(Request $request, Course $course, Assignment $assignment)
    {
        $this->authorizeOwner($course);
        $this->ensureBelongs($course, $assignment);

        $data = $request->validate([
            'judul'     => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'deadline'  => ['required', 'date'],
        ], $this->messages());

        $assignment->update($data);

        return redirect()->route('guru.assignments.show', [$course, $assignment])
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Course $course, Assignment $assignment)
    {
        $this->authorizeOwner($course);
        $this->ensureBelongs($course, $assignment);

        // Hapus semua file pengumpulan tugas ini
        Storage::deleteDirectory("submissions/{$assignment->id}");
        $assignment->delete();

        return redirect()->route('guru.courses.show', $course)
            ->with('success', 'Tugas berhasil dihapus.');
    }

    private function authorizeOwner(Course $course): void
    {
        abort_if($course->teacher_id !== auth()->id(), 403, 'Ini bukan kelas Anda.');
    }

    private function ensureBelongs(Course $course, Assignment $assignment): void
    {
        abort_if($assignment->course_id !== $course->id, 404);
    }

    private function messages(): array
    {
        return [
            'judul.required'    => 'Judul tugas wajib diisi.',
            'deadline.required' => 'Deadline wajib diisi.',
            'deadline.date'     => 'Format deadline tidak valid.',
            'deadline.after'    => 'Deadline harus setelah waktu sekarang.',
        ];
    }
}