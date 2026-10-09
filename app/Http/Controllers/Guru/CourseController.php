<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        $courses = auth()->user()
            ->taughtCourses()
            ->withCount('students')
            ->latest()
            ->get();

        return view('guru.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('guru.courses.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $course = auth()->user()->taughtCourses()->create($data);

        return redirect()->route('guru.courses.show', $course)
            ->with('success', 'Kelas berhasil dibuat.');
    }

    public function show(Course $course)
    {
        $this->authorizeOwner($course);

        $course->load([
            'students'  => fn ($q) => $q->orderBy('name'),
            'materials' => fn ($q) => $q->latest(),
        ]);
        $course->loadCount('assignments');

        return view('guru.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $this->authorizeOwner($course);

        return view('guru.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorizeOwner($course);

        $course->update($this->validated($request));

        return redirect()->route('guru.courses.show', $course)
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Course $course)
    {
        $this->authorizeOwner($course);

        // Hapus semua file materi milik kelas ini dari storage
        Storage::deleteDirectory("materials/{$course->id}");

        $course->delete();

        return redirect()->route('guru.courses.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    private function authorizeOwner(Course $course): void
    {
        abort_if($course->teacher_id !== auth()->id(), 403, 'Ini bukan kelas Anda.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'judul'     => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
        ], [
            'judul.required' => 'Judul kelas wajib diisi.',
            'judul.max'      => 'Judul maksimal 255 karakter.',
            'deskripsi.max'  => 'Deskripsi maksimal 2000 karakter.',
        ]);
    }
}