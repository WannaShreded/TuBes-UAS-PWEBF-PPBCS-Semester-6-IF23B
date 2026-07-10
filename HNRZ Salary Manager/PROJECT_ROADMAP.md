# Project Roadmap

## Status Legend
- ✅ Sudah selesai
- 🟡 Sedang dikerjakan
- 🔴 Belum dibuat

## Overview
Dokumen ini memuat peta pengembangan proyek HNRZ Salary Manager berdasarkan area fitur utama.

## Roadmap Per Area

### Frontend
- ✅ Layout dasar aplikasi
- ✅ Navigasi admin dan karyawan
- ✅ CRUD halaman admin
- 🟡 Standarisasi UI/UX antarmuka
- 🔴 Dashboard interaktif dengan komponen modern

### Backend
- ✅ Struktur MVC Laravel
- ✅ Controller CRUD untuk utama
- ✅ Validasi request
- 🟡 Optimasi query dan performa
- 🔴 Refactor modular untuk logika bisnis yang lebih besar

### Database
- ✅ Migrasi utama selesai
- ✅ Relasi dasar employee, jabatan, bonus, payroll method
- 🟡 Optimasi schema dan indeks data
- 🔴 Backup strategy dan data retention policy

### API
- 🔴 API untuk integrasi eksternal
- 🔴 REST/JSON resource untuk data payroll
- 🔴 Dokumentasi API

### Authentication
- ✅ Login dan registrasi dasar
- ✅ Breeze authentication
- 🟡 Pengaturan session dan keamanan lanjutan
- 🔴 OAuth / SSO integration

### Authorization
- ✅ Spatie Permission terpasang
- ✅ Role dan permission dasar
- 🟡 Pengaturan akses yang lebih granular
- 🔴 Audit log untuk perubahan hak akses

### Payroll
- ✅ Pengaturan metode penggajian
- ✅ Relasi karyawan ke payroll method
- 🟡 Proses perhitungan gaji lebih detail
- 🔴 History pembayaran dan slip gaji

### Bonus
- ✅ CRUD bonus
- ✅ Penetapan bonus ke karyawan
- 🟡 Rule bonus berbasis periode dan tipe
- 🔴 Pengaturan otomatis bonus bulanan

### Dashboard
- ✅ Dashboard dasar pengguna
- 🟡 Statistik ringkas admin
- 🔴 Grafik payroll, bonus, dan status karyawan

### Report
- 🔴 Laporan gaji bulanan
- 🔴 Laporan bonus
- 🔴 Export PDF/Excel

### Employee
- ✅ CRUD karyawan
- ✅ Informasi jabatan dan gaji
- 🟡 Status karyawan aktif/nonaktif
- 🔴 Riwayat pekerjaan dan mutasi

### Role
- ✅ CRUD role
- ✅ Assign permission ke role
- 🟡 Pengaturan role yang lebih spesifik
- 🔴 Role template / preset role

### Permission
- ✅ Permission dasar tersedia
- 🟡 Pengelolaan permission yang lebih terstruktur
- 🔴 Permission audit dan monitoring

### Testing
- 🔴 Unit test untuk model dan controller
- 🔴 Feature test untuk CRUD utama
- 🔴 Test untuk authorization dan payroll logic

### Deployment
- 🔴 Environment configuration production
- 🔴 CI/CD pipeline
- 🔴 Docker / container deployment
- 🔴 Monitoring dan logging production
