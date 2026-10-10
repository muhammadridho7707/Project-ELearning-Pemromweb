<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $courses = Course::with('teacher')
            ->withCount('students')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('judul', 'like', "%{$q}%")
                      ->orWhere('deskripsi', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $enrolledIds = auth()->user()->enrolledCourses()->pluck('courses.id')->all();

        return view('siswa.courses.index', compact('courses', 'enrolledIds', 'q'));
    }

    public function show(Course $course)
{
    $course->load('teacher')->loadCount('students');

    $isEnrolled = auth()->user()
        ->enrolledCourses()
        ->where('courses.id', $course->id)
        ->exists();

    $materials = $isEnrolled
        ? $course->materials()->latest()->get()
        : collect();

    // Tugas beserta pengumpulan milik siswa ini saja
    $assignments = $isEnrolled
        ? $course->assignments()
            ->with(['submissions' => fn ($q) => $q->where('student_id', auth()->id())])
            ->orderBy('deadline')
            ->get()
        : collect();

    return view('siswa.courses.show', compact('course', 'isEnrolled', 'materials', 'assignments'));
}
    public function enroll(Course $course)
    {
        auth()->user()->enrolledCourses()->syncWithoutDetaching([$course->id]);

        return redirect()->route('siswa.courses.show', $course)
            ->with('success', 'Berhasil mendaftar ke kelas "' . $course->judul . '".');
    }

    public function unenroll(Course $course)
    {
        auth()->user()->enrolledCourses()->detach($course->id);

        return redirect()->route('siswa.courses.show', $course)
            ->with('success', 'Kamu sudah keluar dari kelas "' . $course->judul . '".');
    }
}