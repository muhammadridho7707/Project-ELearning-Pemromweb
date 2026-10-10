<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function show(Course $course, Assignment $assignment)
    {
        $this->check($course, $assignment);

        $submission = $this->mySubmission($assignment);

        return view('siswa.assignments.show', compact('course', 'assignment', 'submission'));
    }

    public function submit(Request $request, Course $course, Assignment $assignment)
    {
        $this->check($course, $assignment);

        if ($assignment->isClosed()) {
            return back()->with('error', 'Deadline sudah lewat, pengumpulan ditutup.');
        }

        $existing = $this->mySubmission($assignment);

        if ($existing && $existing->isGraded()) {
            return back()->with('error', 'Tugas sudah dinilai, tidak bisa dikumpulkan ulang.');
        }

        $data = $request->validate([
            'file'    => ['required', 'file', 'max:10240',
                          'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,txt,jpg,jpeg,png'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ], [
            'file.required' => 'Pilih file tugas terlebih dahulu.',
            'file.max'      => 'Ukuran file maksimal 10 MB.',
            'file.mimes'    => 'Format file harus: pdf, doc, docx, ppt, pptx, xls, xlsx, zip, txt, jpg, atau png.',
            'file.uploaded' => 'Upload gagal. Ukuran file melebihi batas server (cek upload_max_filesize di php.ini).',
        ]);

        $path = $request->file('file')->store("submissions/{$assignment->id}");

        // Kumpul ulang: hapus file lama
        if ($existing) {
            Storage::delete($existing->file_path);
        }

        Submission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'student_id' => auth()->id()],
            ['file_path' => $path, 'catatan' => $data['catatan'] ?? null]
        );

        return redirect()->route('siswa.assignments.show', [$course, $assignment])
            ->with('success', 'Tugas berhasil dikumpulkan.');
    }

    // Siswa mengunduh file miliknya sendiri
    public function download(Course $course, Assignment $assignment)
    {
        $this->check($course, $assignment);

        $submission = $this->mySubmission($assignment);
        abort_unless($submission, 404, 'Kamu belum mengumpulkan tugas ini.');
        abort_unless(Storage::exists($submission->file_path), 404, 'File tidak ditemukan.');

        return Storage::download($submission->file_path, $submission->downloadName());
    }

    private function mySubmission(Assignment $assignment): ?Submission
    {
        return Submission::where('assignment_id', $assignment->id)
            ->where('student_id', auth()->id())
            ->first();
    }

    // Tugas harus milik kelas di URL, dan siswa harus terdaftar di kelas itu
    private function check(Course $course, Assignment $assignment): void
    {
        abort_if($assignment->course_id !== $course->id, 404);

        $isEnrolled = auth()->user()
            ->enrolledCourses()
            ->where('courses.id', $course->id)
            ->exists();

        abort_unless($isEnrolled, 403, 'Daftar ke kelas ini untuk mengakses tugas.');
    }
}