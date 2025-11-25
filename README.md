# VOKS INN — Project Setup Guide

Panduan ini menjelaskan langkah-langkah untuk melakukan setup dan menjalankan **Project VOKS INN** di environment lokal.

---

## 🚀 Tech Stack

* **Framework Fullstack:** Laravel 12
* **Frontend:** Blade
* **Backend:** Laravel 12 (PHP 8+)
* **Database:** MySQL
* **Package Manager:** Composer & npm

---

## 📦 1. Clone Repository

```bash
git clone https://github.com/muhlefi/voks-inn.git
cd voks-inn
```

---

## ⚙️ 2. Install Dependencies

```bash
npm install
composer install
```

---

## 🔧 3. Konfigurasi Environment

Buat file `.env` atau salin dari `.env.example` lalu sesuaikan:

```env
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=voks_inn
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan konfigurasi sesuai kebutuhan lokal.

---

## 🗄️ 4. Setup Database

1. Pastikan database server berjalan.
2. Buat database baru:

   ```sql
   CREATE DATABASE voks_inn;
   ```
3. Generate app key, migrasi, dan seed:

   ```bash
   php artisan key:generate
   php artisan migrate
   php artisan db:seed
   ```

---

## ▶️ 5. Menjalankan Aplikasi

### Development Mode

```bash
composer run dev
```

Jika menggunakan Laravel Mix:

```bash
npm run dev
php artisan serve
```

Aplikasi dapat dibuka di:

```
http://localhost:8000
```

---

## 🛠️ Troubleshooting

* Jika `npm run dev` error → jalankan `npm install` ulang.
* Jika migrasi gagal → cek koneksi database atau hapus tabel lalu migrate ulang.
* Jika asset tidak muncul → jalankan `npm run build` atau `npm run dev`.

---

## 📄 Lisensi

Project ini bersifat internal dan mengikuti ketentuan PROVOKS.
