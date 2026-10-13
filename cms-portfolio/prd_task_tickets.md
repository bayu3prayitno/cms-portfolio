# Product Requirements Document (PRD) - CMS Headless Portofolio

Dokumen ini berisi daftar tiket tugas (Task Tickets) untuk pengembangan sistem CMS *headless* menggunakan Laravel, yang menyediakan konten multibahasa via API untuk *landing page* portofolio.

## Epic 1: Inisialisasi Proyek & Konfigurasi Dasar

### TKT-101: Setup Proyek Laravel & Database
*   **Prioritas:** Tinggi
*   **Deskripsi:** Menginisialisasi proyek Laravel baru untuk CMS *headless* dan mengatur koneksi database (MySQL/PostgreSQL).
*   **Kriteria Penerimaan:**
    *   Proyek Laravel berhasil diinstal.
    *   File `.env` sudah terkonfigurasi dengan kredensial database yang benar.
    *   *Migration* bawaan Laravel berhasil dijalankan.

### TKT-102: Instalasi Filament PHP (Panel Admin)
*   **Prioritas:** Tinggi
*   **Deskripsi:** Menginstal dan mengonfigurasi Filament PHP sebagai antarmuka CMS.
*   **Kriteria Penerimaan:**
    *   Package `filament/filament` berhasil diinstal.
    *   Panel admin dapat diakses di rute `/admin`.
    *   Akun admin pertama (Super Admin) berhasil dibuat.

### TKT-103: Setup Multi-bahasa (Spatie Translatable)
*   **Prioritas:** Tinggi
*   **Deskripsi:** Menginstal `spatie/laravel-translatable` dan plugin terjemahan untuk Filament untuk mendukung konten Bahasa Indonesia & Inggris.
*   **Kriteria Penerimaan:**
    *   Package `spatie/laravel-translatable` dan `filament/spatie-laravel-translatable-plugin` terinstal.
    *   Konfigurasi *locale* ID dan EN tersedia di sistem.

---

## Epic 2: Pengembangan Modul Profil (CMS & API)

### TKT-201: CRUD Data Profil di CMS
*   **Prioritas:** Sedang
*   **Deskripsi:** Membuat *migration*, *model*, dan *Resource* Filament untuk mengelola data profil (Nama, Gelar, Tentang, Statistik, Foto). Kolom Gelar dan Tentang harus mendukung multibahasa.
*   **Kriteria Penerimaan:**
    *   Tabel `profiles` memiliki kolom JSON untuk terjemahan.
    *   Admin dapat mengunggah foto profil, mengisi data IPK, proyek, dan teknologi.
    *   Formulir Filament memiliki *switcher* bahasa (ID/EN).

### TKT-202: API Endpoint GET /api/profile
*   **Prioritas:** Sedang
*   **Deskripsi:** Membuat *controller* API untuk mengirimkan data profil ke *landing page*.
*   **Kriteria Penerimaan:**
    *   Endpoint merespons dengan JSON berisi data profil.
    *   Merespons dalam bahasa yang sesuai berdasarkan *header* `Accept-Language`.

---

## Epic 3: Pengembangan Modul Skills / Tech Stack

### TKT-301: CRUD Data Skills di CMS
*   **Prioritas:** Sedang
*   **Deskripsi:** Membuat sistem manajemen untuk daftar teknologi (React, Laravel, Node.js, dll).
*   **Kriteria Penerimaan:**
    *   Tabel `skills` dibuat dengan kolom nama dan logo/ikon.
    *   Admin dapat menambah, mengedit, menghapus teknologi, dan mengunggah ikon.

### TKT-302: API Endpoint GET /api/skills
*   **Prioritas:** Sedang
*   **Deskripsi:** Membuat endpoint API untuk mengambil daftar *tech stack*.
*   **Kriteria Penerimaan:**
    *   Endpoint mengembalikan *array* JSON berisi daftar nama dan URL ikon *skills*.

---

## Epic 4: Pengembangan Modul Proyek

### TKT-401: CRUD Data Proyek di CMS
*   **Prioritas:** Tinggi
*   **Deskripsi:** Membuat manajemen portofolio proyek. Judul dan Kategori harus mendukung multibahasa.
*   **Kriteria Penerimaan:**
    *   Tabel `projects` memiliki kolom JSON untuk judul dan kategori, string untuk *tech stack*, dan string untuk gambar.
    *   Admin dapat mengunggah *thumbnail* proyek melalui panel Filament.

### TKT-402: API Endpoint GET /api/projects
*   **Prioritas:** Tinggi
*   **Deskripsi:** Membuat API untuk mengambil daftar portofolio.
*   **Kriteria Penerimaan:**
    *   Endpoint mengembalikan *array* JSON proyek.
    *   Data yang dikembalikan sudah diterjemahkan sesuai bahasa yang diminta API.

---

## Epic 5: Pengembangan Modul Kegiatan & Pengalaman

### TKT-501: CRUD Data Pengalaman di CMS
*   **Prioritas:** Sedang
*   **Deskripsi:** Membuat fitur pengelolaan pengalaman (Volunteer, Pelatihan, Organisasi, Publikasi).
*   **Kriteria Penerimaan:**
    *   Tabel `experiences` dibuat dengan fitur translatable pada kolom judul dan deskripsi.
    *   Admin dapat mengkategorikan pengalaman, mengatur tahun, dan mengunggah logo instansi/kegiatan.

### TKT-502: API Endpoint GET /api/experiences
*   **Prioritas:** Sedang
*   **Deskripsi:** Membuat API untuk mendistribusikan data pengalaman.
*   **Kriteria Penerimaan:**
    *   Endpoint mendukung *query parameter* untuk *filtering* berdasarkan kategori (misal: `?category=volunteer`).
    *   Data JSON yang dikembalikan mengikuti bahasa yang direquest.

---

## Epic 6: Penyelesaian API & Keamanan

### TKT-601: Implementasi Middleware Deteksi Bahasa
*   **Prioritas:** Tinggi
*   **Deskripsi:** Membuat *middleware* khusus di rute `/api/*` untuk menangkap bahasa pengguna.
*   **Kriteria Penerimaan:**
    *   Middleware membaca *header* HTTP `Accept-Language` atau parameter `?lang=`.
    *   Sistem mengatur konfigurasi `app()->setLocale()` secara dinamis untuk merender *output* JSON multibahasa dengan benar.

### TKT-602: Setup CORS & API Response Formatting
*   **Prioritas:** Sedang
*   **Deskripsi:** Mengonfigurasi keamanan akses *frontend* ke *backend* API.
*   **Kriteria Penerimaan:**
    *   CORS dikonfigurasi agar domain *landing page* diizinkan mengakses API.
    *   Semua *response* API menggunakan struktur JSON yang konsisten (misal: `success`, `message`, `data`).