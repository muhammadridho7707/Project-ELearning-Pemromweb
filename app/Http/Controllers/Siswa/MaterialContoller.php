<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Material;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function download(Course $course, Material $material)
    {
        abort_if($material->course_id !== $course->id, 404);

        // Hanya siswa yang terdaftar di kelas ini yang boleh mengunduh
        $isEnrolled = auth()->user()
            ->enrolledCourses()
            ->where('courses.id', $course->id)
            ->exists();

        abort_unless($isEnrolled, 403, 'Daftar ke kelas ini untuk mengunduh materi.');
        abort_unless(Storage::exists($material->file_path), 404, 'File tidak ditemukan.');

        return Storage::download($material->file_path, $material->downloadName());
    }
}