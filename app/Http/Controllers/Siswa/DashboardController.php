<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $myCourses = auth()->user()
            ->enrolledCourses()
            ->with('teacher')
            ->orderByDesc('enrollments.created_at')
            ->get();

        return view('siswa.dashboard', compact('myCourses'));
    }
}