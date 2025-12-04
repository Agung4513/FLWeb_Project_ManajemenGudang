# 📦 Gudang Jaya  
Sistem Manajemen Gudang Terintegrasi (WMS)

[![Laravel](https://img.shields.io/badge/Laravel-11-red?style=for-the-badge&logo=laravel)](https://laravel.com/)  
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-orange?style=for-the-badge&logo=php)](https://www.php.net/)  
[![MySQL](https://img.shields.io/badge/MySQL-8.0-blue?style=for-the-badge&logo=mysql)](https://www.mysql.com/)  
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com/)

---

## Tentang Aplikasi
Gudang Jaya adalah aplikasi web **Warehouse Management System (WMS)** modern yang dirancang untuk menangani kompleksitas operasional gudang. Aplikasi ini memisahkan peran secara tegas antara administrasi, operasional fisik, dan manajemen stok — dengan alur kerja digital yang terstruktur dan aman.

Sistem ini menerapkan:
- **Validasi berlapis (Maker-Checker Principle)**
- **Integrasi dengan Supplier**
- **Audit fisik untuk semua barang masuk**

---

## Fitur Unggulan

- **Sistem Multi-Role (RBAC)**  
  Setiap peran memiliki akses dan fitur yang berbeda.

- **Manajemen Transaksi (Maker-Checker)**  
  Semua transaksi stok harus dibuat oleh staff dan disetujui manager sebelum perubahan terjadi.

- **Siklus Restock & Audit Fisik**  
  PO → Konfirmasi Supplier → Barang Tiba → Input Fisik → Approval Manager.

- **Keamanan Tingkat Lanjut**  
  Middleware user aktif, validasi stok, dan proteksi transaksi.

---

## Role & Hak Akses

| Role       | Hak & Tanggung Jawab |
|------------|----------------------|
| **Admin**   | Kelola user, aktifkan supplier, akses laporan penuh. |
| **Manager** | Membuat PO, menyetujui transaksi, memantau stok. |
| **Staff**   | Input transaksi masuk/keluar berdasarkan fisik. |
| **Supplier**| Melihat & mengkonfirmasi PO. |

---

## Alur Kerja Sistem

### A. Barang Masuk (Restock)
1. Manager → Buat **PO** → status: `Pending`
2. Supplier → Login → klik **“Terima Pesanan”** → status: `Confirmed`
3. Manager → Set → **“Received”** saat barang tiba
4. Staff → Input **Transaksi Masuk**
5. Manager → **Approve** → Stok bertambah

### B. Barang Keluar (Pengeluaran)
1. Staff → Input **Transaksi Keluar** → status: `Pending`
2. Manager → Review → **Approve** → Stok berkurang

---

## Persyaratan Sistem

- PHP ≥ 8.1  
- Composer  
- Node.js & NPM  
- MySQL / MariaDB  
- Web Server / Laravel built-in server  

---

## Cara Instalasi (Local Development)

```bash
# Clone repository
git clone https://github.com/username/gudang-jaya.git
cd gudang-jaya

# Install dependencies
composer install
npm install && npm run build

# Konfigurasi environment
cp .env.example .env
# Edit .env sesuai konfigurasi database Anda

# Generate key & link storage
php artisan key:generate
php artisan storage:link

# Migrasi database + seeding
php artisan migrate:fresh --seed

# Jalankan server
php artisan serve

#Akses aplikasi via:

http://127.0.0.1:8000
```

## Akun Demo
Role |  Email	             |  Password    |
|---------|-------------------|--------------|
| Admin	| admin@gudang.com | adminpass |
| Manager	| manager1@gudang.com | manager1pass| 
| Staff	| staff1@email.com | staff1pass |
| Supplier	| supplier1@gudang.com | supplier1pass |

User baru hasil register akan berstatus Non-Aktif dan harus diaktifkan oleh Admin terlebih dahulu.


## Struktur Folder
```bash
├── app/
│   ├── Http/
│   ├── Models/
│   └── ...
├── database/
│   ├── migrations/
│   ├── seeders/
├── resources/
│   ├── views/
│   ├── js/
│   └── css/
├── routes/
│   ├── web.php
│   └── api.php
└── public/
```
```
Panduan Penggunaan

Admin → aktifkan supplier, kelola user.

Manager → buat PO, approve transaksi.

Staff → input transaksi masuk/keluar.

Supplier → konfirmasi PO.
