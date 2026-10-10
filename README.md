# PjBL E-Learning

Aplikasi web e-learning berbasis **Project-Based Learning (PjBL)** dengan dua peran pengguna, yaitu guru dan siswa. Dibuat untuk memenuhi tugas mata kuliah Pemrograman Web.

- **Nama Kelompok:** **Sandwich Goceng Satu**

- **Nama:** <br> 
    Muhammad Ridho Reyhan <br>
    Nayla Salsabila <br>
    Rachel Aulia <br>
    Nikita Anastasya
- **NIM:** <br>
    4252550013 <br>
    4252550013 <br>
    4252550005 <br>
    4252550035

- **Mata Kuliah:** Pemrograman Web

- **Mata kuliah:** Pemrograman Web
- **Dosen:** Bapak Adidtya Perdana , S.T., M.Kom

---

## Fitur

### Umum
- Registrasi dan login dengan pilihan peran **guru** atau **siswa**
- Autentikasi berbasis **session** (disimpan di database) dan **cookie** (`laravel_session`, `XSRF-TOKEN`, dan `remember_web_*` untuk fitur "Ingat saya")
- Pembatasan akses berdasarkan peran (middleware `role`)
- Pesan sukses dan error (flash message)
- Profil: ubah nama dan bio, unggah atau hapus foto, ganti password
- Mode gelap dan terang (tersimpan di browser)
- Tampilan responsif (Bootstrap 5)

### Guru
- Membuat, mengubah, dan menghapus kelas
- Mengunggah, mengunduh, dan menghapus materi dari file internal
- Membuat, mengubah, dan menghapus tugas beserta deadline
- Melihat daftar siswa di setiap kelas
- Mengunduh file pengumpulan siswa, lalu memberi nilai dan feedback

### Siswa
- Dashboard berisi kelas yang diambil
- Melihat daftar semua kelas (dengan pencarian dan pagination)
- Mendaftar atau keluar dari kelas
- Mengunduh materi dari kelas yang diikuti
- Mengumpulkan tugas, mengumpulkan ulang selama belum dinilai, dan melihat nilai serta feedback

### Keamanan
- Password di-hash (bcrypt)
- Proteksi CSRF pada semua form
- Pendaftaran guru memerlukan **kode undangan**
- Pembatasan percobaan login dan registrasi (5 kali per menit)
- Session di-regenerate setelah login dan di-invalidate saat logout
- File materi dan tugas disimpan di storage privat dan hanya bisa diunduh oleh pihak yang berhak
- Guru hanya dapat mengelola kelas miliknya sendiri
- Validasi tipe dan ukuran file upload

---

## Teknologi

| Komponen | Versi / Keterangan |
|---|---|
| PHP | 8.2 atau lebih baru |
| Laravel | 13 |
| Database | MySQL |
| Frontend | Blade, Bootstrap 5.3, Bootstrap Icons |
| Web server lokal | Laragon (atau XAMPP) |

---

## Persyaratan

- PHP 8.2+ (ekstensi: `pdo_mysql`, `mbstring`, `fileinfo`, `openssl`)
- Composer
- MySQL

---

## Cara Instalasi

Jalankan perintah berikut di terminal dari folder project.

**1. Pasang dependensi**
```bash
composer install
```

**2. Salin file environment**
```bash
copy .env.example .env
```
(Di Linux atau macOS: `cp .env.example .env`)

**3. Atur database di `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pjbl_elearning
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
APP_TIMEZONE=Asia/Jakarta
TEACHER_INVITE_CODE=GURU-2026-XYZ
```

**4. Buat kunci aplikasi**
```bash
php artisan key:generate
```

**5. Buat database kosong** bernama `pjbl_elearning` lewat phpMyAdmin (collation `utf8mb4_unicode_ci`).

**6. Buat tabel**

*Opsi A: lewat migration (disarankan)*
```bash
php artisan migrate
```

*Opsi B: import file SQL*
Import `pjbl_elearning.sql` lewat phpMyAdmin (tab Import).

**7. Hubungkan folder storage** (diperlukan agar foto profil tampil)
```bash
php artisan storage:link
```

