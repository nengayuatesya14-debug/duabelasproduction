<?php

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

if (is_logged_in()) {
    redirect(is_admin() ? 'admin/dashboard.php' : 'customer/dashboard.php');
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        $error = 'Email atau kata sandi tidak sesuai.';
    } elseif ($user['status'] !== 'active') {
        $error = 'Akun Anda sedang dinonaktifkan.';
    } else {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => (int) $user['id'], 'name' => $user['name'], 'email' => $user['email'], 'role' => $user['role']];
        flash('success', 'Selamat datang, ' . $user['name'] . '.');
        redirect($user['role'] === 'admin' ? 'admin/dashboard.php' : 'customer/dashboard.php');
    }
}

$pageTitle = 'Login';
require __DIR__ . '/includes/header.php';
?>
<section class="auth-section"><div class="auth-card form-card"><span class="eyebrow">Akses Sistem</span><h1>Masuk ke Akun</h1><p>Gunakan akun pelanggan atau administrator.</p><?php if ($error): ?><div class="form-error"><?= e($error) ?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="form-group"><label>Email</label><input type="email" name="email" value="<?= old('email') ?>" required autocomplete="email"></div><div class="form-group" style="margin-top:14px"><label>Kata Sandi</label><input type="password" name="password" required autocomplete="current-password"></div><button class="btn btn-primary btn-block" style="margin-top:20px" type="submit">Login</button></form><p class="auth-footer">Belum punya akun? <a href="<?= url('register.php') ?>">Daftar sekarang</a></p><div class="demo-box"><strong></strong> <br><strong></strong> </div></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
