<?php

declare(strict_types=1);

require_once __DIR__ . '/app.php';

const DB_HOST = '127.0.0.1';
const DB_PORT = '3306';
const DB_NAME = 'duabelas_fotografi';
const DB_USER = 'root';
const DB_PASS = '';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $exception) {
    if (basename($_SERVER['PHP_SELF'] ?? '') !== 'install.php') {
        http_response_code(500);
        $installUrl = url('install.php');
        exit('<div style="font-family:Arial;max-width:720px;margin:60px auto;padding:30px;border:1px solid #ddd;border-radius:16px"><h2>Database belum siap</h2><p>Pastikan Apache dan MySQL pada XAMPP sudah aktif, lalu jalankan instalasi.</p><a href="' . e($installUrl) . '">Buka halaman instalasi</a><details style="margin-top:20px"><summary>Detail teknis</summary><pre>' . e($exception->getMessage()) . '</pre></details></div>');
    }
    throw $exception;
}
