<div align="center">

# 🌟 Sistem Informasi Penjualan
# CV Bintang Jaya Komputer

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://docker.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

**Sistem Informasi Penjualan berbasis web untuk toko komputer dan aksesori elektronik.**

📍 Jl. Ahmad Yani No.68, Iringmulyo, Kota Metro, Lampung &nbsp;|&nbsp; 🗓️ Berdiri 4 September 2025

</div>

---

## 📋 1. Overview

**CV Bintang Jaya Komputer** adalah platform penjualan berbasis web yang menggabungkan konsep **marketplace publik** dengan **sistem manajemen toko (POS/Back-Office)** dalam satu aplikasi Laravel yang terintegrasi penuh.

Sistem ini dirancang untuk memenuhi kebutuhan operasional harian sebuah toko komputer modern, mulai dari manajemen stok, pencatatan transaksi, cetak invoice/laporan PDF, hingga penanganan retur dan komplain pelanggan — semuanya dapat dilakukan dari antarmuka web yang responsif dan intuitif.

| Aspek | Detail |
|---|---|
| **Pemilik** | Bapak Krisna Irawan, S.Kom. |
| **Tipe Sistem** | Web-based Sales Information System |
| **Target Pengguna** | Admin (internal) & Guest (publik) |
| **Arsitektur** | Monolithic MVC + Service Layer |
| **Deployment** | Docker Compose (Nginx + PHP-FPM + MySQL) |

---

## 🚀 2. Fitur Utama & Keunggulan Sistem

### 🛡️ Fitur Admin

| Modul | Fitur |
|---|---|
| **Dashboard** | Statistik real-time, grafik penjualan bulanan, grafik produk terlaris, kartu KPI |
| **Manajemen Produk** | CRUD produk lengkap dengan foto, SKU, barcode, spesifikasi, multi-gambar |
| **Manajemen Stok** | Tambah/kurangi stok, riwayat perubahan stok terotomasi dengan audit trail |
| **Kategori & Merk** | CRUD kategori dan merek dengan slug unik |
| **Supplier** | Manajemen data supplier dengan informasi kontak |
| **Pelanggan** | CRUD pelanggan, soft delete untuk keamanan data |
| **Transaksi** | Buat transaksi via produk database ATAU item manual, manajemen status |
| **Invoice PDF** | Cetak invoice PDF kapan saja dengan detail lengkap |
| **Laporan PDF** | Laporan harian/bulanan/tahunan, laporan retur, stok, produk terlaris |
| **Retur** | Approve/tolak retur, stok dikembalikan otomatis bila disetujui |
| **Komplain** | Manajemen komplain pelanggan dengan sistem status bertingkat |

### 🌐 Fitur Guest (Marketplace Publik)

| Fitur | Keterangan |
|---|---|
| **Katalog Produk** | Tampil semua produk aktif dengan foto, harga, dan stok |
| **Detail Produk** | Spesifikasi lengkap, deskripsi, status ketersediaan |
| **Pencarian** | Cari berdasarkan nama produk, merek, atau kategori |
| **Filter** | Filter berdasarkan kategori, merek, dan rentang harga |
| **Responsif** | Tampilan optimal di desktop, tablet, dan mobile |

### ⚙️ Keunggulan Teknis

- ✅ **Pengurangan stok otomatis** saat transaksi berhasil dibuat
- ✅ **Pengembalian stok otomatis** saat transaksi dibatalkan atau retur disetujui
- ✅ **Audit trail lengkap** setiap perubahan stok tercatat di riwayat
- ✅ **Soft Delete** untuk data pelanggan dan produk (data aman, tidak terhapus permanen)
- ✅ **Dua metode transaksi**: produk dari database atau item manual
- ✅ **PDF generation** untuk invoice dan laporan menggunakan DomPDF
- ✅ **CSRF Protection** dan validasi form di seluruh input
- ✅ **Eager Loading** untuk performa query yang optimal
- ✅ **Service Layer** memisahkan business logic dari controller

---

## 📁 3. Struktur Direktori Blueprint

