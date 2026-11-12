# Sistem Peminjaman Alat Laboratorium

Aplikasi web inventaris dan peminjaman alat laboratorium sekolah berbasis Laravel 12. Dibuat untuk memenuhi kebutuhan Uji Sertifikasi Kompetensi (USK) jurusan Rekayasa Perangkat Lunak (RPL).

## Fitur Utama

### 1. Panel Siswa
- Registrasi dan login akun siswa (menggunakan NISN atau email).
- Pencarian dan filter katalog alat berdasarkan kategori (Biologi, Kimia, Elektronika, Fisika) dan status ketersediaan stok.
- Formulir pengajuan peminjaman alat dengan validasi tanggal (tanggal kembali tidak boleh sebelum tanggal pinjam) dan validasi batas stok.
- Riwayat peminjaman siswa untuk memantau status persetujuan secara real-time (Menunggu, Disetujui, Ditolak, Selesai).
- Fitur pelaporan barang rusak jika terjadi kendala pada alat yang sedang dipinjam (dilengkapi deskripsi kerusakan dan upload foto bukti).

### 2. Panel Admin (Laboran)
- Dashboard ringkasan: statistik total alat, total unit stok, peminjaman aktif, pengajuan yang butuh verifikasi, dan jumlah siswa terdaftar.
- Persetujuan peminjaman: aksi setujui atau tolak pengajuan siswa. Saat disetujui, stok alat otomatis berkurang.
- Konfirmasi pengembalian: saat alat selesai digunakan, admin mengonfirmasi pengembalian dan stok alat otomatis bertambah kembali.
- Manajemen data alat (CRUD): tambah, ubah, dan hapus data alat (nama, kategori, stok, kondisi, lokasi penyimpanan, dan foto).
- Manajemen kategori (CRUD): tambah, ubah, dan hapus kategori instrumen laboratorium.
- Daftar siswa: memantau seluruh data siswa yang memiliki akses peminjaman di sistem.

### 3. Keamanan & Validasi Sistem
- Enkripsi password menggunakan hashing Bcrypt (Hash::make).
- Validasi stok tidak boleh minus di level database (unsigned integer) dan logic controller.
- Validasi tanggal: tanggal pengembalian wajib sama atau setelah tanggal mulai pinjam.
- Role-based middleware untuk membatasi hak akses antara akun siswa dan laboran.

## Kebutuhan Sistem
- PHP >= 8.2
- Composer
- MySQL / MariaDB (Laragon / XAMPP)
- Web Browser modern

## Cara Instalasi dan Menjalankan

1. Clone repositori ini:
   ```bash
   git clone https://github.com/Fadhil-byte/PeminjamanLaboratorium_PelatihanUSK.git
   cd PeminjamanLaboratorium_PelatihanUSK
   ```

2. Install dependensi composer:
   ```bash
   composer install
   ```

3. Salin file konfigurasi environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Sesuaikan konfigurasi database pada file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=PelatihanUSK
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Jalankan migrasi database beserta data awal (seeder):
   ```bash
   php artisan migrate:fresh --seed
   ```

6. Hubungkan symbolic link storage untuk upload foto alat dan laporan:
   ```bash
   php artisan storage:link
   ```

7. Jalankan server lokal:
   ```bash
   php artisan serve
   ```
   Buka aplikasi melalui browser di `http://127.0.0.1:8000`.

## Akun Pengujian (Seeder)

Untuk mencoba fungsionalitas sistem, bisa menggunakan akun bawaan berikut:

### Akun Admin / Laboran
- Email: `admin@lab.sch.id`
- Password: `admin123`

### Akun Siswa
- Siswa 1: NISN `2104018` (atau email `ahmad@smk.sch.id`) / Password: `password`
- Siswa 2: NISN `2104033` (atau email `siti@smk.sch.id`) / Password: `password`
