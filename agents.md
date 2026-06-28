# AGENTS.md

# Project
Sistem Informasi Penjualan CV Bintang Jaya Komputer

## Deskripsi

CV Bintang Jaya Komputer merupakan usaha yang didirikan oleh Bapak Krisna Irawan, S.Kom. pada tanggal 4 September 2025 dan berlokasi di Jl. Ahmad Yani No.68, Iringmulyo, Kota Metro, Lampung.

Sistem ini merupakan website penjualan berbasis Laravel yang menggabungkan konsep marketplace dengan sistem manajemen toko.

Website memiliki dua jenis pengguna utama:

- Admin
- Guest

Seluruh pengembangan harus mengikuti dokumen ini.

---

# Tujuan Sistem

Membangun sistem informasi penjualan yang mampu:

- Mengelola produk dan stok
- Melakukan transaksi penjualan
- Menghasilkan invoice PDF
- Menghasilkan laporan bulanan PDF
- Menampilkan katalog produk kepada pengunjung
- Mengurangi stok secara otomatis ketika transaksi berhasil
- Mengembalikan stok ketika transaksi dibatalkan atau diretur

---

# User Roles

## 1. Admin

Admin merupakan pengguna utama sistem.

Admin memiliki hak akses penuh terhadap seluruh fitur.

### Hak Akses

- Login
- Dashboard
- CRUD Produk
- CRUD Kategori
- CRUD Merk
- CRUD Supplier
- CRUD Stok
- Melihat seluruh transaksi
- Membuat transaksi
- Membatalkan transaksi
- Melihat histori pembelian
- Mengelola retur
- Mengelola komplain
- Mengelola pelanggan
- Cetak Invoice PDF
- Cetak Laporan Bulanan PDF
- Melihat statistik penjualan
- Mengelola akun admin

---

## 2. Guest

Guest tidak perlu login.

Guest hanya dapat melihat informasi produk.

### Hak Akses

- Melihat katalog produk
- Melihat stok tersedia
- Melihat spesifikasi produk
- Melihat kategori
- Melihat merk
- Melakukan pencarian produk
- Melakukan filter produk

Guest tidak dapat melakukan transaksi maupun mengubah data.

---

# Business Rules

## Produk

Produk memiliki:

- Nama
- SKU
- Barcode (opsional)
- Kategori
- Merk
- Supplier
- Harga Modal
- Harga Jual
- Jumlah Stok
- Minimum Stok
- Deskripsi
- Spesifikasi
- Foto Produk
- Status Aktif

---

## Manajemen Stok

Admin dapat:

- Menambah stok
- Mengurangi stok
- Mengedit stok
- Menghapus stok

Setiap perubahan stok harus tersimpan pada tabel riwayat stok.

Riwayat harus menyimpan:

- Produk
- Jenis perubahan
- Jumlah
- Admin
- Tanggal
- Keterangan

---

## Transaksi

Admin dapat membuat transaksi dengan dua metode.

### Metode 1

Mengambil produk langsung dari database.

Admin memilih:

- Produk
- Jumlah

Harga diambil otomatis dari database.

---

### Metode 2

Menambahkan item manual.

Digunakan apabila barang belum tersedia pada database.

Admin mengisi:

- Nama Barang
- Harga
- Jumlah

---

## Invoice

Invoice harus dapat dibuat dalam bentuk PDF.

Invoice berisi:

- Nomor Invoice
- Tanggal
- Nama Pelanggan
- Daftar Barang
- Qty
- Harga
- Subtotal
- Total
- Nama Admin

Invoice dapat dicetak kapan saja.

---

## Pengurangan Stok

Ketika invoice berhasil dibuat:

stok = stok - qty

berlaku otomatis.

---

## Pembatalan Invoice

Ketika invoice dibatalkan:

stok = stok + qty

berlaku otomatis.

---

## Pembayaran

Seluruh pembayaran dilakukan secara manual.

Sistem hanya mencatat:

- Belum Dibayar
- Lunas

Tidak ada integrasi payment gateway.

---

## Riwayat Pembelian

Sistem menyimpan:

- Invoice
- Barang
- Jumlah
- Harga
- Admin
- Pelanggan
- Waktu

---

## Retur

Admin dapat:

- Menyetujui retur
- Menolak retur

Jika retur diterima maka stok dikembalikan.

---

## Komplain

Komplain disimpan sebagai histori.

Status:

- Menunggu
- Diproses
- Selesai

---

