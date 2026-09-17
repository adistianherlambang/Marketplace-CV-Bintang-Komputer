<div align="center">

# 🌟 Sistem Informasi Penjualan & E-Commerce
# CV Bintang Jaya Komputer

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://docker.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

**Sistem Informasi Penjualan Komputer, Kasir POS Back-Office, dan Marketplace Online Terintegrasi.**

📍 Jl. Ahmad Yani No.68, Iringmulyo, Kota Metro, Lampung &nbsp;|&nbsp; 🗓️ Berdiri 4 September 2025

</div>

---

## 📋 1. Overview

**CV Bintang Jaya Komputer** adalah platform penjualan berbasis web yang menggabungkan konsep **e-commerce publik untuk pelanggan** dengan **sistem manajemen toko (POS & Back-Office)** dalam satu aplikasi Laravel yang terintegrasi penuh.

Sistem ini dirancang untuk memenuhi kebutuhan operasional toko komputer modern:
1. **Sisi Pelanggan (Customer / Publik)**: Menjelajahi katalog produk, memesan laptop/gadget secara online dengan kurir GrabExpress khusus area Kota Metro, melacak status pengiriman secara real-time, mengunduh nota PDF, hingga mengajukan komplain retur barang rusak secara digital.
2. **Sisi Internal (Admin Toko)**: Kasir Point of Sale (POS) untuk transaksi fisik toko, manajemen master produk dan multi-gambar, manajemen stok otomatis (audit trail), verifikasi pembayaran & pengiriman pesanan online, penanganan komplain & retur (auto-restock), serta cetak laporan PDF harian, bulanan, tahunan.

| Aspek | Detail |
|---|---|
| **Pemilik Usaha** | Bapak Krisna Irawan, S.Kom. |
| **Tipe Sistem** | Web-based Sales Information System & E-Commerce |
| **Pengguna Sistem** | Administrator (Internal Toko) & Pelanggan / Guest (Publik) |
| **Arsitektur** | Monolithic MVC + Service Layer Pattern |
| **Deployment** | Docker Compose (Nginx + PHP-FPM + MySQL + phpMyAdmin) & Local PHP Server |

---

## 🚀 2. Fitur Utama & Keunggulan Sistem

### 🛡️ Fitur Admin (Back-Office & POS)

| Modul | Fitur & Deskripsi |
|---|---|
| **Dashboard Analitik** | Statistik pendapatan real-time, tren penjualan bulanan, grafik produk terlaris, indikator stok kritis & habis. |
| **Kasir POS Toko** | Input transaksi langsung di toko via database stok ATAU barang manual, kalkulator kembalian uang tunai/transfer, cetak Struk Kasir & Invoice PDF. |
| **Kelola Pesanan Online** | Manajemen pesanan masuk dari checkout website: verifikasi bukti transfer, rincian titik maps penerima, update status pesanan (*Menunggu Konfirmasi* ➔ *Diproses* ➔ *Dalam Pengiriman* ➔ *Selesai* / *Batal*). |
| **Manajemen Produk** | CRUD produk lengkap dengan SKU unik, barcode, brand, kategori, harga modal, harga jual, stok minimum, spesifikasi teknis, serta multi-gambar produk. |
| **Manajemen Stok Otomatis** | Penambahan/pengurangan stok terintegrasi, audit trail riwayat pergerakan stok (`in`, `out`, `edit`, `delete`, `return`). |
| **Komplain Pelanggan** | Manajemen komplain online dari customer, peninjauan bukti fisik kerusakan & nota, serta pembaruan status komplain. |
| **Manajemen Retur** | Pencatatan retur unit, persetujuan/penolakan retur, otomatis mengembalikan stok (*auto-restock*) ke inventaris saat retur disetujui. |
| **Laporan & Ekspor PDF** | Laporan berkala (Harian, Bulanan, Tahunan), Laporan Stok Opname, Laporan Retur, dan Produk Terlaris lengkap dengan fitur **Live Preview** dan unduh PDF siap cetak. |
| **Booking Produk** | Kelola pemesanan/booking unit oleh calon pembeli sebelum transaksi resmi. |
| **Master Supplier & Pelanggan** | Manajemen data pemasok barang dan basis data pelanggan tetap (dengan perlindungan *Soft Delete*). |

