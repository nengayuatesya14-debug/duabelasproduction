<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$userId = (int) current_user()['id'];
$stmt = $pdo->prepare("SELECT COUNT(*) total, SUM(status='confirmed') confirmed, SUM(status='completed') completed FROM bookings WHERE user_id=?");
$stmt->execute([$userId]);
$stats = $stmt->fetch() ?: ['total'=>0,'confirmed'=>0,'completed'=>0];
$stmt = $pdo->prepare("SELECT b.*, p.name package_name FROM bookings b JOIN packages p ON p.id=b.package_id WHERE b.user_id=? ORDER BY b.created_at DESC LIMIT 5");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();
$pageTitle='Dashboard Pelanggan';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/customer_nav.php';
?>
<section class="section" style="padding-top:30px"><div class="container"><div class="dashboard-head"><div><h1>Halo, <?= e(current_user()['name']) ?></h1><p>Pantau pesanan dan pembayaran dari satu halaman.</p></div><a class="btn btn-primary" href="<?= url('packages.php') ?>">+ Buat Pemesanan</a></div><div class="metric-grid"><div class="metric"><span>Total Pesanan</span><strong><?= (int)$stats['total'] ?></strong></div><div class="metric"><span>Dikonfirmasi</span><strong><?= (int)$stats['confirmed'] ?></strong></div><div class="metric"><span>Selesai</span><strong><?= (int)$stats['completed'] ?></strong></div><div class="metric"><span>Akun</span><strong>Aktif</strong></div></div><div class="panel"><div class="panel-head"><h2>Pesanan Terbaru</h2><a class="btn btn-outline btn-sm" href="<?= url('customer/orders.php') ?>">Lihat Semua</a></div><div class="table-wrap"><table><thead><tr><th>Kode</th><th>Paket</th><th>Tanggal</th><th>Total</th><th>Status</th><th></th></tr></thead><tbody><?php if(!$orders): ?><tr><td colspan="6" class="empty">Belum ada pesanan.</td></tr><?php else: foreach($orders as $row): ?><tr><td><strong><?= e($row['booking_code']) ?></strong></td><td><?= e($row['package_name']) ?></td><td><?= date('d-m-Y',strtotime($row['event_date'])) ?>, <?= substr($row['start_time'],0,5) ?></td><td><?= rupiah($row['total_price']) ?></td><td><span class="badge badge-<?= status_class($row['status']) ?>"><?= e(booking_status_label($row['status'])) ?></span></td><td><a class="btn btn-outline btn-sm" href="<?= url('customer/order_detail.php?id='.$row['id']) ?>">Detail</a></td></tr><?php endforeach; endif; ?></tbody></table></div></div></div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