```
bintang-jaya-komputer/
│
├── 📂 app/
│   ├── 📂 Http/
│   │   ├── 📂 Controllers/
│   │   │   ├── 📂 Admin/                   # Controller area admin
│   │   │   │   ├── BrandController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── ComplaintController.php
│   │   │   │   ├── CustomerController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── ReportController.php
│   │   │   │   ├── ReturnController.php
│   │   │   │   ├── StockController.php
│   │   │   │   ├── SupplierController.php
│   │   │   │   └── TransactionController.php
│   │   │   ├── 📂 Auth/                    # Controller autentikasi (Breeze)
│   │   │   ├── GuestCatalogController.php  # Controller katalog publik
│   │   │   └── ProfileController.php
│   │   └── 📂 Requests/                   # Form Request Validation
│   │
│   ├── 📂 Models/                          # Eloquent ORM Models
│   │   ├── Brand.php
│   │   ├── Category.php
│   │   ├── Complaint.php
│   │   ├── Customer.php
│   │   ├── MonthlyReport.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Payment.php
│   │   ├── Product.php
│   │   ├── ProductImage.php
│   │   ├── ReturnLog.php
│   │   ├── StockHistory.php
│   │   ├── Supplier.php
│   │   └── User.php
│   │
│   ├── 📂 Services/                        # Business Logic Layer
│   │   ├── OrderService.php                # Logika transaksi & invoice
│   │   ├── ReportService.php               # Logika generate laporan
│   │   └── StockService.php               # Logika manajemen stok
│   │
│   ├── 📂 Providers/
│   └── 📂 View/
│
├── 📂 database/
│   ├── 📂 migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   └── 2026_06_28_000000_create_bintang_jaya_tables.php
│   ├── 📂 seeders/
│   │   └── DatabaseSeeder.php             # Seed data demo lengkap
│   └── 📂 factories/
│
├── 📂 resources/
│   ├── 📂 views/
│   │   ├── 📂 admin/                      # View Blade area admin
│   │   │   ├── 📂 brands/
│   │   │   ├── 📂 categories/
│   │   │   ├── 📂 complaints/
│   │   │   ├── 📂 customers/
│   │   │   ├── 📂 products/
│   │   │   ├── 📂 reports/
│   │   │   ├── 📂 returns/
│   │   │   ├── 📂 stocks/
│   │   │   ├── 📂 suppliers/
│   │   │   ├── 📂 transactions/
│   │   │   └── dashboard.blade.php
│   │   ├── 📂 auth/                       # View autentikasi
│   │   ├── 📂 catalog/                    # View katalog publik (guest)
│   │   ├── 📂 components/                 # Blade Components reusable
│   │   ├── 📂 layouts/                    # Layout utama (admin & guest)
│   │   ├── 📂 pdf/                        # Template PDF (invoice, laporan)
│   │   └── welcome.blade.php              # Halaman katalog publik
│   ├── 📂 css/
│   └── 📂 js/
│
├── 📂 routes/
│   ├── web.php                            # Route web utama
│   ├── auth.php                           # Route autentikasi
│   └── console.php
│
├── 📂 docker/
│   ├── Dockerfile                         # PHP-FPM 8.3 image
│   └── nginx.conf                         # Konfigurasi Nginx
│
├── docker-compose.yml                     # Orkestrasi container
├── .env.example                           # Template konfigurasi environment
├── composer.json                          # Dependensi PHP
├── package.json                           # Dependensi Node.js
└── artisan                                # CLI Laravel
```

---

## 🗄️ 4. Arsitektur Database (Skema Relasi)

