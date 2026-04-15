# 🚀 App Portal — CodeIgniter 3 + Bootstrap 5

Portal manajemen tautan aplikasi dengan dark mode, CRUD lengkap, dan upload logo.

---

## 📁 Struktur File

```
portal_ci3/
├── application/
│   ├── controllers/
│   │   └── Portal.php
│   ├── models/
│   │   └── App_model.php
│   ├── views/
│   │   └── portal/
│   │       └── index.php
│   └── config/
│       ├── database.php   ← Edit kredensial DB di sini
│       └── routes.php
├── assets/
│   └── uploads/
│       └── logos/         ← Folder upload logo (chmod 775)
└── sql/
    └── portal.sql         ← Jalankan ini di phpMyAdmin/MySQL
```

---

## ⚙️ Instalasi

### 1. Salin file ke proyek CI3
Salin setiap file ke folder CI3 Anda yang sudah ada:
- `application/controllers/Portal.php`
- `application/models/App_model.php`
- `application/views/portal/index.php`
- Timpa `application/config/routes.php` (atau merge dengan yang ada)
- Timpa `application/config/database.php` (sesuaikan kredensial)

### 2. Buat database & tabel
```sql
-- Di phpMyAdmin atau MySQL CLI:
SOURCE sql/portal.sql;
```

### 3. Sesuaikan konfigurasi database
Edit `application/config/database.php`:
```php
'hostname' => 'localhost',
'username' => 'root',       // username MySQL Anda
'password' => '',           // password MySQL Anda
'database' => 'portal_db',
```

### 4. Sesuaikan base_url di config.php CI3
```php
// application/config/config.php
$config['base_url'] = 'http://localhost/nama-project/';
```

### 5. Buat folder upload & beri izin
```bash
mkdir -p assets/uploads/logos
chmod 775 assets/uploads/logos
```

### 6. Aktifkan library & helper di autoload (opsional)
```php
// application/config/autoload.php
$autoload['libraries'] = array('database', 'session', 'upload');
$autoload['helper']    = array('url', 'form');
```

---

## ✅ Fitur

| Fitur             | Keterangan                                      |
|-------------------|-------------------------------------------------|
| ➕ Tambah Aplikasi | Modal form dengan validasi                      |
| ✏️ Edit Aplikasi   | Data auto-fill di modal                         |
| 🗑️ Hapus Aplikasi  | Konfirmasi sebelum hapus                        |
| 🖼️ Upload Logo     | Drag & drop / klik, preview real-time           |
| 🎨 Warna Aksen     | Color picker + palette 12 warna                 |
| 📂 Kategori        | Grouping & filter per kategori                  |
| 🔍 Search          | Cari by nama & deskripsi                        |
| 🌙 Dark Mode       | Bootstrap 5 dark theme penuh                    |
| 🔔 Toast Notif     | Feedback sukses/error setelah aksi              |

---

## 🔗 Routing

| Route          | Aksi               |
|----------------|--------------------|
| `/`            | Halaman portal     |
| `POST /apps/store`   | Tambah app   |
| `POST /apps/update`  | Edit app     |
| `POST /apps/delete`  | Hapus app    |
| `POST /apps/toggle`  | Toggle aktif |

---

## 🛠️ Requirements

- PHP 7.4+
- CodeIgniter 3.x
- MySQL 5.7+ / MariaDB 10.3+
- Web server: Apache / Nginx / XAMPP / Laragon
