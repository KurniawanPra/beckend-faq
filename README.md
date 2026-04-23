# FAQ Portal PT INL — Backend (Laravel)

Backend API untuk aplikasi **FAQ Portal PT INL**. Dibangun dengan **Laravel 13** + **Sanctum** untuk autentikasi berbasis token.

---

## Prasyarat

- PHP >= 8.2
- Composer >= 2.x
- MySQL >= 8.0 (via Laragon / XAMPP / manual)
- Laragon / WAMP / MAMP (opsional)

---

## Setup & Instalasi

### 1. Clone & Install Dependensi

```bash
cd beckend-render
composer install
```

### 2. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` sesuai konfigurasi database lokal Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=faq_inl
DB_USERNAME=root
DB_PASSWORD=       # kosongkan jika tidak ada password
```

### 3. Buat Database

Pastikan MySQL berjalan, lalu buat database:

```bash
mysql -uroot -e "CREATE DATABASE IF NOT EXISTS faq_inl CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 4. Jalankan Migration & Seeder

```bash
php artisan migrate
php artisan db:seed
```

Seeder akan membuat:
- 1 akun admin (`admin@inl.co.id` / `password123`)
- 6 topik FAQ (Akun, Password, Pembayaran, Layanan, Kebijakan, Teknis)
- 12 contoh pertanyaan & jawaban HRD PT INL
- 3 contoh inquiry dari pengguna

### 5. Jalankan Server

```bash
php artisan serve --port=8000
```

API tersedia di `http://localhost:8000/api/`

---

## Daftar Endpoint

| Method | URL | Auth | Keterangan |
|:-------|:----|:----:|:-----------|
| POST | `/api/login` | ❌ | Login admin |
| POST | `/api/logout` | ✅ | Logout |
| GET | `/api/me` | ✅ | Info user login |
| GET | `/api/faqs` | ❌ | FAQ publik (support `?q=keyword`) |
| POST | `/api/user-inquiries` | ❌ | Kirim pertanyaan (form publik) |
| GET | `/api/dashboard/stats` | ✅ | Statistik dashboard |
| GET | `/api/topics` | ✅ | Daftar topik |
| POST | `/api/topics` | ✅ | Buat topik baru |
| PUT | `/api/topics/{id}` | ✅ | Update topik |
| DELETE | `/api/topics/{id}` | ✅ | Hapus topik (cascade) |
| GET | `/api/questions` | ✅ | Daftar pertanyaan (filter/search/paginate) |
| POST | `/api/questions` | ✅ | Buat pertanyaan FAQ |
| PUT | `/api/questions/{id}` | ✅ | Update pertanyaan |
| DELETE | `/api/questions/{id}` | ✅ | Hapus pertanyaan |
| GET | `/api/user-inquiries` | ✅ | Daftar inquiry user (filter/search/paginate) |
| PATCH | `/api/user-inquiries/{id}/status` | ✅ | Update status inquiry |
| DELETE | `/api/user-inquiries/{id}` | ✅ | Hapus inquiry |

---

## Autentikasi

Backend menggunakan **Laravel Sanctum** (Token-based). Sertakan token di header:

```
Authorization: Bearer <token>
Accept: application/json
```

### Contoh Login

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@inl.co.id","password":"password123"}'
```

---

## Struktur Database (ERD)

```
users                   → Admin pengelola FAQ
  ↓ (1:N)
topik                   → Kategori/topik FAQ (diurutkan by urutan)
  ↓ (1:N)
pertanyaan              → FAQ Q&A (dibuat oleh admin)

pertanyaan_from_user    → Pertanyaan dari pengunjung publik (form Hubungi Kami)
```

---

## CORS

CORS dikonfigurasi di `config/cors.php` untuk mengizinkan request dari:
- `http://localhost:3000` (Next.js frontend dev)

Untuk production, ubah `allowed_origins` sesuai domain frontend Anda.

---

## Akun Default (Seeder)

| Field | Value |
|:------|:------|
| Email | `admin@inl.co.id` |
| Password | `password123` |

> ⚠️ **Ganti password** setelah pertama kali login di production!