Sistem menggunakan **14 tabel utama** dengan relasi foreign key yang ketat dan indeks untuk performa optimal.

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
       │               │ name, sku (UNIQUE), barcode        │
       │               │ category_id (FK → categories)      │
       │               │ brand_id (FK → brands)             │
       │               │ supplier_id (FK → suppliers)       │
       │               │ price_modal, price_jual            │
       │               │ stock, min_stock                   │
       │               │ description, specs                 │
       │               │ is_active, soft_deletes            │
       │               └─────────────┬───────────────────────┘
       │                             │
       │           ┌─────────────────┼──────────────────────┐
       │           │                 │                      │
       │    ┌──────▼──────┐  ┌───────▼────────┐  ┌─────────▼───────┐
       │    │product_image│  │ stock_histories│  │    suppliers    │
       │    │─────────────│  │────────────────│  │─────────────────│
       │    │ product_id  │  │ product_id     │  │ id (PK)         │
       │    │ path        │  │ type           │  │ name            │
       │    │ is_primary  │  │ quantity       │  │ contact_phone   │
       │    └─────────────┘  │ user_id (FK)   │  │ email, address  │
       │                     │ date, desc     │  └─────────────────┘
       │                     └────────────────┘
       │
┌──────▼──────────────────────────┐    ┌─────────────────┐
│             orders              │    │    customers    │
│─────────────────────────────────│    │─────────────────│
│ id (PK)                         │    │ id (PK)         │
│ invoice_number (UNIQUE)         ├────│ name, phone     │
│ customer_id (FK → customers)    │    │ email, address  │
│ user_id (FK → users)            │    │ soft_deletes    │
│ status (Belum Dibayar/Lunas/    │    └─────────────────┘
│         Dibatalkan)             │
│ total_amount, notes, soft_del   │
└──────────────────┬──────────────┘
                   │
     ┌─────────────┼─────────────────────┐
     │             │                     │
┌────▼────────┐ ┌──▼──────────┐ ┌────────▼───────┐
│ order_items │ │  payments   │ │   complaints   │
│─────────────│ │─────────────│ │────────────────│
│ order_id FK │ │ order_id FK │ │ order_id FK    │
│ product_id  │ │ payment_    │ │ customer_name  │
│ item_name   │ │   method    │ │ complaint_text │
│ price, qty  │ │ amount_paid │ │ status         │
│ subtotal    │ │ payment_    │ └────────────────┘
└─────────────┘ │   status    │
                │ payment_date│ ┌────────────────┐
                └─────────────┘ │    returns     │
                                │────────────────│
                                │ order_id FK    │
                                │ product_id FK  │
                                │ quantity       │
                                │ reason, status │
                                └────────────────┘

┌──────────────────────────┐
│     monthly_reports      │
│──────────────────────────│
│ id (PK)                  │
│ report_month (YYYY-MM)   │
│ total_sales              │
│ total_earnings           │
│ total_transactions       │
│ generated_at             │
└──────────────────────────┘
```

### Enum Status Per Tabel

| Tabel | Kolom | Nilai |
|---|---|---|
| `orders` | `status` | `Belum Dibayar` · `Lunas` · `Dibatalkan` |
| `payments` | `payment_status` | `Lunas` · `Belum Lunas` |
| `payments` | `payment_method` | `Cash` · `Transfer` |
| `returns` | `status` | `Menunggu` · `Disetujui` · `Ditolak` |
| `complaints` | `status` | `Menunggu` · `Diproses` · `Selesai` |
| `stock_histories` | `type` | `in` · `out` · `edit` · `delete` · `return` |

---

## 🔄 5. Alur Kerja Utama Sistem (System Flow)

### Alur 1 — Transaksi Penjualan

```
Admin Login
    │
    ▼
Buka Form Transaksi
    │
    ├──► Metode A: Pilih produk dari database
    │        │ → sistem ambil harga otomatis
    │        │ → tambah ke keranjang
    │
    └──► Metode B: Input item manual
             │ → isi nama barang, harga, qty
             │ → tambah ke keranjang
    │
    ▼
Pilih/Input Data Pelanggan (opsional)
    │
    ▼
Konfirmasi & Simpan Transaksi
    │
    ▼
Sistem Otomatis:
    ├── Generate Nomor Invoice (INV/YYYYMMDD/XXXX)
    ├── Kurangi stok produk (jika produk dari database)
    └── Catat riwayat stok (type: out)
    │
    ▼
Cetak Invoice PDF (kapan saja)
    │
    ▼