---

### 🛒 Fitur Pelanggan & Publik (Marketplace E-Commerce)

| Modul | Fitur & Deskripsi |
|---|---|
| **Katalog Produk Publik** | Tampilan katalog modern dengan filter kategori, brand/merek, filter harga, serta pencarian produk interaktif. |
| **Detail & Spesifikasi** | Galeri foto produk, spesifikasi teknis lengkap, ketersediaan stok, dan tombol beli/booking. |
| **Autentikasi Satu Pintu** | Login terpusat (Admin & Customer), serta formulir registrasi mandiri untuk pelanggan baru (`/customer/register`). |
| **Checkout E-Commerce** | Pemesanan produk online dengan integrasi zonasi wilayah pengiriman se-Kota Metro (Metro Pusat, Metro Timur, Metro Barat, Metro Utara, Metro Selatan) dengan perhitungan ongkir GrabExpress otomatis. |
| **Titik Pengiriman & Bukti Bayar** | Input koordinat/link Google Maps (Shareloc), pilihan metode pembayaran (Transfer Bank / Tunai), dan unggah file bukti transfer langsung saat checkout. |
| **Riwayat & Pelacakan Pesanan** | Halaman khusus pelanggan (`/riwayat-pesanan`) dengan metrik status interaktif, pemantauan kurir GrabExpress, unduh Nota PDF digital, dan tautan bantuan WhatsApp toko. |
| **Konfirmasi Penerimaan** | Pelanggan dapat mengonfirmasi barang telah sampai dan diterima dengan baik dengan satu klik. |
| **Pengajuan Komplain Online** | Formulir klaim komplain kerusakan barang langsung dari riwayat pesanan dengan unggahan foto bukti kerusakan dan nota pembelian. |

---

### ⚙️ Keunggulan Teknis & Arsitektur

- ✅ **Pengurangan Stok Otomatis**: Stok berkurang seketika saat order kasir POS atau pesanan online diverifikasi.
- ✅ **Auto-Restock Cerdas**: Pengembalian kuantitas stok otomatis ke katalog ketika pesanan dibatalkan atau retur disetujui admin.
- ✅ **Audit Trail Lengkap (`StockService`)**: Setiap mutasi stok tercatat lengkap dengan identitas user, tanggal, jenis perubahan, dan deskripsi.
- ✅ **Zonasi Tarif GrabExpress Kota Metro**: Master tarif ongkos kirim tersimpan per kelurahan di Kota Metro untuk kalkulasi akurat.
- ✅ **Generasi Dokumen PDF**: Menggunakan `barryvdh/laravel-dompdf` untuk mencetak Nota Kasir, Invoice Resmi, dan Laporan Rekapitulasi.
- ✅ **Keamanan Data & Soft Deletes**: Menggunakan proteksi CSRF, enkripsi password via Bcrypt/Argon2, serta *soft delete* untuk data pelanggan dan produk.

---

## 📁 3. Struktur Direktori Blueprint

