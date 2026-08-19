# Website Pemesanan Jasa Fotografi — Dua Belas Production

Website PHP Native + MySQL yang dapat diedit menggunakan Sublime Text dan dijalankan pada localhost melalui XAMPP.

## Fitur

### Pelanggan
- Registrasi dan login.
- Melihat paket serta galeri.
- Pemesanan paket berdasarkan tanggal, waktu, dan lokasi.
- Pencegahan benturan jadwal aktif.
- Unggah bukti pembayaran DP.
- Melihat status dan riwayat pesanan.
- Mengubah data profil dan kata sandi.

### Admin
- Dashboard statistik.
- Kelola paket dan foto paket.
- Kelola galeri portofolio.
- Kelola pemesanan dan status.
- Verifikasi atau tolak pembayaran.
- Jadwal otomatis setelah pembayaran diverifikasi.
- Kelola status akun pelanggan.
- Laporan periode dan cetak laporan.

## Teknologi
- PHP 8.x Native
- MySQL / MariaDB
- HTML5, CSS3, JavaScript
- PDO dan prepared statements
- XAMPP (Apache dan MySQL)
- Sublime Text

## Cara Menjalankan pada Localhost

1. Instal XAMPP dan Sublime Text.
2. Ekstrak folder `duabelas_fotografi`.
3. Salin folder tersebut ke:
   - Windows: `C:\xampp\htdocs\duabelas_fotografi`
4. Buka XAMPP Control Panel.
5. Klik **Start** pada Apache dan MySQL sampai berwarna hijau.
6. Buka browser dan akses:
   - `http://localhost/duabelas_fotografi/install.php`
7. Klik **Instal Database Sekarang**.
8. Setelah berhasil, buka halaman login.

## Akun Awal

### Administrator
- Email: `admin@duabelas.local`
- Password: `admin123`

### Pelanggan Demo
- Email: `pelanggan@demo.local`
- Password: `pelanggan123`

Segera ganti password akun setelah digunakan untuk kebutuhan nyata.

## Membuka Proyek di Sublime Text

1. Jalankan Sublime Text.
2. Pilih **File → Open Folder**.
3. Pilih `C:\xampp\htdocs\duabelas_fotografi`.
4. File konfigurasi utama berada pada:
   - `config/app.php`
   - `config/database.php`
5. Desain utama berada pada:
   - `assets/css/style.css`
6. Script interaktif berada pada:
   - `assets/js/app.js`

## Konfigurasi Database

Konfigurasi bawaan XAMPP berada pada `config/database.php`:

```php
DB_HOST = 127.0.0.1
DB_PORT = 3306
DB_NAME = duabelas_fotografi
DB_USER = root
DB_PASS = kosong
```

Apabila MySQL Anda menggunakan password atau port lain, ubah nilai pada file tersebut dan pada koneksi di `install.php`.

## Struktur Folder

```text
duabelas_fotografi/
├── admin/                 halaman administrator
├── customer/              halaman pelanggan
├── assets/                CSS, JavaScript, dan ilustrasi
├── config/                konfigurasi aplikasi dan database
├── includes/              header, footer, navigasi, autentikasi
├── uploads/               file paket, galeri, dan pembayaran
├── rancangan/             dokumentasi dan diagram sistem
├── database.sql           struktur serta data awal database
├── install.php            instalasi otomatis database
├── index.php              halaman beranda
├── packages.php           katalog paket
├── gallery.php            portofolio
├── login.php              login admin/pelanggan
└── register.php           registrasi pelanggan
```

## Penyesuaian Nama Folder

Konfigurasi `BASE_URL` pada `config/app.php` memakai `/duabelas_fotografi`. Bila nama folder di dalam `htdocs` diubah, nilai tersebut juga harus diubah.

## Mengganti Kontak Studio

Edit bagian kontak pada `includes/footer.php`. Nomor WhatsApp dan Instagram masih berupa contoh sehingga perlu diganti dengan data Dua Belas Production yang sebenarnya.

## Troubleshooting

### Database belum siap
- Pastikan MySQL XAMPP aktif.
- Jalankan kembali `install.php`.
- Pastikan port MySQL adalah 3306.

### Apache tidak dapat berjalan
- Periksa apakah port 80 dipakai aplikasi lain.
- Ubah port Apache melalui XAMPP jika diperlukan, lalu akses misalnya `http://localhost:8080/duabelas_fotografi`.

### Gambar gagal diunggah
- Pastikan folder `uploads` dapat ditulis.
- Gunakan JPG, PNG, atau WEBP maksimal 3 MB.

### Halaman 404
- Pastikan folder benar-benar berada di dalam `htdocs`.
- Gunakan URL lengkap `http://localhost/duabelas_fotografi/` dan bukan membuka file PHP secara langsung dari File Explorer.

## Rancangan Sistem

Buka `rancangan/RANCANGAN_SISTEM.md` dan folder `rancangan/diagram` untuk melihat Use Case, Activity Diagram, Sequence Diagram, ERD, dan Arsitektur Sistem.
