# DigiLib - Sistem Informasi Perpustakaan Digital

Aplikasi ini adalah solusi manajemen perpustakaan sekolah berbasis web yang modern, cepat, dan aman. Dikembangkan sebagai proyek **UKK RPL 2025/2026**.

## ✨ Fitur Utama
- **Role-Based Access Control (RBAC)**: Pemisahan hak akses antara Admin dan Siswa.
- **Manajemen Buku**: CRUD lengkap data buku dengan kategori dan pelacakan stok.
- **Sistem Peminjaman**: Alur peminjaman buku yang valid (cek stok, cek duplikasi pinjam).
- **Pengembalian Otomatis**: Kalkulasi denda otomatis jika pengembalian terlambat.
- **Desain Modern**: Antarmuka berbasis Glassmorphism dengan animasi halus dan responsif.
- **Dashboard Statistik**: Visualisasi data ringkas untuk admin dan siswa.

## 🛠️ Teknologi yang Digunakan
- **Frontend**: HTML5, Vanilla CSS3 (Custom Design System), JavaScript.
- **Backend**: PHP 8.x Native (PDO for secure DB access).
- **Database**: MySQL / MariaDB.
- **Fonts & Icons**: Google Fonts (Outfit), Font Awesome 6.

## 🚀 Cara Instalasi
1. Pastikan Anda telah menginstal **XAMPP** atau server lokal lainnya.
2. Salin folder `APB-shin` ke dalam direktori `htdocs` Anda.
3. Buka **phpMyAdmin**, buat database baru bernama `db_perpustakaan`.
4. Import file `database.sql` ke dalam database tersebut.
5. Akses aplikasi melalui browser di `http://localhost/APB-shin`.

## 🔑 Akun Default
| Role | Username | Password |
|------|----------|----------|
| **Admin** | admin | password |
| **Siswa** | shinta | password |

## 📁 Struktur Folder
- `admin/`: Halaman khusus administrator.
- `assets/`: File CSS, JS, dan gambar.
- `auth/`: Logika login, registrasi, dan logout.
- `config/`: Koneksi database dan fungsi bantuan.
- `includes/`: Komponen UI yang dapat digunakan kembali (Header, Footer).
- `user/`: Halaman khusus siswa.

---
Dikembangkan oleh **Antigravity** untuk **UKK RPL 2025/2026**.
