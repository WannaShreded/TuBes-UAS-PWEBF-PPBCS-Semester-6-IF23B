# HNRZ Salary Manager 💼💰

**HNRZ Salary Manager** adalah sistem manajemen penggajian (payroll) karyawan berbasis multi-platform. Proyek ini dikembangkan dengan arsitektur modern yang memisahkan antara REST API Backend dan Mobile App Client, dirancang khusus untuk memudahkan administrasi data karyawan, jabatan, gaji, bonus, dan pencatatan riwayat payroll dalam suatu perusahaan.

Sistem ini mendukung akses berbasis peran (*Role-Based Access Control*) untuk **Admin** dan **Karyawan**, di mana karyawan dapat mengelola metode pembayaran gaji secara mandiri serta melihat ringkasan profil mereka, sementara Admin memiliki kontrol penuh atas manajemen data perusahaan.

---

## 🚀 Fitur Utama

### 👑 Modul Administrator (Admin)
- **Dashboard Statistik & Ringkasan**: Menampilkan metrik utama seperti total pengeluaran gaji, jumlah karyawan aktif/nonaktif, serta visualisasi data payroll.
- **Manajemen Karyawan (CRUD)**: Menambah, mengubah status keaktifan (aktif/nonaktif), mengedit informasi detail karyawan, atau menghapusnya.
- **Manajemen Gaji & Jabatan (CRUD)**: Mengatur jabatan beserta nominal gaji pokoknya. Perubahan nominal gaji jabatan secara otomatis akan memperbarui gaji semua karyawan yang memegang jabatan tersebut (dinamis tanpa duplikasi data).
- **Manajemen Bonus Karyawan (CRUD & Relasi)**: Mengelola jenis bonus tambahan yang dapat didistribusikan ke karyawan.
- **Riwayat Payroll (Pencatatan)**: Mencatat dan memproses riwayat pembayaran gaji karyawan, lengkap dengan perhitungan gaji pokok, total bonus, metode pembayaran yang dipilih, dan tanggal proses.

### 👤 Modul Karyawan (Employee Self-Service)
- **Ringkasan Profil & Dashboard**: Melihat informasi data pribadi, jabatan yang diampu, serta nominal gaji pokok yang bersumber dari jabatannya.
- **Pengaturan Mandiri Metode Penggajian**: Memilih dan memperbarui metode pembayaran gaji yang disukai (misalnya transfer bank atau e-wallet) beserta detail nomor rekening/nomor e-wallet.
- **Ubah Password**: Mengubah kata sandi akun secara mandiri langsung dari aplikasi mobile.

---

## 📁 Struktur Folder Proyek

Proyek ini terbagi menjadi dua sub-direktori utama:

```text
PWBF_PPBCS/
├── README.md                      # Dokumentasi utama proyek (file ini)
├── HNRZ Salary Manager/           # Backend REST API dibangun dengan Laravel
│   ├── app/                       # Logika bisnis utama & Controller API
│   ├── database/                  # Migrasi tabel database & Seeders data default
│   ├── routes/api.php             # Definisi endpoint REST API
│   └── .env.example               # Template konfigurasi environment backend
└── hnrz_salary_manager/           # Frontend Mobile Application dibangun dengan Flutter
    ├── lib/                       # Source code Flutter (Dart)
    │   ├── models/                # Pemodelan objek data (Model)
    │   ├── screens/               # Halaman UI (Auth, Dashboard, Employee, dll)
    │   └── services/              # Client API Integration (Http Service)
    └── pubspec.yaml               # Manajemen dependensi aplikasi Flutter
```

---

## 🛠️ Tech Stack

Di bawah ini adalah teknologi utama yang digunakan untuk membangun aplikasi **HNRZ Salary Manager**:

