# Sistem Aduan Masyarakat DLHPKKP
## Dinas Lingkungan Hidup, Perumahan, Kawasan Permukiman dan Pertanahan
### Kabupaten Tojo Una-Una

Website aduan masyarakat yang modern dan dinamis untuk DLHPKKP Kabupaten Tojo Una-Una.

## 🚀 Fitur Utama

### Frontend (Masyarakat)
- **Beranda** - Dashboard dengan statistik real-time dan informasi terkini
- **Buat Aduan Baru** - Formulir pengajuan aduan dengan upload bukti/foto
- **Lacak Aduan** - Pelacakan status aduan menggunakan token unik
- **Daftar Aduan** - Daftar semua aduan dengan filter dan pencarian
- **Tentang** - Informasi tentang DLHPKKP dan sistem

### Backend (Admin/Staff)
- **Dashboard Admin** - Statistik dan overview aduan
- **Manajemen Aduan** - Kelola, update status, dan beri tanggapan
- **Manajemen User** - Kelola akun admin dan staff
- **Manajemen Kategori** - Kelola kategori aduan
- **Laporan** - Generate laporan aduan

## 📋 Teknologi

- **Backend**: PHP 7.4+ dengan PDO
- **Database**: MySQL/MariaDB
- **Frontend**: 
  - Bootstrap 5.3
  - Font Awesome 6
  - Animate.css
  - SweetAlert2
  - Custom CSS dengan gradient modern

## 🛠️ Instalasi

### 1. Persyaratan Server
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau MariaDB 10.3
- Web Server (Apache/Nginx)
- Extension PHP: PDO, GD

### 2. Setup Database
```bash
# Import database schema
mysql -u root -p < database.sql
```

### 3. Konfigurasi
Edit file `config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'dlhpkkp_tojo_unauna');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
define('APP_URL', 'http://localhost/aduan-masyarakat/');
```

### 4. Permissions
```bash
# Set permissions for uploads directory
chmod 755 uploads/
chown www-data:www-data uploads/
```

### 5. Akses Aplikasi
- Frontend: `http://localhost/aduan-masyarakat/`
- Admin Login: `http://localhost/aduan-masyarakat/admin/login.php`

## 👤 Default Login Credentials

**Admin:**
- Username: `admin`
- Password: `admin123`

**Staff:**
- Username: `staff1`
- Password: `admin123`

⚠️ **Penting**: Ganti password default setelah instalasi!

## 📁 Struktur Direktori

```
aduan-masyarakat/
├── assets/
│   ├── css/
│   │   └── style.css          # Custom styles
│   ├── js/
│   │   └── main.js            # JavaScript functions
│   └── images/                # Image assets
├── admin/                     # Admin panel
├── includes/                  # Include files
├── templates/
│   └── header.php             # Template header & footer
├── uploads/                   # Uploaded files
├── config.php                 # Configuration
├── database.sql              # Database schema
├── index.php                 # Homepage
├── submit.php                # Submit complaint
├── track.php                 # Track complaint
├── complaints.php            # Complaints list
├── success.php               # Success page
└── about.php                 # About page
```

## 🎨 Desain Features

- **Modern Gradient Design** - Warna hijau gradasi yang fresh
- **Responsive Layout** - Optimal untuk desktop, tablet, dan mobile
- **Smooth Animations** - Animasi halus dengan Animate.css
- **Card-based UI** - Interface modern dengan card components
- **Interactive Elements** - Hover effects dan transitions

## 🔐 Keamanan

- Password hashing dengan bcrypt
- SQL Injection protection (Prepared Statements)
- XSS Protection (htmlspecialchars)
- CSRF Token protection
- File upload validation
- Session security

## 📊 Kategori Aduan

1. Lingkungan Hidup
2. Perumahan
3. Kawasan Permukiman
4. Pertanahan
5. Drainase & Sanitasi
6. Jalan & Jembatan
7. Lainnya

## 🔄 Status Aduan

- **Pending** - Aduan baru, menunggu verifikasi
- **Proses** - Sedang ditindaklanjuti
- **Selesai** - Aduan telah diselesaikan
- **Ditolak** - Aduan tidak dapat diproses

## 📞 Kontak & Support

**DLHPKKP Kabupaten Tojo Una-Una**
- Alamat: Jl. Poros Ampana, Kabupaten Tojo Una-Una
- Email: info@dlhpkkp-tojounauna.go.id
- Telepon: (0458) 123456

## 📝 License

© 2024 DLHPKKP Kabupaten Tojo Una-Una

---

**Developed with ❤️ for Tojo Una-Una**