```
bintang-jaya-komputer/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── BookingController.php          # Kelola booking produk
│   │   │   │   ├── BrandController.php            # CRUD merek
│   │   │   │   ├── CategoryController.php         # CRUD kategori
│   │   │   │   ├── ComplaintController.php        # Kelola komplain toko
│   │   │   │   ├── CustomerController.php         # CRUD pelanggan
│   │   │   │   ├── DashboardController.php        # Statistik & analitik
│   │   │   │   ├── KelolaPesananController.php    # Manajemen order online
│   │   │   │   ├── ProductController.php          # CRUD produk & foto
│   │   │   │   ├── ReportController.php           # Laporan & PDF export
│   │   │   │   ├── ReturnController.php           # Kelola retur barang
│   │   │   │   ├── StockController.php            # Penyesuaian stok & audit
│   │   │   │   ├── SupplierController.php         # CRUD supplier
│   │   │   │   └── TransactionController.php      # Kasir POS & Nota/Invoice
│   │   │   ├── CheckoutController.php             # Form checkout online & ongkir
│   │   │   ├── CustomerAuthController.php         # Auth universal & register
│   │   │   ├── CustomerComplaintController.php    # Form komplain customer
│   │   │   ├── CustomerOrderController.php        # Riwayat pesanan customer
│   │   │   ├── GuestActionController.php          # Aksi booking & kontak
│   │   │   └── GuestCatalogController.php         # Katalog & detail publik
│   │   └── Requests/                              # Form Request Validations
│   ├── Models/                                    # Eloquent Models (14+ model)
│   └── Services/                                  # Business Logic (StockService, OrderService)
├── database/
│   ├── migrations/                                # 14+ migrasi skema tabel
│   └── seeders/                                   # Data master & demo seeder
├── docker/
│   ├── Dockerfile                                 # PHP 8.2-FPM Alpine container
│   └── nginx.conf                                 # Konfigurasi Nginx reverse proxy
├── docker-compose.yml                             # Orkestrasi 4 container
├── public/                                        # Assets, CSS, JS, logo
├── resources/
│   └── views/                                     # Blade templates
│       ├── admin/                                 # Antarmuka panel admin
│       ├── catalog/                               # Halaman katalog e-commerce
│       ├── customers/                             # Halaman order & komplain pelanggan
│       ├── pdf/                                   # Template cetak Nota, Invoice, Laporan
│       └── layouts/                               # Layout utama aplikasi
└── routes/
    └── web.php                                    # Definisi seluruh rute sistem
```

---

## 🗄️ 4. Arsitektur Database (Skema Relasi)

Sistem menggunakan database relasional MySQL dengan skema relasi foreign key yang terstruktur:

```
┌─────────────┐     ┌──────────────────┐     ┌──────────────┐
│    users    │     │    categories    │     │    brands    │
│─────────────│     │──────────────────│     │──────────────│
│ id (PK)     │     │ id (PK)          │     │ id (PK)      │
│ name        │     │ name             │     │ name         │
│ email       │     │ slug (UNIQUE)    │     │ slug (UNIQUE)│
│ password    │     └────────┬─────────┘     └──────┬───────┘
└──────┬──────┘              │                      │
       │               ┌─────▼──────────────────────▼──────┐
       │               │             products               │
       │               │────────────────────────────────────│
       │               │ id (PK)                            │
       │               │ name, sku, barcode                 │
       │               │ category_id (FK → categories)      │
       │               │ brand_id (FK → brands)             │
       │               │ supplier_id (FK → suppliers)       │
       │               │ price_modal, price_jual            │
       │               │ stock, min_stock                   │
       │               │ description, specs                 │
       │               │ is_active, soft_deletes            │
       │               └─────────────┬──────────────────────┘
       │                             │
┌──────▼─────────────────────────────▼──────────────────────┐
│                           orders                          │
│───────────────────────────────────────────────────────────│
│ id (PK)                                                   │
│ invoice_number (UNIQUE: INV/YYYYMMDD/XXXX)                │
│ user_id (FK → users, Kasir Admin - Nullable)              │
│ customer_user_id (FK → users, Akun Pelanggan - Nullable)  │
│ customer_id (FK → customers, Master Pelanggan - Nullable) │
│ kecamatan_id (FK → kecamatans)                            │
│ kelurahan_id (FK → kelurahans)                            │
│ shipping_cost (Tarif ongkir GrabExpress)                  │
│ payment_method (Transfer / Cash)                          │
│ customer_name, customer_phone, customer_address           │
│ shareloc_link (Tautan titik Google Maps)                  │
│ bukti_transfer (File struk transfer bank)                 │
│ total_amount, status, notes, timestamps                   │
└──────┬─────────────────────────────┬──────────────────────┘
       │                             │
┌──────▼──────────────┐       ┌──────▼──────────────┐
│     order_items     │       │       payments      │
│─────────────────────│       │─────────────────────│
│ id (PK)             │       │ id (PK)             │
│ order_id (FK)       │       │ order_id (FK)       │
│ product_id (FK)     │       │ payment_method      │
│ item_name           │       │ amount_paid         │
│ price, quantity     │       │ payment_status      │
│ subtotal            │       │ payment_date        │
└─────────────────────┘       └─────────────────────┘
```