Update Status Pembayaran (Belum Dibayar → Lunas)
```

### Alur 2 — Pembatalan Transaksi

```
Admin buka detail Invoice
    │
    ▼
Klik Batalkan Transaksi
    │
    ▼
Sistem Otomatis:
    ├── Ubah status order → "Dibatalkan"
    ├── Kembalikan stok tiap produk (stock + qty)
    └── Catat riwayat stok (type: return)
```

### Alur 3 — Proses Retur

```
Admin terima permintaan retur
    │
    ▼
Buat data Return (order_id, product_id, qty, alasan)
    │
    ▼
Admin Review Retur:
    │
    ├──► Setujui Retur:
    │        ├── Status → "Disetujui"
    │        ├── Stok produk bertambah (stock + qty)
    │        └── Catat riwayat stok (type: return)
    │
    └──► Tolak Retur:
             └── Status → "Ditolak"
                 (stok tidak berubah)
```

### Alur 4 — Guest Katalog Publik

```
Pengunjung buka website
    │
    ▼
Melihat Halaman Katalog (welcome.blade.php)
    │
    ├── Filter berdasarkan: Kategori / Merek / Harga
    ├── Cari berdasarkan: Nama Produk / Merek / Kategori
    │
    ▼
Klik Produk → Lihat Detail
    ├── Foto produk
    ├── Harga jual
    ├── Status stok (Tersedia / Habis)
    └── Spesifikasi lengkap

(Guest tidak dapat melakukan transaksi)
```

---

## ✅ 6. Validasi & Logika Teknis Khusus

### Manajemen Stok (StockService)

```
Setiap perubahan stok WAJIB:
1. Update kolom `stock` pada tabel `products`
2. Insert record baru ke tabel `stock_histories`
   dengan field: product_id, type, quantity, user_id, date, description

Tipe perubahan stok:
  - "in"     → Penambahan stok (pembelian dari supplier)
  - "out"    → Pengurangan stok (terjual)
  - "edit"   → Koreksi stok manual oleh admin
  - "delete" → Stok dihapus/dinonaktifkan
  - "return" → Pengembalian stok dari retur disetujui
```

### Logika Invoice (OrderService)

```
Format Nomor Invoice: INV/{YYYYMMDD}/{4-digit-sequence}
Contoh: INV/20260701/0001

Saat order dibuat:
  foreach (item as $item):
    if ($item->product_id !== null):   // produk dari DB
      Product::decrement('stock', $qty)
      StockHistory::create(type: 'out', ...)

Saat order dibatalkan:
  foreach (item as $item):
    if ($item->product_id !== null):
      Product::increment('stock', $qty)
      StockHistory::create(type: 'return', ...)
```

### Validasi Order Item

```
Produk dari Database:
  - product_id wajib valid & aktif di tabel products
  - stok produk harus mencukupi qty yang diminta
  - harga diambil dari price_jual di database (tidak bisa diubah manual)

Item Manual:
  - product_id = null
  - item_name, price, quantity wajib diisi
  - stok tidak dikurangi (barang tidak ada di sistem)
```

### Soft Delete

```
Tabel dengan Soft Delete: customers, products, orders
  → Data tidak terhapus permanen dari database
  → Hanya kolom deleted_at yang diisi
  → Relasi terjaga untuk keperluan histori transaksi
```

### Alert Stok Minimum

```
Dashboard Admin menampilkan peringatan produk hampir habis:
  WHERE products.stock <= products.min_stock
  AND products.is_active = true
  AND products.deleted_at IS NULL