# Dashboard Admin

Dashboard menampilkan:

- Total Produk
- Total Kategori
- Total Stok
- Produk Hampir Habis
- Total Penjualan Hari Ini
- Total Penjualan Bulan Ini
- Jumlah Invoice
- Jumlah Retur
- Grafik Penjualan Bulanan
- Grafik Produk Terlaris

---

# Laporan

Laporan otomatis dalam bentuk PDF.

Jenis laporan:

- Penjualan Harian
- Penjualan Bulanan
- Penjualan Tahunan
- Laporan Retur
- Laporan Produk Terlaris
- Laporan Stok

---

# UI Guidelines

Ikuti desain Figma yang diberikan oleh pengguna.

Aturan umum:

- Clean
- Modern
- Responsive
- Minimalis
- Dominan warna putih
- Primary color mengikuti desain Figma
- Sidebar admin
- Navbar sederhana
- Menggunakan icon Heroicons atau Font Awesome
- Tabel responsif
- Card dashboard
- Pagination
- Search
- Filter
- Toast notification
- Modal CRUD

Jangan membuat desain di luar referensi Figma yang diberikan.

---

# Tech Stack

Backend

- PHP 8.2.12
- Laravel 12
- Blade
- Eloquent ORM

Frontend

- Blade
- css native
- JavaScript
- Alpine.js (jika diperlukan)

Database

- MySQL 8

Authentication

- Laravel Breeze

PDF

- barryvdh/laravel-dompdf

Charts

- Chart.js

Image

- Intervention Image

Icons

- Heroicons
- Font Awesome

Deployment

- Docker
- Docker Compose

Web Server

- Nginx

PHP Runtime

- PHP-FPM 8.2

---

# Docker

Project wajib menggunakan Docker.

Container minimal:

- nginx
- php-fpm
- mysql
- phpmyadmin

Gunakan Docker Compose.

---

# Coding Standard

Gunakan:

- PSR-12
- Laravel Best Practices
- Repository Pattern bila diperlukan
- Service Layer untuk business logic
- Form Request Validation
- Resource Controller
- Migration
- Seeder
- Factory

Tidak diperbolehkan query SQL langsung apabila dapat menggunakan Eloquent.

---

# Database

Minimal tabel:

- users
- customers
- categories
- brands
- suppliers
- products
- product_images
- stock_histories
- orders
- order_items
- payments
- complaints
- returns
- monthly_reports

Gunakan foreign key pada seluruh relasi.

---

# PDF

PDF harus dapat menghasilkan:

- Invoice
- Nota
- Laporan Penjualan
- Laporan Retur
- Laporan Stok

---

# Security

- CSRF Protection
- Validation seluruh input
- Authentication
- Authorization
- Password Hashing
- Session Authentication
- Soft Delete untuk data penting
- Audit Log aktivitas admin

---

# Search

Guest dapat mencari berdasarkan:

- Nama Produk
- Merk
- Kategori

Admin dapat mencari seluruh data.

---

# Performance

Gunakan:

- Pagination
- Lazy Loading Image
- Eager Loading
- Database Index
- Cache apabila diperlukan

---

# Testing

Minimal:

- Blackbox Testing
- Beta Testing

---

# Scope

Fitur yang harus tersedia:

✅ Login Admin

✅ Dashboard

✅ CRUD Produk

✅ CRUD Kategori

✅ CRUD Merk

✅ CRUD Supplier

✅ CRUD Pelanggan

✅ CRUD Stok

✅ Histori Stok

✅ Pencatatan Transaksi

✅ Invoice PDF

✅ Nota PDF

✅ Laporan Bulanan PDF

✅ Riwayat Pembelian

✅ Retur

✅ Komplain

✅ Statistik Dashboard

✅ Marketplace Katalog Produk

✅ Pencarian Produk

✅ Filter Produk

✅ Responsive Design

---

# Out of Scope

- Payment Gateway
- Multi Warehouse
- Multi Cabang
- Marketplace API
- Mobile App
- AI Recommendation
- Chat
- Live Tracking
- QRIS
- Integrasi Ekspedisi

---

# Development Principle

Seluruh implementasi harus:

- Mengikuti Laravel Best Practices.
- Mengutamakan clean architecture.
- Memisahkan business logic dari controller.
- Mudah dikembangkan.
- Mudah dipelihara.
- Konsisten dengan desain Figma yang diberikan pengguna.
- Menghasilkan kode yang bersih, modular, dan terdokumentasi.