---

## 🔄 5. Alur Kerja Utama Sistem (System Flow)

Sistem CV Bintang Jaya Komputer mengintegrasikan alur operasional toko secara menyeluruh antara pelanggan publik dan staf internal admin:

### 🛒 Alur 1 — Belanja & Checkout Online oleh Pelanggan (E-Commerce)

```
[Pengunjung Buka Website]
           │
           ▼
[Jelajah Katalog & Pilih Produk]
           │
           ▼
[Klik "Beli Sekarang" / Checkout] ──► [Belum Login?] ──► [Halaman Login / Register Akun]
           │                                                       │
           ▼                                                       ▼
[Halaman Form Checkout (/checkout)] ◄──────────────────────────────┘
           │
           ├── 1. Tinjau Ringkasan Produk & Harga
           ├── 2. Masukkan Nama Penerima & No. WhatsApp
           ├── 3. Pilih Wilayah Pengiriman:
           │        ├── Pilih Kecamatan (Kota Metro)
           │        └── Pilih Kelurahan ──► (Sistem kalkulasi Ongkir GrabExpress otomatis)
           ├── 4. Masukkan Link Google Maps / Titik Lokasi
           ├── 5. Pilih Metode Pembayaran:
           │        ├── Transfer Bank ──► Unggah file Bukti Transfer
           │        └── Tunai (Cash on Delivery)
           │
           ▼
[Klik "Konfirmasi & Buat Pesanan"]
           │
           ▼
[Sistem Memproses Transaksi]:
  ├── Generate Nomor Invoice Unik (INV/YYYYMMDD/XXXX)
  ├── Simpan data pengiriman & file bukti transfer ke storage
  ├── Simpan rincian order_items
  └── Status awal pesanan: "Menunggu Konfirmasi"
           │
           ▼
[Redirect ke Halaman Riwayat Pesanan (/riwayat-pesanan)]
```

---

### 📦 Alur 2 — Pemrosesan & Pengiriman Pesanan oleh Admin (`/admin/pesanan`)

```
[Admin Login ke Panel Admin]
           │
           ▼
[Buka Menu: Kelola Pesanan (/admin/pesanan)]
           │
           ├── Tab: Pesanan Masuk (Menunggu Konfirmasi)
           │     │
           │     ├── Admin memeriksa rincian pesanan, alamat, & link Maps
           │     ├── Admin memeriksa foto bukti transfer pembayaran
           │     │
           │     ├──► Jika Valid:
           │     │      ├── Klik "Proses Pesanan" (Status: Diproses Toko)
           │     │      ├── Kurangi stok produk dari inventaris
           │     │      └── Catat log mutasi stok (type: out)
           │     │
           │     └──► Jika Tidak Valid / Dibatalkan:
           │            └── Klik "Batalkan Pesanan" (Status: Dibatalkan)
           │
           ├── Tab: Siap Kirim / Diproses
           │     │
           │     └── Staf menyiapkan & packing barang
           │     └── Menyerahkan paket ke kurir GrabExpress
           │     └── Klik "Kirim Pesanan" (Status: Dalam Pengiriman)
           │
           └── Tab: Pengiriman & Selesai
                 │
                 └── Paket diantarkan kurir ke lokasi customer
                 └── Saat paket diterima: Status berubah menjadi "Selesai"
```

---

### 🛵 Alur 3 — Pelacakan & Konfirmasi Pesanan oleh Pelanggan (`/riwayat-pesanan`)

