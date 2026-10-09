<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Kelas yang diambil siswa (yang terbaru didaftar di atas)
        $myCourses = $user->enrolledCourses()
            ->with('teacher')
            ->orderByDesc('enrollments.created_at')
            ->get();

        // Cuplikan daftar kelas yang belum diambil
        $availableCourses = Course::with('teacher')
            ->withCount('students')
            ->whereNotIn('id', $myCourses->pluck('id'))
            ->latest()
            ->take(4)
            ->get();

        return view('siswa.dashboard', compact('myCourses', 'availableCourses'));
    }
}