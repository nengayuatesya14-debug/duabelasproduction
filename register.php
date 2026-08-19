<?php

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

if (is_logged_in()) redirect('index.php');
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['password_confirm'] ?? '';

    try {
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $phone === '' || $address === '') throw new RuntimeException('Semua data wajib diisi dengan benar.');
        if (strlen($password) < 8) throw new RuntimeException('Kata sandi minimal 8 karakter.');
        if ($password !== $confirm) throw new RuntimeException('Konfirmasi kata sandi tidak sama.');
        $stmt = $pdo->prepare("INSERT INTO users (name,email,phone,address,password,role) VALUES (?,?,?,?,?,'customer')");
        $stmt->execute([$name, $email, $phone, $address, password_hash($password, PASSWORD_DEFAULT)]);
        flash('success', 'Pendaftaran berhasil. Silakan login.');
        redirect('login.php');
    } catch (PDOException $exception) {
        $error = $exception->getCode() === '23000' ? 'Email sudah digunakan.' : 'Pendaftaran gagal. Silakan coba lagi.';
    } catch (Throwable $exception) {
        $error = $exception->getMessage();
    }
}
$pageTitle = 'Daftar Pelanggan';
require __DIR__ . '/includes/header.php';
?>
<section class="auth-section"><div class="auth-card form-card"><span class="eyebrow">Akun Pelanggan</span><h1>Buat Akun Baru</h1><p>Data ini digunakan untuk pemesanan dan konfirmasi layanan.</p><?php if ($error): ?><div class="form-error"><?= e($error) ?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="form-grid"><div class="form-group full"><label>Nama Lengkap</label><input name="name" value="<?= old('name') ?>" required></div><div class="form-group"><label>Email</label><input type="email" name="email" value="<?= old('email') ?>" required></div><div class="form-group"><label>No. WhatsApp</label><input name="phone" value="<?= old('phone') ?>" required></div><div class="form-group full"><label>Alamat</label><textarea name="address" required><?= old('address') ?></textarea></div><div class="form-group"><label>Kata Sandi</label><input type="password" name="password" minlength="8" required></div><div class="form-group"><label>Ulangi Kata Sandi</label><input type="password" name="password_confirm" minlength="8" required></div></div><button class="btn btn-primary btn-block" style="margin-top:20px" type="submit">Daftar Pelanggan</button></form><p class="auth-footer">Sudah punya akun? <a href="<?= url('login.php') ?>">Login</a></p></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
