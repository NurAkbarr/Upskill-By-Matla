# UPSKILL by MATLA

Platform Kursus & Pelatihan Online yang dibangun menggunakan **CodeIgniter 4** dan **Tailwind CSS**.

## Fitur Utama
- **Landing Page Interaktif**: Slider program kelas modern dengan ambient glow efek, FAQ akordeon, testimoni, dan counter statistik.
- **Katalog Kursus**: Tampilan daftar kelas beserta detail materi dan form pendaftaran.
- **Autentikasi & Otorisasi**: Login, Register, dan role-based access (Super Admin, Admin, Member) menggunakan filter auth.
- **Panel Admin**:
  - Manajemen Kursus (CRUD + Upload Banner Gambar).
  - Manajemen Pengguna (CRUD Tambah, Edit, Hapus, dan proteksi akun diri sendiri).

## Persyaratan Sistem
- PHP >= 8.1 (dengan ekstensi `intl`, `mbstring`, `mysqli`)
- MySQL / MariaDB
- Composer

## Cara Instalasi Lokal
1. Clone repository ini:
   ```bash
   git clone <URL_REPOSITORY>
   cd Upskill
   ```
2. Salin file environment:
   ```bash
   cp .env.example .env
   ```
3. Sesuaikan konfigurasi database di file `.env`.
4. Import database menggunakan file `schema.sql`.
5. Jalankan server lokal:
   ```bash
   php spark serve
   ```
   Akses aplikasi di browser: `http://localhost:8080`
