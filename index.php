<?php

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$packages = $pdo->query("SELECT * FROM packages WHERE status = 'active' ORDER BY id DESC LIMIT 3")->fetchAll();
$galleryItems = $pdo->query("SELECT * FROM gallery WHERE status = 'active' ORDER BY id DESC LIMIT 4")->fetchAll();
$totalPackages = (int) $pdo->query("SELECT COUNT(*) FROM packages WHERE status = 'active'")->fetchColumn();
$totalBookings = (int) $pdo->query("SELECT COUNT(*) FROM bookings WHERE status IN ('confirmed','completed')")->fetchColumn();

$pageTitle = 'Beranda';
require __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <div class="container hero-grid">
        <div>
            <span class="eyebrow">Duabelas Production</span>

            <h1>
                Mengabadikan Cerita
                <span>Menciptakan kenangan.</span>
            </h1>

            <p>
                Duabelas Production menyediakan layanan fotografi dan
                videografi profesional untuk wedding, prewedding, wisuda,
                acara keluarga, produk, dan dokumentasi lainnya.
            </p>

            <div class="hero-actions">
                <a class="btn btn-primary" href="<?= url('packages.php') ?>">
                    Pilih Paket
                </a>

                <a class="btn btn-outline" href="<?= url('gallery.php') ?>">
                    Lihat Galeri
                </a>
            </div>
        </div>
        <div class="hero-art">

    <div class="hero-image-wrap">
        <img
            src="<?= asset('img/1.jpeg') ?>"
            alt="Ilustrasi kamera profesional"
        >
    </div>

    <div class="hero-note">
        <strong>Booking Online</strong>
        <small>Pilih paket, tanggal, lalu unggah pembayaran.</small>
    </div>


    </div>
</section>

<div class="container stats">
    <div class="stats-grid">
        <div class="stat"><strong><?= max($totalPackages, 6) ?>+</strong><span>Pilihan paket layanan</span></div>
        <div class="stat"><strong><?= max($totalBookings, 25) ?>+</strong><span>Momen terdokumentasi</span></div>
        <div class="stat"><strong>6 Tahun</strong><span>Pengalaman layanan</span></div>
        <div class="stat"><strong>Pasbar</strong><span>Wilayah pelayanan utama</span></div>
    </div>
</div>

<section class="section white">
    <div class="container">
        <div class="section-heading">
            <div><span class="eyebrow">Layanan Pilihan</span><h2>Paket untuk setiap cerita</h2><p>Pilih paket sesuai kebutuhan acara dan anggaran Anda.</p></div>
            <a class="btn btn-outline" href="<?= url('packages.php') ?>">Semua Paket</a>
        </div>
        <div class="card-grid">
            <?php foreach ($packages as $package): ?>
                <article class="card">
                    <img class="card-image" src="<?= $package['photo'] ? url('uploads/packages/' . $package['photo']) : asset('img/default-package.svg') ?>" alt="<?= e($package['name']) ?>">
                    <div class="card-body">
                        <span class="chip"><?= e($package['category']) ?></span>
                        <h3><?= e($package['name']) ?></h3>
                        <p><?= e($package['description']) ?></p>
                        <div class="price"><?= rupiah($package['price']) ?></div>
                        <div class="card-actions">
                            <a class="btn btn-primary btn-sm" href="<?= url('customer/booking_create.php?package=' . $package['id']) ?>">Pesan Sekarang</a>
                            <a class="btn btn-outline btn-sm" href="<?= url('packages.php#package-' . $package['id']) ?>">Detail</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section dark">
    <div class="container">
        <div class="section-heading center"><span class="eyebrow">Dua belas Production</span><h2>Abadikan Moment Berharga</h2><p>Tentukan Tanggal SpesialMu.</p></div>
        <div class="steps">
            <div class="step"><div class="step-number">1</div><h3>Daftar & Login</h3><p>Buat akun pelanggan menggunakan email aktif.</p></div>
            <div class="step"><div class="step-number">2</div><h3>Pilih Paket</h3><p>Tentukan layanan, tanggal, waktu, dan lokasi acara.</p></div>
            <div class="step"><div class="step-number">3</div><h3>Kirim Pembayaran</h3><p>Unggah bukti pembayaran DP melalui dashboard.</p></div>
            <div class="step"><div class="step-number">4</div><h3>Pesanan Dikonfirmasi</h3><p>Admin memverifikasi dan memasukkan acara ke jadwal.</p></div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading"><div><span class="eyebrow">Galeri</span><h2>Duabelas Production</h2><p></p></div><a class="btn btn-outline" href="<?= url('gallery.php') ?>">Buka Galeri</a></div>
        <div class="gallery-grid">
            <?php foreach ($galleryItems as $item): ?>
                <article class="gallery-item">
                    <img src="<?= $item['photo'] ? url('uploads/gallery/' . $item['photo']) : asset('img/default-gallery.svg') ?>" alt="<?= e($item['title']) ?>">
                    <div class="gallery-caption"><strong><?= e($item['title']) ?></strong><small><?= e($item['category']) ?></small></div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section white">
    <div class="container cta">
        <div><h2>Sudah siap mengabadikan momen Anda?</h2><p>Daftar, pilih paket, dan cek status pesanan langsung dari dashboard pelanggan.</p></div>
        <a class="btn btn-dark" href="<?= is_logged_in() ? url('packages.php') : url('register.php') ?>">Mulai Pemesanan</a>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