```
[Pelanggan Buka Halaman: Riwayat Pesanan (/riwayat-pesanan)]
           │
           ├── 📊 Pantau Kartu Metrik:
           │     (Semua Pesanan, Menunggu, Dalam Pengiriman, Selesai)
           │
           ├── 🔍 Filter status pesanan atau cari nomor invoice
           │
           ├── 📄 Unduh Nota Digital:
           │     └── Klik tombol "Download Nota" untuk mengunduh Nota Resmi (PDF)
           │
           ├── 🛵 Pantau Status Pengiriman Kurir Toko secara Real-time
           │
           ├── 💬 Bantuan Cepat:
           │     └── Klik tombol "Bantuan CS" untuk chat WhatsApp admin otomatis
           │
           └── ✅ Konfirmasi Penerimaan:
                 └── Saat kurir tiba membawa barang:
                 └── Pelanggan klik tombol: "Pesanan Diterima"
                 └── Status pesanan berubah menjadi: "Selesai" (Lunas)
```

---

### ⚠️ Alur 4 — Pengajuan & Penanganan Komplain Kerusakan Barang

```
[Barang Diterima Pelanggan Mengalami Kendala/Rusak]
           │
           ▼
[Pelanggan Buka /riwayat-pesanan]
           │
           ▼
[Klik Tombol: "Ajukan Komplain" pada Pesanan Terkait]
           │
           ▼
[Formulir Pengajuan Komplain (/riwayat-pesanan/{id}/komplain)]:
  ├── Rincian invoice dan produk terisi otomatis
  ├── Pilih Jenis Kendala (Barang Cacat, Aksesori Kurang, Mati Total, dll)
  ├── Tulis Deskripsi Detail Kerusakan
  ├── Unggah Foto Nota Pembelian Fisik
  └── Unggah Foto/Bukti Fisik Kerusakan Produk
           │
           ▼
[Klik "Kirim Laporan Komplain"] ──► Tersimpan dengan status "Menunggu"
           │
           ▼
[Admin Meninjau di Menu: Komplain Toko (/admin/complaints)]:
  ├── Admin memeriksa keterangan dan foto bukti
  ├── Menghubungi customer via telepon/WA
  └── Memperbarui status komplain: "Diproses" ➔ "Selesai" / "Ditolak"
```

---

### 🏢 Alur 5 — Transaksi Langsung di Toko (Kasir POS Admin)

```
[Pelanggan Datang ke Toko (Walk-in Customer)]
           │
           ▼
[Admin Buka Menu: Kasir Transaksi (/admin/transactions/create)]
           │
           ├── Metode A: Pilih produk dari inventaris (stok & harga otomatis)
           └── Metode B: Input item manual (jasa servis / barang khusus)
           │
           ▼
[Pilih Data Pelanggan Terdaftar atau Pelanggan Umum (Walk-in)]
           │
           ▼
[Masukkan Pembayaran (Tunai / Transfer) & Hitung Kembalian]
           │
           ▼
[Simpan Transaksi]:
  ├── Generate Nomor Invoice (INV/YYYYMMDD/XXXX)
  ├── Potong stok produk dari database otomatis
  ├── Catat mutasi stok (type: out)
  └── Cetak Struk Nota Kasir PDF atau Faktur Invoice PDF
```

---

### 🔄 Alur 6 — Proses Retur Barang & Auto-Restock Inventaris

```
[Pelanggan Mengajukan Retur Unit ke Toko]
           │
           ▼
[Admin Input Data Retur (/admin/returns)]:
  ├── Pilih Nomor Invoice & Produk yang diretur
  ├── Masukkan Jumlah Unit & Alasan Retur
  └── Status awal: "Menunggu"
           │
           ▼
[Review & Keputusan Admin]:
  │
  ├──► [Setujui Retur]:
  │        ├── Status retur berubah menjadi: "Disetujui"
  │        ├── Stok produk otomatis bertambah kembali (+Qty)
  │        └── Sistem mencatat riwayat stok baru (type: return)
  │
  └──► [Tolak Retur]:
           └── Status retur berubah menjadi: "Ditolak" (stok tidak berubah)
```

