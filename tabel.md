# 🗄️ Dokumentasi Rancangan Basis Data
### **CV Bintang Jaya Komputer**

Berikut adalah rancangan basis data (*database tables design*) yang digunakan dalam sistem berdasarkan struktur tabel migrasi database Laravel yang terpasang.


### (15) Tabel product_bookings
Tabel product_bookings menyimpan data booking produk secara online.
- Nama Tabel : product_bookings
- Attribute : id, product_id, customer_name, customer_phone, pickup_time, status, notes, notes_internal, created_at, updated_at
- Primary key : id
- Jumlah field : 10

#### Tabel 15. Rancangan basis data tabel product_bookings
| Field | Type | Null | Key | Default | Extra |
| :--- | :--- | :--- | :--- | :--- | :--- |
| id | Bigint(20) unsigned | NO | PRI | NULL | Auto_increment |
| product_id | Bigint(20) unsigned | NO | MUL | NULL | Foreign Key |
| customer_name | Varchar(255) | NO | | NULL | |
| customer_phone | Varchar(255) | NO | | NULL | |
| pickup_time | Datetime | NO | | NULL | |
| status | Varchar(255) | NO | | 'Menunggu' | |
| notes | Text | YES | | NULL | |
| notes_internal | Text | YES | | NULL | |
| created_at | Timestamp | YES | | NULL | |
| updated_at | Timestamp | YES | | NULL | |
*(Penulis, 2026)*