```

---

## 📦 7. Dependensi Project

### PHP Dependencies (composer.json)

| Package | Versi | Fungsi |
|---|---|---|
| `laravel/framework` | ^13.8 | Core framework Laravel |
| `barryvdh/laravel-dompdf` | ^3.1 | Generate PDF (invoice & laporan) |
| `laravel/tinker` | ^3.0 | REPL interaktif untuk debugging |
| `laravel/breeze` | ^2.4 | Starter kit autentikasi (dev) |
| `laravel/pail` | ^1.2.5 | Log tailing real-time (dev) |
| `laravel/pint` | ^1.27 | Code formatter PSR-12 (dev) |
| `phpunit/phpunit` | ^12.5 | Testing framework (dev) |
| `fakerphp/faker` | ^1.23 | Generate data dummy (dev) |

### JavaScript Dependencies (package.json)

| Package | Fungsi |
|---|---|
| `vite` | Build tool & dev server |
| `laravel-vite-plugin` | Integrasi Vite dengan Laravel |
| `chart.js` | Grafik penjualan di dashboard |

### Runtime Environment

| Komponen | Versi |
|---|---|
| PHP | 8.3 |
| MySQL | 8.0 |
| Nginx | Alpine (latest) |
| Node.js | LTS (untuk build assets) |
| Composer | 2.x |

---

## 🔧 8. Panduan Instalasi

### Prasyarat

- Git
- **Metode A (Docker):** Docker Desktop
- **Metode B (Manual):** PHP 8.3, Composer, MySQL 8, Node.js LTS

---

### 🐳 Metode A — Menggunakan Docker (Direkomendasikan)

> Metode ini menjalankan seluruh stack (Nginx, PHP-FPM, MySQL, phpMyAdmin) dalam container Docker secara terisolasi.

**Langkah 1: Clone repository**
```bash
git clone https://github.com/your-username/bintang-jaya-komputer.git
cd bintang-jaya-komputer
```

**Langkah 2: Salin dan konfigurasi environment**
```bash
cp .env.example .env
```

Edit file `.env` untuk konfigurasi database Docker:
```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=bintang_jaya_db
DB_USERNAME=bintang_jaya_user
DB_PASSWORD=bintang_jaya_pass
```

**Langkah 3: Bangun dan jalankan container**
```bash
docker compose up -d --build
```

**Langkah 4: Install dependensi PHP**
```bash
docker compose exec app composer install
```

**Langkah 5: Generate application key**
```bash
docker compose exec app php artisan key:generate
```

**Langkah 6: Jalankan migrasi dan seeder**
```bash
docker compose exec app php artisan migrate --seed
```

**Langkah 7: Build assets frontend**
```bash
docker compose exec app npm install
docker compose exec app npm run build
```

**Langkah 8: Set permission storage**
```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

**Akses Aplikasi:**

| Layanan | URL | Keterangan |
|---|---|---|
| 🌐 Aplikasi Web | http://localhost:8083 | Halaman utama & admin |
| 🗄️ phpMyAdmin | http://localhost:8082 | Manajemen database visual |

**Menghentikan container:**
```bash
docker compose down
```

**Menghapus container + data:**
```bash
docker compose down -v
```

---

### 💻 Metode B — Tanpa Docker (Manual)

> Metode ini menjalankan aplikasi langsung di mesin lokal menggunakan PHP built-in server atau server web yang sudah ada.

**Prasyarat:**
- PHP 8.3+ dengan ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`
- Composer 2.x
- MySQL 8.0+
- Node.js LTS + npm

**Langkah 1: Clone repository**
```bash
git clone https://github.com/your-username/bintang-jaya-komputer.git
cd bintang-jaya-komputer
```

**Langkah 2: Install dependensi PHP**
```bash
composer install
```

**Langkah 3: Salin dan konfigurasi environment**
```bash
cp .env.example .env
```

Edit file `.env`:
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
DB_PASSWORD=your_mysql_password
```

