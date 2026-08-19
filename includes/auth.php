<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';

function require_login(): void
{
    if (!is_logged_in()) {
        flash('warning', 'Silakan login terlebih dahulu.');
        redirect('login.php');
    }
}

function require_role(string $role): void
{
    require_login();
    if (($_SESSION['user']['role'] ?? '') !== $role) {
        flash('danger', 'Anda tidak memiliki akses ke halaman tersebut.');
        redirect('index.php');
    }
}
