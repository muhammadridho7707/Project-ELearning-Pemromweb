<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $this->authorizeOwner($course);

        $data = $request->validate([
            'judul'     => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'file'      => ['required', 'file', 'max:10240',
                            'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,txt,jpg,jpeg,png'],
        ], [
            'judul.required' => 'Judul materi wajib diisi.',
            'file.required'  => 'Pilih file materi terlebih dahulu.',
            'file.max'       => 'Ukuran file maksimal 10 MB.',
            'file.mimes'     => 'Format file harus: pdf, doc, docx, ppt, pptx, xls, xlsx, zip, txt, jpg, atau png.',
            'file.uploaded'  => 'Upload gagal. Ukuran file melebihi batas server (cek upload_max_filesize di php.ini).',
        ]);

        // Disimpan di storage/app/private/materials/{id_kelas}/ dengan nama acak
        $path = $request->file('file')->store("materials/{$course->id}");

        $course->materials()->create([
            'judul'     => $data['judul'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'file_path' => $path,
        ]);

        return redirect()->route('guru.courses.show', $course)
            ->with('success', 'Materi berhasil diunggah.');
    }

    public function download(Course $course, Material $material)
    {
        $this->authorizeOwner($course);
        $this->ensureBelongs($course, $material);

        abort_unless(Storage::exists($material->file_path), 404, 'File tidak ditemukan.');

        return Storage::download($material->file_path, $material->downloadName());
    }

    public function destroy(Course $course, Material $material)
    {
        $this->authorizeOwner($course);
        $this->ensureBelongs($course, $material);

        Storage::delete($material->file_path); // hapus file fisik
        $material->delete();                   // hapus data di database

        return redirect()->route('guru.courses.show', $course)
            ->with('success', 'Materi berhasil dihapus.');
    }

    private function authorizeOwner(Course $course): void
    {
        abort_if($course->teacher_id !== auth()->id(), 403, 'Ini bukan kelas Anda.');
    }

    // Pastikan materi memang milik kelas di URL
    private function ensureBelongs(Course $course, Material $material): void
    {
        abort_if($material->course_id !== $course->id, 404);
    }
}