---

### 📑 Alur 7 — Pembuatan Laporan & Ekspor Dokumen PDF

```
[Admin Buka Menu: Laporan Penjualan (/admin/reports)]
           │
           ├── 1. Pilih Tipe Laporan:
           │        ├── Laporan Harian
           │        ├── Laporan Bulanan (Rekapitulasi Omset & Profit)
           │        ├── Laporan Tahunan
           │        ├── Laporan Stok Opname
           │        ├── Laporan Retur Barang
           │        └── Laporan Produk Terlaris (Top Selling)
           │
           ├── 2. Pilih Periode Tanggal / Bulan / Tahun
           │
           ├── 3. Klik "Preview Laporan":
           │        └── Pratinjau data interaktif langsung di layar web
           │
           └── 4. Klik "Download PDF":
                    └── Sistem men-generate dokumen resmi berkop toko via DomPDF
```

---

## ✅ 6. Validasi & Logika Teknis Khusus

### Manajemen Stok Terpusat (`StockService`)
Setiap mutasi stok produk **wajib** melewati `StockService` untuk menjamin konsistensi data:
1. Mengubah nilai kolom `stock` pada tabel `products`.
2. Menulis baris riwayat baru pada tabel `stock_histories` dengan parameter:
   - `product_id`: ID produk terkait.
   - `type`: Jenis mutasi (`in`, `out`, `edit`, `delete`, `return`).
   - `quantity`: Jumlah unit yang bertambah/berkurang.
   - `user_id`: ID admin yang mengotorisasi perubahan.
   - `date`: Timestamp waktu transaksi.
   - `description`: Catatan sumber perubahan (misal: nomor invoice).

### Penomoran Invoice Otomatis
Nomor invoice di-generate secara unik dan berurutan dengan format baku:
```
INV / YYYYMMDD / XXXX
Contoh: INV/20260917/0001
```

---

## 📦 7. Dependensi Project

### Backend (PHP / Laravel)
- `laravel/framework`: ^12.x
- `barryvdh/laravel-dompdf`: ^3.1 (Pembuatan dokumen PDF)
- `intervention/image`: ^3.x (Manipulasi & kompresi foto produk)
- `laravel/breeze`: Autentikasi dasar

### Frontend
- Native Responsive CSS & Tailwind Utility
- `Bootstrap 5.3` & `FontAwesome 6.4` (Iconography)
- `Chart.js` (Visualisasi grafik analitik dashboard)
- `Alpine.js` (Interaktivitas dialog & dropdown)

---

## 🔧 8. Panduan Instalasi & Menjalankan Aplikasi

Aplikasi dapat dijalankan melalui **2 metode pilihan**:
- **Metode A (Docker Compose)**: Sangat direkomendasikan karena lingkungan server (Nginx, PHP-FPM 8.2, MySQL 8, phpMyAdmin) telah terkonfigurasi otomatis dan terisolasi.
- **Metode B (Tanpa Docker / Manual Lokal)**: Menggunakan PHP built-in server (`php artisan serve`) dan MySQL lokal (XAMPP / Homebrew / MariaDB).

---

### 🐳 Metode A — Menggunakan Docker Compose (Direkomendasikan)

#### Prasyarat:
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) telah terpasang dan berjalan di komputer Anda.

#### Langkah-langkah:

**1. Masuk ke direktori project:**
```bash
cd /Users/aaaa/Documents/Desain/Client/bayu/code
```

**2. Siapkan file environment (`.env`):**
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Pastikan konfigurasi database pada `.env` diarahkan ke container `db`:
```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=bintang_jaya_db
DB_USERNAME=bintang_jaya_user
DB_PASSWORD=bintang_jaya_pass
```

**3. Bangun dan jalankan seluruh container:**
```bash
docker compose up -d --build
```
> Perintah ini menyalakan 4 container: `bintang_jaya_app` (PHP-FPM), `bintang_jaya_web` (Nginx), `bintang_jaya_db` (MySQL 8.0), dan `bintang_jaya_phpmyadmin`.