**8. Jalankan aplikasi**
```bash
php artisan serve
```

Buka **http://127.0.0.1:8000** di browser.

---

## Cara Penggunaan

### Mendaftar sebagai siswa
1. Buka halaman **Daftar**, lalu pilih **Siswa**.
2. Isi nama, email, dan password, lalu klik **Daftar**.

### Mendaftar sebagai guru
1. Buka halaman **Daftar**, lalu pilih **Guru**.
2. Isi data, lalu masukkan **Kode Undangan Guru**.
3. Kode default ada di file `.env` pada variabel `TEACHER_INVITE_CODE`
   (nilai bawaan: `GURU-2026-XYZ`).

### Skenario pengujian singkat
1. Daftar dan login sebagai **guru**, lalu buat sebuah kelas.
2. Di halaman kelas, unggah materi dan buat tugas dengan deadline.
3. Logout, lalu daftar dan login sebagai **siswa**.
4. Buka **Daftar Kelas**, lalu daftar ke kelas yang tadi dibuat.
5. Unduh materi, lalu kumpulkan file tugas.
6. Login kembali sebagai guru, buka **Detail & Nilai**, lalu beri nilai dan feedback.
7. Login sebagai siswa dan lihat nilai yang diberikan.

---

## Struktur Database

| Tabel | Isi |
|---|---|
| `users` | Akun pengguna (nama, email, password, role, foto, bio) |
| `courses` | Kelas yang dibuat guru |
| `enrollments` | Relasi siswa dengan kelas (many-to-many) |
| `materials` | Materi (file) per kelas |
| `assignments` | Tugas per kelas beserta deadline |
| `submissions` | Pengumpulan tugas siswa, nilai, dan feedback |
| `sessions` | Data session login |

Relasi utama:
- Satu guru memiliki banyak kelas.
- Siswa dan kelas terhubung lewat `enrollments`.
- Satu kelas memiliki banyak materi dan tugas.
- Satu tugas memiliki banyak pengumpulan (satu per siswa).

---

## Struktur Folder Penting

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/           (login, register)
│   │   ├── Guru/           (kelas, materi, tugas, penilaian)
│   │   ├── Siswa/          (dashboard, kelas, materi, tugas)
│   │   └── ProfileController.php
│   └── Middleware/RoleMiddleware.php
└── Models/                 (User, Course, Material, Assignment, Submission)
database/migrations/        (skema database)
resources/views/            (tampilan Blade)
routes/web.php              (daftar route)
storage/app/private/        (file materi dan tugas)
storage/app/public/foto/    (foto profil)
```

---

## Batasan Upload

- Materi dan tugas: maksimal **10 MB**, format pdf, doc, docx, ppt, pptx, xls, xlsx, zip, txt, jpg, jpeg, png
- Foto profil: maksimal **2 MB**, format jpg, jpeg, png, webp

Jika upload gagal pada file yang kecil, naikkan batas PHP di `php.ini`:
```ini
upload_max_filesize = 20M
post_max_size = 25M
```

---

## Pemecahan Masalah

| Masalah | Solusi |
|---|---|
| `No connection could be made` | Nyalakan MySQL (di Laragon klik **Start All**) |
| `Unknown database` | Buat database `pjbl_elearning` dan cocokkan `DB_DATABASE` di `.env` |
| `No application encryption key` | Jalankan `php artisan key:generate` |
| Foto profil tidak tampil | Jalankan `php artisan storage:link` |
| Perubahan tidak muncul | Jalankan `php artisan optimize:clear` |
| Kode undangan guru selalu ditolak | Pastikan `TEACHER_INVITE_CODE` terisi di `.env`, lalu `php artisan config:clear` |
| Halaman 429 | Terlalu banyak percobaan login, tunggu satu menit |

---

## Keterbatasan dan Rencana Pengembangan

- Kode undangan guru bersifat bersama, sehingga bisa bocor. Pengembangan berikutnya: persetujuan akun guru oleh admin.
- Belum ada verifikasi email dan fitur lupa password.
- Belum ada pengumuman kelas, diskusi, kelompok proyek, dan rekap nilai.