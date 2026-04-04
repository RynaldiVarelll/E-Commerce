## Deskripsi Proyek: E-Commerce
Proyek ini adalah aplikasi E-Commerce sederhana yang dibangun menggunakan Laravel. Aplikasi ini memiliki fitur manajemen produk, kategori, keranjang belanja, transaksi, dan chat antara pembeli dan penjual (seller).

### Struktur Utama Kode
- **`app/Http/Controllers`**: Berisi logika utama aplikasi.
  - `CartController.php`: Mengelola keranjang belanja pengguna.
  - `TransactionController.php`: Menangani proses checkout dan laporan transaksi.
  - `Admin/ProductController.php`: Tempat Seller/Admin mengelola produk mereka.
- **`app/Models`**: Representasi tabel database.
  - `Product.php`: Model untuk data produk (harga, stok, gambar, dll).
  - `Transaction.php`: Model untuk menyimpan riwayat pesanan.
- **`routes/web.php`**: Definisi rute URL untuk publik, user, dan admin.
- **`resources/views`**: Tampilan (UI) aplikasi menggunakan Blade templates.

---

## Cara Install buat pengguna laragon/xampp (Tanpa Docker/Sail)

1. Clone repository: `git clone <url-repo>`
2. Install dependencies: `composer install` & `npm install`
3. Copy .env: `cp .env.example .env`
4. Generate key: `php artisan key:generate`
5. Sesuaikan konfigurasi DB di `.env` (DB_HOST=127.0.0.1)
6. Jalankan migrasi: `php artisan migrate --seed`
7. Hubungkan storage: `php artisan storage:link`
8. Jalankan server: `php artisan serve` & `npm run dev`

---