**4. Install dependensi composer di dalam container:**
```bash
docker compose exec app composer install
```

**5. Generate App Key Laravel:**
```bash
docker compose exec app php artisan key:generate
```

**6. Jalankan Migrasi Database dan Seeder Data Awal:**
```bash
docker compose exec app php artisan migrate --seed
```
> Perintah ini membuat seluruh tabel, master wilayah se-Kota Metro dengan tarif GrabExpress, akun admin, akun pelanggan demo, data produk, serta riwayat transaksi.

**7. Buat Symbolic Link Storage (Penting untuk foto produk & bukti transfer):**
```bash
docker compose exec app php artisan storage:link
```

**8. Build asset frontend:**
```bash
docker compose exec app npm install
docker compose exec app npm run build
```

**9. Atur hak akses direktori storage:**
```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

#### 🌐 URL Akses Docker:

| Layanan | URL Akses | Keterangan |
|---|---|---|
| **Aplikasi Web E-Commerce & POS** | **http://localhost:8083** | Halaman utama, katalog, checkout, & admin panel |
| **phpMyAdmin Database Visual** | **http://localhost:8082** | Manajemen database visual (User: `root`, Pass: `root_secret_pass`) |

#### Cheat Sheet Perintah Docker:
```bash
# Melihat status container yang aktif
docker compose ps

# Melihat log aplikasi secara langsung
docker compose logs -f app

# Menghentikan container tanpa menghapus data database
docker compose stop

# Menjalankan kembali container
docker compose start

# Mematikan dan menghapus container
docker compose down

# Masuk ke terminal bash container app
docker compose exec app sh
```

---

### 💻 Metode B — Tanpa Docker (Manual Lokal / XAMPP / PHP Native)

#### Prasyarat:
- **PHP**: Versi 8.2 atau lebih baru (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`, `curl`)
- **Composer**: Versi 2.x
- **Database**: MySQL 8.0 atau MariaDB (misal via XAMPP)
- **Node.js & npm**: Node.js LTS

#### Langkah-langkah:

**1. Masuk ke direktori project:**
```bash
cd /Users/aaaa/Documents/Desain/Client/bayu/code
```

**2. Install dependensi PHP:**
```bash
composer install
```

**3. Siapkan file `.env`:**
```bash
cp .env.example .env
```
Sesuaikan konfigurasi database dengan server MySQL lokal Anda (misal XAMPP):
```env
APP_NAME="CV Bintang Jaya Komputer"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bintang_jaya_db
DB_USERNAME=root
DB_PASSWORD=
```

**4. Buat Database di MySQL lokal:**
Buka MySQL CLI atau phpMyAdmin lokal Anda, lalu buat database baru:
```sql
CREATE DATABASE bintang_jaya_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**5. Generate App Key Laravel:**
```bash
php artisan key:generate
```

**6. Jalankan Migrasi & Database Seeder:**
```bash
php artisan migrate --seed
```

**7. Buat Symbolic Link Storage:**
```bash
php artisan storage:link
```

**8. Install dependensi JavaScript & Build Asset:**
```bash
npm install
npm run build
```

**9. Jalankan Server Pengembangan:**
```bash
php artisan serve --port=8000
```

#### 🌐 URL Akses Non-Docker:
Akses website melalui browser: **http://localhost:8000**

---

## 🔑 9. Kredensial Akun & Informasi Login

Setelah perintah `php artisan migrate --seed` dijalankan, sistem menyediakan akun siap pakai untuk pengujian:

### 🛡️ 1. Akun Administrator (Akses Penuh Toko & POS)

| Atribut | Kredensial |
|---|---|
| **Halaman Login** | `http://localhost:8083/login` (Docker) atau `http://localhost:8000/login` (Lokal) |
| **Email** | `admin@bintangkomputer.com` |
| **Password** | `password` |
| **Nama Pengguna** | Krisna Irawan |
| **Hak Akses** | Akses penuh: Dashboard analitik, Kasir POS Transaksi, Kelola Pesanan Online, Master Produk, Stok, Retur, Komplain, Laporan PDF. |

