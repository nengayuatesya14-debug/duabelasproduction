<?php
$adminMenu = [
    ['Dashboard', 'admin/dashboard.php'],
    ['Paket', 'admin/packages.php'],
    ['Pemesanan', 'admin/bookings.php'],
    ['Pembayaran', 'admin/payments.php'],
    ['Jadwal', 'admin/schedule.php'],
    ['Galeri', 'admin/gallery.php'],
    ['Pelanggan', 'admin/customers.php'],
    ['Laporan', 'admin/reports.php'],
];
?>
<div class="container admin-nav">
    <?php foreach ($adminMenu as [$label, $path]): ?>
        <a href="<?= url($path) ?>"><?= e($label) ?></a>
    <?php endforeach; ?>
</div>
