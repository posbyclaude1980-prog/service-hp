# Sistem Informasi Service HP

Aplikasi manajemen toko service HP berbasis **Laravel 12** dengan full server-side rendering (Blade).

## Teknologi

- Laravel 12, PHP 8.4+
- MySQL
- Bootstrap 5.3, jQuery, DataTables, SweetAlert2, Chart.js
- DomPDF, Laravel Excel, Intervention Image

## Database & ERD

Skema database lengkap (3NF) dokumentasi di [`docs/ERD.md`](docs/ERD.md).

**29 tabel** + pivot `permission_role`, dengan Foreign Key, Soft Delete, Timestamp, dan UUID pada entitas bisnis utama.

```bash
php artisan migrate:fresh
```

```bash
# Clone & install dependencies
composer install

# Salin environment
cp .env.example .env
php artisan key:generate

# Konfigurasi database di .env
DB_CONNECTION=mysql
DB_DATABASE=service_hp
DB_USERNAME=root
DB_PASSWORD=

# Migrasi & seed
php artisan migrate:fresh --seed
php artisan storage:link
```

## Akses Aplikasi

URL: `http://service-hp.test` (Laragon) atau `php artisan serve`

### Akun Default

| Role    | Email                   | Password |
|---------|-------------------------|----------|
| Owner   | owner@servicehp.test    | password |
| Admin   | admin@servicehp.test    | password |
| Kasir   | kasir@servicehp.test    | password |
| Teknisi | teknisi@servicehp.test  | password |

## Workflow Service

```
Diterima → Diagnosa → Menunggu Persetujuan → Proses Service → QC
→ Selesai → Sudah Diambil → Garansi → Closed
```

Setiap perubahan status tercatat di **Riwayat Perubahan Status** dengan tanggal, jam, user, catatan, dan foto (opsional).

## Modul

- **Dashboard** — statistik, grafik pendapatan & status service
- **Service Order** — tiket service, status workflow, spare part, nota PDF
- **Pelanggan** — CRUD lengkap
- **Master Data** — Merek, Tipe HP, Spare Part, Supplier
- **Pembayaran** — DP & pelunasan
- **Pembelian** — restock spare part + mutasi stok
- **Laporan** — Service, Pendapatan, Stok (PDF/Excel/Print)
- **Pengguna** — manajemen user (Admin)
- **Pengaturan** — profil toko & logo

## Fitur Setiap Modul

CRUD, Search, Filter, Pagination, Export PDF, Export Excel, Print

## Catatan SSL Composer (Windows/Laragon)

Jika `composer install` gagal karena SSL, set:

```bash
composer config cafile C:\laragon\etc\ssl\cacert-combined.pem
```