| Komponen | Teknologi | Versi | Deskripsi |
|---|---|---|---|
| **Backend** | ![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel&logoColor=white) | `^13.8` | REST API Provider & Core Business Logic |
| **Language (BE)** | ![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php&logoColor=white) | `^8.3` | Bahasa pemrograman backend server-side |
| **Database** | ![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white) | `8.x` / MariaDB | Penyimpanan data relasional |
| **Auth & Security** | ![Sanctum](https://img.shields.io/badge/Laravel_Sanctum-4.0-red?style=flat-square) | `^4.0` | Autentikasi API berbasis token |
| **RBAC** | [Spatie Laravel Permission](https://github.com/spatie/laravel-permission) | `^8.2` | Manajemen peran & hak akses (Role & Permission) |
| **Frontend** | ![Flutter](https://img.shields.io/badge/Flutter-3.x-02569B?style=flat-square&logo=flutter&logoColor=white) | `SDK ^3.12.0` | Framework pengembangan aplikasi mobile cross-platform |
| **Language (FE)** | ![Dart](https://img.shields.io/badge/Dart-3.x-0175C2?style=flat-square&logo=dart&logoColor=white) | `^3.12.0` | Bahasa pemrograman untuk aplikasi mobile |

---

## 🔑 Role & Hak Akses

Sistem menggunakan kontrol akses berbasis peran (Spatie Laravel Permission) yang dibagi menjadi:

1. **Admin (`admin`)**
   - Akses penuh ke seluruh menu administrasi.
   - Hak CRUD untuk Jabatan, Karyawan, Bonus, dan Metode Pembayaran.
   - Hak untuk melihat statistik keuangan & grafik payroll.
   - Hak untuk memproses pencatatan riwayat payroll baru.
2. **Karyawan (`karyawan`)**
   - Terbatas hanya untuk akses fitur personal.
   - Melihat profil, jabatan, dan gaji pokok sendiri.
   - Melihat daftar metode pembayaran aktif.
   - Mengatur/memperbarui metode pembayaran terpilih sendiri (Bank Transfer / E-Wallet).
   - Mengubah kata sandi akun sendiri.

---

## 🔌 API Overview

Semua request REST API menggunakan format JSON dan harus menyertakan header `Authorization: Bearer <token>` setelah login sukses (kecuali endpoint `/api/login`).

### Endpoint Autentikasi
- `POST /api/login` - Login pengguna (mengembalikan Sanctum Token & Info Role)
- `POST /api/logout` - Logout pengguna (revokasi token aktif) [Auth Required]
- `GET /api/user` - Mengambil info profil pengguna yang sedang login [Auth Required]

### Endpoint Khusus Karyawan (Self-Service)
- `GET /api/profile` - Mengambil data lengkap profil karyawan yang login [Auth Required]
- `PUT /api/profile/password` - Mengupdate password karyawan [Auth Required]
- `GET /api/employee/payroll-methods` - Mengambil daftar metode pembayaran yang tersedia [Auth Required]
- `PUT /api/employee/payroll-method` - Mengupdate metode pembayaran pilihan karyawan [Auth Required]

### Endpoint Khusus Admin (Management)
- `GET /api/statistics` - Mengambil statistik ringkasan payroll & dashboard [Admin Only]
- `GET /api/payroll-histories` - Melihat riwayat transaksi payroll karyawan [Admin Only]
- `POST /api/payroll-histories` - Mencatat riwayat payroll baru (proses bayar) [Admin Only]
- `DELETE /api/payroll-histories/{id}` - Menghapus catatan riwayat payroll [Admin Only]

### Resource Endpoints (CRUD)
- `/api/jabatan` - Mengelola data jabatan & nominal gaji pokok [Auth Required]
- `/api/bonus` - Mengelola jenis-jenis bonus [Auth Required]
- `/api/payroll-methods` - Mengelola data daftar opsi metode pembayaran [Auth Required]
- `/api/employee` - Mengelola data master karyawan [Auth Required]

---

## ⚙️ Cara Instalasi & Setup

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek di lingkungan lokal Anda.

### 🛡️ 1. Setup Backend Laravel
Pastikan Anda sudah menginstal **PHP >= 8.3**, **Composer**, dan server database **MySQL**.

1. Masuk ke direktori backend:
   ```bash
   cd "HNRZ Salary Manager"
   ```
2. Instal semua dependensi PHP menggunakan Composer:
   ```bash
   composer install
   ```
3. Salin file konfigurasi environment:
   ```bash
   cp .env.example .env
   ```
4. Buka file `.env` dan konfigurasikan koneksi database Anda (buat database kosong bernama `hnrz` terlebih dahulu pada MySQL Anda):
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hnrz
   DB_USERNAME=root
   DB_PASSWORD=
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Jalankan migrasi tabel beserta pengisian data awal (seeder):
   ```bash
   php artisan migrate --seed
   ```
7. Jalankan server lokal Laravel:
   ```bash
   php artisan serve
   ```
   *Secara default, backend akan berjalan di `http://127.0.0.1:8000`.*

#### 🔑 Akun Default hasil Seeding:
- **Admin**:
  - Email: `admin@example.com`
  - Password: `password`
- **Karyawan**:
  - Akun karyawan digenerate otomatis sebanyak 10 data oleh `EmployeeSeeder`. Silakan cek tabel `users` / `employees` untuk detail email login (password default adalah `password`).

---

### 📱 2. Setup Frontend Flutter
Pastikan Anda telah menginstal **Flutter SDK** (versi Dart SDK kompatibel `^3.12.0`) dan emulator (Android/iOS) atau perangkat fisik yang terhubung.

1. Masuk ke direktori frontend:
   ```bash
   cd ../hnrz_salary_manager
   ```
2. Dapatkan semua package dependensi Flutter:
   ```bash
   flutter pub get
   ```
3. **Konfigurasi Base URL API**:
   Buka file `lib/services/api_client.dart` dan sesuaikan variabel `baseUrl` dengan alamat IP server backend Anda.
   - Jika menggunakan **Android Emulator**, gunakan alamat IP gateway loopback khusus emulator Android:
     ```dart
     static const String baseUrl = "http://10.0.2.2:8000/api";
     ```
   - Jika menggunakan **iOS Simulator** atau perangkat lokal yang sama dengan server:
     ```dart
     static const String baseUrl = "http://127.0.0.1:8000/api";
     ```
   - Jika menggunakan **Perangkat Fisik (Real Device)**, gunakan alamat IP lokal komputer server Anda (misal: `192.168.xx.xx`):
     ```dart
     static const String baseUrl = "http://192.168.100.14:8000/api";
     ```
4. Jalankan aplikasi Flutter:
   ```bash
   flutter run
   ```

---

## 📸 Screenshot / Demo

*Bagian ini dapat diisi secara manual dengan screenshot tampilan aplikasi.*

| Halaman Login | Dashboard Admin | Dashboard Karyawan |
| :---: | :---: | :---: |
| *[Placeholder Screenshot Login]* | *[Placeholder Screenshot Dashboard Admin]* | *[Placeholder Screenshot Dashboard Karyawan]* |

---

## 👥 Kontributor

Proyek ini dikembangkan oleh kelompok mahasiswa program studi Teknik Informatika / Sistem Informasi untuk memenuhi tugas mata kuliah Pemrograman Web dan Bahasa Framework (PWBF) serta Pemrograman Platform Bergerak (PPB) - Semester 6:

- **[Nama Kontributor 1]** - NIM: `[NIM 1]` - *Job Desk: Backend (Laravel) / Frontend (Flutter)*
- **[Nama Kontributor 2]** - NIM: `[NIM 2]` - *Job Desk: Backend (Laravel) / Frontend (Flutter)*
- **[Nama Kontributor 3]** - NIM: `[NIM 3]` - *Job Desk: UI/UX & Quality Assurance*

---

## 📄 Lisensi

Proyek ini dirancang untuk tujuan edukasi akademik di **Universitas/Fakultas** Anda. Hak cipta dilindungi oleh para kontributor di atas.

---

*Terima kasih telah menggunakan HNRZ Salary Manager!*