**Langkah 4: Buat database MySQL**
```sql
CREATE DATABASE bintang_jaya_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**Langkah 5: Generate application key**
```bash
php artisan key:generate
```

**Langkah 6: Jalankan migrasi dan seeder**
```bash
php artisan migrate --seed
```

**Langkah 7: Install dan build assets frontend**
```bash
npm install
npm run build
```

**Langkah 8: Set permission storage**
```bash
chmod -R 775 storage bootstrap/cache
```

**Langkah 9: Jalankan server pengembangan**
```bash
php artisan serve
```

Atau gunakan perintah all-in-one (server + queue + log + vite):
```bash
composer run dev
```

**Akses Aplikasi:** http://localhost:8000

---

## 🔑 9. Kredensial Akun Bawaan

Setelah menjalankan `php artisan migrate --seed`, akun berikut tersedia:

### Admin

| Field | Value |
|---|---|
| **Email** | `admin@bintangkomputer.com` |
| **Password** | `password` |
| **Nama** | Krisna Irawan |
| **Role** | Administrator (akses penuh) |

> ⚠️ **Penting:** Segera ganti password default ini setelah login pertama di lingkungan produksi!

### Data Demo yang Di-seed

| Data | Jumlah |
|---|---|
| Kategori Produk | 5 (Laptops, Smartphones, Headphones, Accessories, Smart Home) |
| Merek | 6 (Apple, Asus, Samsung, Sony, Logitech, Google) |
| Supplier | 2 (PT. Bintang Distribusi Nusantara, CV. Global Gadget Lampung) |
| Produk | 7 produk lengkap dengan detail spesifikasi |
| Pelanggan | 3 pelanggan sample |
| Riwayat Order | ~40 order (selama 5 bulan terakhir) |
| Retur | 2 retur (1 pending, 1 disetujui) |
| Komplain | 1 komplain sample |

---

## 🛠️ 10. Ringkasan Tech Stack

```
┌─────────────────────────────────────────────────────────────┐
│                    FRONTEND (Client Side)                    │
│                                                             │
│  📄 Blade Templates    🎨 Native CSS    ⚡ Alpine.js (opt) │
│  📊 Chart.js           🔤 Heroicons / Font Awesome          │
│  ⚙️  Vite (build tool)                                      │
└────────────────────────────┬────────────────────────────────┘
                             │  HTTP
┌────────────────────────────▼────────────────────────────────┐
│                   APPLICATION LAYER                         │
│                                                             │
│  🚀 Laravel 13 (PHP 8.3)   🔐 Laravel Breeze (Auth)       │
│  📋 Eloquent ORM            🛡️  Form Request Validation     │
│  🏗️  Resource Controllers   📦 Service Layer Pattern        │
│  📄 DomPDF (PDF Generate)  🖼️  Intervention Image          │
└────────────────────────────┬────────────────────────────────┘
                             │
┌────────────────────────────▼────────────────────────────────┐
│                    DATA LAYER                               │
│                                                             │
│  🗄️  MySQL 8.0             🔄 Eloquent Migrations           │
│  🌱 Database Seeders        📊 14 Tabel dengan Foreign Key  │
│  🗑️  Soft Deletes          📈 Database Indexes              │
└────────────────────────────┬────────────────────────────────┘
                             │
┌────────────────────────────▼────────────────────────────────┐
│                 INFRASTRUCTURE (Docker)                     │
│                                                             │
│  🐳 Docker Compose          🌐 Nginx (Web Server)          │
│  ⚙️  PHP-FPM 8.3            🗄️  MySQL 8.0 Container        │
│  🔧 phpMyAdmin              🔒 Isolated Network Bridge     │
└─────────────────────────────────────────────────────────────┘
```

| Layer | Teknologi | Versi |
|---|---|---|
| **Language** | PHP | 8.3 |
| **Framework** | Laravel | 13.x |
| **Template Engine** | Blade | — |
| **Authentication** | Laravel Breeze | 2.4 |
| **Database** | MySQL | 8.0 |
| **ORM** | Eloquent | — |
| **PDF** | barryvdh/laravel-dompdf | 3.1 |
| **Charts** | Chart.js | latest |
| **Icons** | Heroicons + Font Awesome | — |
| **Build Tool** | Vite | latest |
| **Web Server** | Nginx Alpine | latest |
| **Runtime** | PHP-FPM | 8.3 |
| **Containerization** | Docker + Docker Compose | — |
| **Code Style** | PSR-12 (enforced by Pint) | — |

---

<div align="center">

**© 2025 CV Bintang Jaya Komputer** — Krisna Irawan, S.Kom.

📍 Jl. Ahmad Yani No.68, Iringmulyo, Kota Metro, Lampung

*Dibuat dengan ❤️ menggunakan Laravel*

</div>
