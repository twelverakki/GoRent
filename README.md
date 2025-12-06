# 📸 GoRent - Sistem Manajemen Penyewaan Alat

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

**GoRent** adalah aplikasi web modern untuk manajemen penyewaan peralatan (kamera, lensa, drone, dll). Sistem ini mendigitalisasi seluruh siklus sewa mulai dari pemesanan pelanggan hingga pengembalian barang, dilengkapi dengan **Notifikasi WhatsApp Otomatis** (via Fonntee) dan manajemen stok yang akurat.

---

## ✨ Fitur Utama

### 🛠️ Fitur Sistem & Admin

-   **Dashboard Analitik:** Menampilkan ringkasan transaksi terbaru dan **Total Revenue** (Pendapatan Riil).
-   **Manajemen Inventaris:** CRUD Alat dengan kategori, harga sewa per hari, dan manajemen stok otomatis.
-   **Manajemen Pengguna:** Kelola akun Admin dan Customer (Lihat, Edit, Hapus).
-   **Proses Pengembalian Cerdas:**
    -   Perhitungan **Denda Keterlambatan** otomatis (berdasarkan hari terlambat).
    -   Input **Denda Kerusakan** manual saat pengembalian barang.
    -   _Restock_ otomatis saat status transaksi menjadi `completed`.
-   **Keamanan:** Menggunakan **UUID/ULID** sebagai Primary Key untuk User dan model utama lainnya.

### 🛒 Fitur Pelanggan (Customer)

-   **Katalog Interaktif:** Pencarian dan filter alat yang tersedia.
-   **Sistem Keranjang (Cart):** Menambah beberapa alat sebelum _checkout_.
-   **Riwayat Transaksi:** Memantau status sewa (Pending, Paid, Rented, Completed, Cancelled).
-   **Validasi Booking:** Sistem mencegah pemilihan tanggal sewa yang tidak logis (minimal 1 hari).

### 📱 Integrasi WhatsApp Gateway (Fonntee)

Aplikasi ini mengirimkan notifikasi _real-time_ untuk event berikut:

1.  **Checkout Berhasil:** Pesan detail tagihan ke Customer.
2.  **Pesanan Baru:** Notifikasi "Action Needed" ke Admin setiap ada order masuk.
3.  **Konfirmasi Pembayaran:** Notifikasi ke Customer saat Admin mengubah status menjadi `paid`.
4.  **Selesai Sewa:** Laporan pengembalian dan rincian denda (jika ada) ke Customer.
5.  **Registrasi:** Sambutan otomatis untuk pengguna baru.

---

## ⚙️ Teknologi yang Digunakan

-   **Backend:** PHP 8.2+, Laravel 11
-   **Frontend:** Blade Template, Tailwind CSS, Alpine.js
-   **Database:** MySQL / MariaDB / SQLite
-   **API Service:** Fonntee (WhatsApp Gateway)

---

## 🚀 Panduan Instalasi (Lokal)

Ikuti langkah-langkah ini untuk menjalankan proyek di komputer Anda.

### 1. Prasyarat

Pastikan Anda telah menginstal:

-   PHP >= 8.2
-   Composer
-   Node.js & NPM

### 2. Kloning Repositori

```bash
git clone [https://github.com/username-anda/gorent.git](https://github.com/username-anda/gorent.git)
cd gorent
```

### 3. Instalasi Dependensi

```bash
composer install
npm install
```

### 4. Instalasi Dependensi

```bash
cp .env.example .env
```

Buka file .env dan sesuaikan konfigurasi berikut

A. Database

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_gorent
DB_USERNAME=root
DB_PASSWORD=
```

B. WhatsApp Fonntee & Admin: Dapatkan API Key di dashboard Fonntee Anda.

```
FONNTEE_API_KEY="ISI_API_KEY_ANDA_DISINI"
FONNTEE_BASE_URL="[https://api.fonnte.com/send](https://api.fonnte.com/send)"

# Nomor Admin untuk menerima notifikasi order (Format 62...)
ADMIN_WA_NUMBER="6281234567890"
```

### 5. Generate Key & Migrasi

Buat App Key dan jalankan migrasi database serta seeder (data dummy).

```
php artisan key:generate
php artisan migrate --seed
```

### 6. Setup Storage

Buat symlink agar gambar alat dapat diakses publik:

```
php artisan storage:link
```

### 7. Jalankan Aplikasi

Jalankan server backend dan frontend (dalam 2 terminal terpisah):

A. Terminal 1 (Laravel):

```
php artisan serve
```

B. Terminal 2 (Vite):

```
npm run dev
```

Akses aplikasi di: http://127.0.0.1:8000

### 👤 Akun Demo (Seeder)

Jika Anda menjalankan `php artisan migrate --seed`, akun berikut tersedia untuk pengujian::

| Role         | Nama          | Email              | Password   | No. HP       | Keterangan                       |
| :----------- | :------------ | :----------------- | :--------- | :----------- | :------------------------------- |
| **Admin**    | Kennan Admin  | `admin@gorent.com` | `password` | 081234567890 | Akses Dashboard & Kelola Sistem  |
| **Customer** | Jems Customer | `jems@gmail.com`   | `password` | 08987654321  | Skenario User Normal (Baik)      |
| **Customer** | Bad Boy       | `bad@gmail.com`    | `password` | 08111111111  | Skenario User Bermasalah (Denda) |

### 📂 Struktur Folder Penting

app/Http/Controllers/Admin - Controller untuk logika Admin (Return, Tools, Dashboard).

app/Http/Controllers/RentalController.php - Logika Checkout & Transaksi.

app/Services/WhatsAppService.php - Service khusus untuk integrasi API Fonntee.

app/Models/User.php - Model User (dikonfigurasi dengan UUID).

routes/web.php - Definisi rute untuk Guest, Customer, dan Admin.

### 🤝 Kontribusi

Fork repositori ini.

Buat branch fitur baru (git checkout -b fitur-baru).

Commit perubahan Anda (git commit -m 'Menambahkan fitur X').

Push ke branch tersebut (git push origin fitur-baru).

Buat Pull Request.

### 📝 Lisensi

Proyek ini bersifat open-source di bawah lisensi MIT.
