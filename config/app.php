<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

date_default_timezone_set('Asia/Jakarta');

const APP_NAME = 'Dua Belas Production';
const BASE_URL = '/duabelas_fotografi';
const ROOT_PATH = __DIR__ . '/..';
const UPLOAD_PATH = ROOT_PATH . '/uploads';

function url(string $path = ''): string
{
    return BASE_URL . ($path !== '' ? '/' . ltrim($path, '/') : '');
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(419);
        exit('Permintaan tidak valid. Silakan muat ulang halaman dan coba lagi.');
    }
}

function rupiah(float|int|string $amount): string
{
    return 'Rp' . number_format((float) $amount, 0, ',', '.');
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool
{
    return isset($_SESSION['user']);
}

function is_admin(): bool
{
    return ($_SESSION['user']['role'] ?? null) === 'admin';
}

function booking_status_label(string $status): string
{
    return match ($status) {
        'pending' => 'Menunggu Konfirmasi',
        'awaiting_payment' => 'Menunggu Pembayaran',
        'payment_review' => 'Pembayaran Diperiksa',
        'confirmed' => 'Dikonfirmasi',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        default => ucfirst(str_replace('_', ' ', $status)),
    };
}

function payment_status_label(string $status): string
{
    return match ($status) {
        'pending' => 'Menunggu Verifikasi',
        'verified' => 'Terverifikasi',
        'rejected' => 'Ditolak',
        default => ucfirst($status),
    };
}

function status_class(string $status): string
{
    return match ($status) {
        'confirmed', 'completed', 'verified', 'active' => 'success',
        'cancelled', 'rejected', 'inactive' => 'danger',
        'payment_review', 'pending' => 'warning',
        default => 'info',
    };
}

function upload_image(array $file, string $folder, int $maxBytes = 3145728): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('File gambar belum dipilih atau gagal diunggah.');
    }

    if (($file['size'] ?? 0) > $maxBytes) {
        throw new RuntimeException('Ukuran gambar maksimal 3 MB.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Format gambar harus JPG, PNG, atau WEBP.');
    }

    $targetDir = UPLOAD_PATH . '/' . trim($folder, '/');
    if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
        throw new RuntimeException('Folder unggahan tidak dapat dibuat.');
    }

    $filename = date('YmdHis') . '_' . bin2hex(random_bytes(6)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], $targetDir . '/' . $filename)) {
        throw new RuntimeException('Gagal menyimpan gambar.');
    }

    return $filename;
}

function delete_upload(?string $filename, string $folder): void
{
    if (!$filename) {
        return;
    }
    $path = UPLOAD_PATH . '/' . trim($folder, '/') . '/' . basename($filename);
    if (is_file($path)) {
        @unlink($path);
    }
}

function old(string $key, string $default = ''): string
{
    return e($_POST[$key] ?? $default);
}
