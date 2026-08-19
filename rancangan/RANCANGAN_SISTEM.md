# Rancangan Sistem Pemesanan Jasa Fotografi

## 1. Gambaran Sistem
Sistem dikembangkan menggunakan PHP Native, MySQL, HTML, CSS, dan JavaScript. Aplikasi memiliki dua aktor utama: pelanggan dan admin. Sistem dapat dijalankan melalui Apache dan MySQL pada XAMPP serta diedit menggunakan Sublime Text.

## 2. Aktor dan Hak Akses

### Pelanggan
- Registrasi dan login.
- Melihat katalog paket dan galeri.
- Membuat pemesanan berdasarkan paket, tanggal, waktu, dan lokasi.
- Mengunggah bukti pembayaran DP.
- Melihat status pembayaran dan status pemesanan.
- Melihat riwayat pesanan serta memperbarui profil.

### Admin
- Login ke dashboard administrator.
- Mengelola paket fotografi dan galeri portofolio.
- Melihat serta memproses pemesanan.
- Memverifikasi atau menolak pembayaran.
- Mengelola status pesanan dan jadwal acara.
- Melihat data pelanggan.
- Memfilter dan mencetak laporan pemesanan.

## 3. Alur Utama
1. Pelanggan membuat akun dan login.
2. Pelanggan memilih paket fotografi.
3. Pelanggan mengisi tanggal, waktu, lokasi, dan catatan acara.
4. Sistem memeriksa ketersediaan tanggal.
5. Sistem menyimpan pesanan dengan status menunggu pembayaran.
6. Pelanggan mengunggah bukti pembayaran DP.
7. Admin memverifikasi pembayaran.
8. Sistem mengubah pesanan menjadi dikonfirmasi dan membuat jadwal.
9. Setelah layanan dilaksanakan, admin mengubah status menjadi selesai.
10. Data transaksi dapat ditampilkan pada laporan periode.

## 4. Struktur Database

| Tabel | Fungsi Utama |
|---|---|
| `users` | Menyimpan akun admin dan pelanggan. |
| `packages` | Menyimpan katalog paket, harga, DP, durasi, dan foto. |
| `bookings` | Menyimpan transaksi pemesanan dan statusnya. |
| `payments` | Menyimpan bukti dan verifikasi pembayaran. |
| `schedules` | Menyimpan jadwal dari pesanan yang dikonfirmasi. |
| `gallery` | Menyimpan portofolio yang tampil pada website. |
| `reports` | Menyediakan struktur penyimpanan rekap laporan. |

## 5. Relasi Utama
- Satu pelanggan memiliki banyak pemesanan.
- Satu paket digunakan oleh banyak pemesanan.
- Satu pemesanan dapat memiliki beberapa percobaan pembayaran.
- Satu pemesanan yang dikonfirmasi memiliki satu jadwal.
- Admin dapat memverifikasi banyak pembayaran.

## 6. Rancangan Antarmuka

### Halaman Publik
- Beranda: hero, statistik, paket pilihan, alur pemesanan, galeri, dan CTA.
- Paket: kartu paket, kategori, harga, fasilitas, durasi, DP, dan tombol pesan.
- Galeri: portofolio berdasarkan data admin.
- Login dan registrasi: formulir responsif dan validasi.

### Dashboard Pelanggan
- Ringkasan jumlah pesanan.
- Pesanan terbaru.
- Formulir pemesanan.
- Detail status dan pembayaran.
- Formulir unggah bukti pembayaran.
- Pengelolaan profil.

### Dashboard Admin
- Statistik pelanggan, pemesanan, pembayaran, dan pendapatan.
- CRUD paket dan galeri.
- Daftar serta detail pemesanan.
- Verifikasi pembayaran.
- Jadwal bulanan.
- Data pelanggan.
- Laporan periode yang dapat dicetak.

## 7. Validasi dan Keamanan
- Password disimpan menggunakan `password_hash()`.
- Query database menggunakan PDO prepared statements.
- Setiap formulir perubahan data menggunakan token CSRF.
- Hak akses dipisahkan berdasarkan role admin dan customer.
- Unggahan dibatasi pada JPG, PNG, atau WEBP maksimal 3 MB.
- Folder unggahan menolak eksekusi berkas PHP.
- Tanggal acara tidak dapat dipilih sebelum tanggal hari ini.
- Sistem memeriksa benturan jadwal aktif pada tanggal yang sama.

## 8. Diagram
- `diagram/01-use-case.png`
- `diagram/02-activity-pemesanan.png`
- `diagram/03-sequence-pemesanan.png`
- `diagram/04-erd.png`
- `diagram/05-arsitektur.png`

File sumber Graphviz `.dot` tersedia pada folder yang sama dan dapat diedit kembali.
