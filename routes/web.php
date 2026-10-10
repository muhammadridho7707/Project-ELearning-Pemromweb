<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Guru\AssignmentController as GuruAssignmentController;
use App\Http\Controllers\Guru\CourseController as GuruCourseController;
use App\Http\Controllers\Guru\MaterialController as GuruMaterialController;
use App\Http\Controllers\Guru\SubmissionController as GuruSubmissionController;
use App\Http\Controllers\Siswa\AssignmentController as SiswaAssignmentController;
use App\Http\Controllers\Siswa\CourseController as SiswaCourseController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\MaterialController as SiswaMaterialController;
use Illuminate\Support\Facades\Auth;  
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// ----- Hanya untuk tamu (belum login) -----
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'choose'])->name('register');
    Route::get('/register/{role}', [RegisterController::class, 'showForm'])
        ->whereIn('role', ['guru', 'siswa'])
        ->name('register.form');
    Route::post('/register/{role}', [RegisterController::class, 'store'])
        ->whereIn('role', ['guru', 'siswa'])
        ->middleware('throttle:5,1')
        ->name('register.store');
});

// ----- Hanya untuk yang sudah login -----
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return Auth::user()->isGuru()
            ? redirect()->route('guru.dashboard')
            : redirect()->route('siswa.dashboard');
    })->name('dashboard');
    // Profil (guru dan siswa)
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profil/foto', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/profil/foto', [ProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // ===== Area siswa =====
    Route::middleware('role:siswa')->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');

        Route::get('/kelas', [SiswaCourseController::class, 'index'])->name('courses.index');
        Route::get('/kelas/{course}', [SiswaCourseController::class, 'show'])->name('courses.show');
        Route::post('/kelas/{course}/daftar', [SiswaCourseController::class, 'enroll'])->name('courses.enroll');
        Route::delete('/kelas/{course}/keluar', [SiswaCourseController::class, 'unenroll'])->name('courses.unenroll');

        Route::get('/kelas/{course}/materi/{material}/unduh', [SiswaMaterialController::class, 'download'])
            ->name('materials.download');

        // Tugas
        Route::get('/kelas/{course}/tugas/{assignment}', [SiswaAssignmentController::class, 'show'])
            ->name('assignments.show');
        Route::post('/kelas/{course}/tugas/{assignment}/kumpul', [SiswaAssignmentController::class, 'submit'])
            ->name('assignments.submit');
        Route::get('/kelas/{course}/tugas/{assignment}/unduh', [SiswaAssignmentController::class, 'download'])
            ->name('assignments.download');
    });

    // ===== Area guru =====
    Route::middleware('role:guru')->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', function () {
            $courses = auth()->user()
                ->taughtCourses()
                ->withCount('students')
                ->latest()
                ->get();

            return view('guru.dashboard', compact('courses'));
        })->name('dashboard');

        Route::resource('kelas', GuruCourseController::class)
            ->parameters(['kelas' => 'course'])
            ->names('courses');

        // Materi
        Route::post('/kelas/{course}/materi', [GuruMaterialController::class, 'store'])
            ->name('materials.store');
        Route::get('/kelas/{course}/materi/{material}/unduh', [GuruMaterialController::class, 'download'])
            ->name('materials.download');
        Route::delete('/kelas/{course}/materi/{material}', [GuruMaterialController::class, 'destroy'])
            ->name('materials.destroy');

        // Tugas
        Route::post('/kelas/{course}/tugas', [GuruAssignmentController::class, 'store'])
            ->name('assignments.store');
        Route::get('/kelas/{course}/tugas/{assignment}', [GuruAssignmentController::class, 'show'])
            ->name('assignments.show');
        Route::get('/kelas/{course}/tugas/{assignment}/edit', [GuruAssignmentController::class, 'edit'])
            ->name('assignments.edit');
        Route::put('/kelas/{course}/tugas/{assignment}', [GuruAssignmentController::class, 'update'])
            ->name('assignments.update');
        Route::delete('/kelas/{course}/tugas/{assignment}', [GuruAssignmentController::class, 'destroy'])
            ->name('assignments.destroy');

        // Pengumpulan: unduh dan beri nilai
        Route::get('/kelas/{course}/tugas/{assignment}/pengumpulan/{submission}/unduh',
            [GuruSubmissionController::class, 'download'])->name('submissions.download');
        Route::put('/kelas/{course}/tugas/{assignment}/pengumpulan/{submission}/nilai',
            [GuruSubmissionController::class, 'grade'])->name('submissions.grade');
    });
});