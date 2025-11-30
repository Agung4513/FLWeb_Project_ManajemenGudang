<div align="center">

📦 GUDANG JAYA

Sistem Manajemen Gudang Terintegrasi (WMS)

</div>

📖 Tentang Aplikasi

Gudang Jaya adalah aplikasi web Warehouse Management System (WMS) modern yang dirancang untuk menangani kompleksitas operasional gudang. Aplikasi ini memisahkan peran secara tegas antara administrasi, operasional fisik, dan manajemen stok dengan alur kerja digital yang terstruktur.

💡 Fokus Utama: Keamanan data dengan validasi berlapis (Maker-Checker Principle) dan integrasi langsung dengan supplier.

🚀 Fitur Unggulan

1. Sistem Multi-Role (RBAC)

Akses fitur dibatasi secara ketat berdasarkan peran pengguna untuk menjaga integritas data:

Role

Tanggung Jawab & Hak Akses

👑 Admin

Super-user. Mengelola user, menyetujui pendaftaran supplier, dan akses laporan penuh.

👔 Manager

Pengawas. Menyetujui transaksi staff, membuat PO (Restock), dan memantau stok.

👷 Staff

Garda depan. Input fisik barang masuk/keluar. Tidak bisa mengubah stok tanpa persetujuan.

🚚 Supplier

Eksternal. Login khusus untuk melihat dan mengkonfirmasi pesanan (PO) dari gudang.

2. Manajemen Transaksi (Maker-Checker)

Mencegah kecurangan dan kesalahan input stok dengan alur kerja bertingkat:

✅ Input: Staff membuat transaksi ➔ Status Pending.

✅ Verifikasi: Manager menerima notifikasi real-time.

✅ Approval: Manager menyetujui transaksi ➔ Stok Database Berubah.

3. Siklus Restock & Audit Fisik

Alur pengadaan barang yang realistis (tidak otomatis):

Manager buat PO ➔ Supplier Konfirmasi ➔ Barang Dikirim.

Saat barang tiba, sistem TIDAK otomatis menambah stok.

Staff wajib melakukan Input Transaksi Masuk berdasarkan fisik barang yang diterima (Audit) untuk menjaga akurasi data.

4. Keamanan Tingkat Lanjut

🛡️ Middleware Satpam: Mencegah user yang statusnya "Belum Aktif" (misal Supplier baru daftar) untuk login meskipun password benar.

🔒 Validasi Stok: Mencegah transaksi keluar jika stok di sistem tidak mencukupi.

⚙️ Persyaratan Sistem

Sebelum memulai, pastikan komputer Anda memiliki:

PHP >= 8.1

Composer

Node.js & NPM

MySQL Database

🛠️ Cara Instalasi

Ikuti langkah-langkah ini untuk menjalankan proyek di komputer lokal:

1. Clone Repositori

git clone [https://github.com/username/gudang-jaya.git](https://github.com/username/gudang-jaya.git)
cd gudang-jaya


2. Install Dependencies

composer install
npm install && npm run build


3. Konfigurasi Environment
Salin file contoh dan atur koneksi database Anda.

cp .env.example .env


Buka file .env dan sesuaikan:

DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=
APP_URL=[http://127.0.0.1:8000](http://127.0.0.1:8000)


4. Generate Key & Link Storage

php artisan key:generate
php artisan storage:link


5. Migrasi & Seeding (PENTING)
Perintah ini akan membuat tabel dan akun dummy untuk pengujian.

php artisan migrate:fresh --seed


6. Jalankan Server

php artisan serve


Akses aplikasi di: http://127.0.0.1:8000

🔑 Akun Pengujian (Demo)

Gunakan akun berikut untuk login dan menguji setiap fitur:

Role

Email

Password

Admin

admin@gudang.com

password

Manager

manager@gudang.com

password

Staff

staff@gudang.com

password

Supplier

supplier@maju.com

password

⚠️ Catatan Penting:
Jika Anda mencoba fitur Register, akun baru akan berstatus Non-Aktif (Supplier Pending). Anda harus login sebagai Admin terlebih dahulu untuk menyetujuinya di menu "Kelola Pengguna".

🔄 Alur Kerja Sistem

A. Barang Masuk (Restock)

Manager: Buat Restock Order (PO) ➔ Status: Pending.

Supplier: Login & Klik "Terima Pesanan" ➔ Status: Confirmed.

Manager: Ubah status jadi Received saat truk tiba.

Staff: Dapat notifikasi di dashboard ➔ Input "Transaksi Masuk" sesuai jumlah fisik.

Manager: Approve transaksi Staff ➔ Stok Bertambah.

B. Barang Keluar (Penjualan)

Staff: Input "Transaksi Keluar" ➔ Status: Pending.

Manager: Cek data & Approve ➔ Stok Berkurang.

<div align="center">
<p>Dikembangkan untuk Tugas Individual Project 7</p>
<p><strong>Manajemen Gudang 2025</strong></p>
</div>
