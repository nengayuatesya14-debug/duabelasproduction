<?php

require_once __DIR__ . '/../config/app.php';
$flashMessage = get_flash();
$pageTitle = $pageTitle ?? APP_NAME;
$user = current_user();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem pemesanan layanan jasa fotografi Dua Belas Production">
    <title><?= e($pageTitle) ?> | <?= e(APP_NAME) ?></title>
    <link rel="icon" href="<?= asset('img/1.jpeg') ?>?v=2" type="image/jpeg">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>?v=4">
</head>
<body>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="brand" href="<?= url('index.php') ?>">
    <img
        class="brand-logo"
        src="<?= asset('img/1.jpeg') ?>"
        alt="Logo Duabelas Production"
    >

    <span class="brand-copy">
        <strong class="brand-title">Duabelas Production</strong>
        <small class="brand-tagline">- Accompany Your Life -</small>
    </span>
</a>
        <button class="nav-toggle" type="button" aria-label="Buka menu" data-nav-toggle>☰</button>
        <nav class="main-nav" data-nav>
            <a href="<?= url('index.php') ?>">Beranda</a>
            <a href="<?= url('packages.php') ?>">Paket</a>
            <a href="<?= url('gallery.php') ?>">Galeri</a>
            <?php if (!$user): ?>
                <a href="<?= url('login.php') ?>">Login</a>
                <a class="btn btn-sm btn-primary" href="<?= url('register.php') ?>">Daftar</a>
            <?php elseif ($user['role'] === 'admin'): ?>
                <a href="<?= url('admin/dashboard.php') ?>">Admin</a>
                <a class="btn btn-sm btn-outline" href="<?= url('logout.php') ?>">Logout</a>
            <?php else: ?>
                <a href="<?= url('customer/dashboard.php') ?>">Dashboard</a>
                <a class="btn btn-sm btn-outline" href="<?= url('logout.php') ?>">Logout</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<?php if ($flashMessage): ?>
    <div class="container flash-wrap">
        <div class="alert alert-<?= e($flashMessage['type']) ?>" data-alert>
            <span><?= e($flashMessage['message']) ?></span>
            <button type="button" aria-label="Tutup" data-alert-close>×</button>
        </div>
    </div>
<?php endif; ?>
<main>
