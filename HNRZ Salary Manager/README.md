# HNRZ Salary Manager

A Laravel-based employee and payroll management application for managing positions, payroll methods, and employee self-service salary information.

## Fitur Utama

- Manajemen karyawan untuk admin
- Manajemen jabatan/posisi dengan gaji tetap per posisi
- Manajemen metode penggajian
- Karyawan dapat melihat jabatan dan gaji mereka secara mandiri
- Karyawan dapat memilih satu metode penggajian favorit
- Gaji selalu diambil dari jabatan yang ditugaskan, bukan disimpan duplikasi di tabel karyawan

## Peran Akses

### Admin
- Mengelola karyawan
- Mengelola jabatan
- Mengelola metode penggajian

### Karyawan
- Melihat jabatan sendiri
- Melihat gaji yang berasal dari jabatan
- Melihat daftar metode penggajian
- Memilih satu metode penggajian

## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Pengujian

```bash
php artisan test
```

## Kredensial Default

Setelah menjalankan seeder, akun admin tersedia dengan:

- Email: admin@example.com
- Password: password

## Catatan Arsitektur

- Gaji tidak disimpan secara manual di tabel karyawan.
- Gaji diambil secara dinamis dari relasi jabatan/posisi.
- Jika gaji jabatan berubah, semua karyawan yang memakai jabatan tersebut akan otomatis melihat perubahan tersebut.