---

### 🛒 2. Akun Pelanggan Demo (Customer)

| Atribut | Kredensial |
|---|---|
| **Halaman Login** | `http://localhost:8083/login` (Docker) atau `http://localhost:8000/login` (Lokal) |
| **Email** | `user@bintangkomputer.com` |
| **Password** | `password` |
| **Nama Pengguna** | Pelanggan Demo |
| **Hak Akses** | Akses belanja: Jelajah katalog, Checkout dengan kurir GrabExpress, Pelacakan status di `/riwayat-pesanan`, Download Nota PDF, Konfirmasi barang sampai, Ajukan komplain kerusakan. |

---

### 📝 3. Registrasi Pelanggan Baru

Pengunjung baru juga dapat mendaftarkan akun pribadi secara mandiri melalui menu registrasi:
- URL Registrasi: `http://localhost:8083/customer/register` (Docker) atau `http://localhost:8000/customer/register` (Lokal)
- Cukup mengisi Nama Lengkap, Alamat Email, Nomor Telepon/WhatsApp, Alamat Domisili, dan Kata Sandi.

---

### 📊 Ringkasan Data Demo Hasil Seeder

| Entitas Data | Jumlah / Keterangan |
|---|---|
| **Akun Pengguna** | 1 Admin (`admin@bintangkomputer.com`), 1 Customer (`user@bintangkomputer.com`) |
| **Master Wilayah** | 5 Kecamatan & seluruh Kelurahan di Kota Metro lengkap dengan tarif zonasi kurir GrabExpress |
| **Kategori Produk** | 5 Kategori (Laptops, Smartphones, Headphones, Accessories, Smart Home) |
| **Merek / Brand** | 6 Brand Resmi (Apple, Asus, Samsung, Sony, Logitech, Google) |
| **Supplier** | 2 Supplier Resmi (PT. Bintang Distribusi Nusantara, CV. Global Gadget Lampung) |
| **Katalog Produk** | 10+ Produk komputer, laptop, dan aksesori lengkap spesifikasi & gambar |
| **Riwayat Transaksi** | ~40 Transaksi penjualan selama 5 bulan terakhir (untuk simulasi grafik omset & rekapitulasi) |
| **Sample Retur** | 2 Kasus Retur (1 Menunggu Review, 1 Disetujui dengan auto-restock) |
| **Sample Komplain** | 1 Kasus Komplain Pelanggan |

---

## 🛠️ 10. Ringkasan Tech Stack

| Komponen | Spesifikasi & Teknologi |
|---|---|
| **Bahasa Pemrograman** | PHP 8.2+ |
| **Framework Backend** | Laravel 12.x |
| **Arsitektur** | Model-View-Controller (MVC) + Service Layer Pattern |
| **Basis Data** | MySQL 8.0 (InnoDB, Foreign Keys, Soft Deletes) |
| **Autentikasi** | Laravel Breeze & Custom Universal Auth Guard |
| **Template Engine** | Blade Templating |
| **Styling & UI** | Native CSS, Bootstrap 5.3, FontAwesome 6.4, Chart.js |
| **Pembuatan PDF** | `barryvdh/laravel-dompdf` (Nota Kasir, Invoice, Laporan Berkala) |
| **Web Server (Docker)**| Nginx Alpine Reverse Proxy |
| **Orkestrasi Container**| Docker & Docker Compose |
| **Standar Kode** | PSR-12 (Laravel Pint) |

---

<div align="center">

**© 2025 CV Bintang Jaya Komputer** — Krisna Irawan, S.Kom.

📍 Jl. Ahmad Yani No.68, Iringmulyo, Kota Metro, Lampung

*Sistem Informasi Penjualan & Manajemen Toko Komputer Terintegrasi*

</div>
