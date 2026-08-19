<?php

declare(strict_types=1);

require_once __DIR__ . '/config/app.php';

$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        $pdoInstall = new PDO(
            'mysql:host=127.0.0.1;port=3306;charset=utf8mb4',
            'root',
            '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        $sql = file_get_contents(__DIR__ . '/database.sql');
        if ($sql === false) {
            throw new RuntimeException('File database.sql tidak ditemukan.');
        }
        $pdoInstall->exec($sql);
        $message = 'Instalasi berhasil. Database dan data awal sudah dibuat.';
    } catch (Throwable $exception) {
        $error = $exception->getMessage();
    }
}

$pageTitle = 'Instalasi Sistem';
require __DIR__ . '/includes/header.php';
?>
<section class="auth-section">
    <div class="auth-card form-card">
        <span class="eyebrow">Instalasi Lokal</span>
        <h1>Siapkan Database</h1>
        <p>Pastikan Apache dan MySQL pada XAMPP sudah berwarna hijau, lalu tekan tombol instalasi.</p>
        <?php if ($message): ?>
            <div class="alert alert-success"><span><?= e($message) ?></span></div>
            <div class="demo-box">
                <strong>Login admin:</strong> admin@duabelas.local / admin123<br>
                <strong>Login pelanggan:</strong> pelanggan@demo.local / pelanggan123
            </div>
            <p><a class="btn btn-primary btn-block" href="<?= url('login.php') ?>">Lanjut ke Login</a></p>
        <?php else: ?>
            <?php if ($error): ?><div class="form-error"><?= e($error) ?></div><?php endif; ?>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <button class="btn btn-primary btn-block" type="submit">Instal Database Sekarang</button>
            </form>
            <div class="demo-box">Konfigurasi bawaan XAMPP: host 127.0.0.1, user root, password kosong, port 3306.</div>
        <?php endif; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
