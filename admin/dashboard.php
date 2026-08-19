<?php

declare(strict_types=1);
require_once __DIR__.'/../config/database.php';require_once __DIR__.'/../includes/auth.php';require_role('admin');
$metrics=[
 'customers'=>(int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn(),
 'bookings'=>(int)$pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn(),
 'pending'=>(int)$pdo->query("SELECT COUNT(*) FROM payments WHERE verification_status='pending'")->fetchColumn(),
 'revenue'=>(float)$pdo->query("SELECT COALESCE(SUM(amount),0) FROM payments WHERE verification_status='verified'")->fetchColumn(),
];
$recent=$pdo->query("SELECT b.*,u.name customer_name,p.name package_name FROM bookings b JOIN users u ON u.id=b.user_id JOIN packages p ON p.id=b.package_id ORDER BY b.created_at DESC LIMIT 8")->fetchAll();
$pageTitle='Dashboard Admin';require __DIR__.'/../includes/header.php';require __DIR__.'/../includes/admin_nav.php';
?>
<section class="section" style="padding-top:30px"><div class="container"><div class="dashboard-head"><div><h1>Dashboard Administrator</h1><p>Ringkasan kegiatan pemesanan Dua Belas Production.</p></div><a class="btn btn-primary" href="<?= url('admin/package_form.php') ?>">+ Tambah Paket</a></div><div class="metric-grid"><div class="metric"><span>Pelanggan</span><strong><?= $metrics['customers'] ?></strong></div><div class="metric"><span>Total Pemesanan</span><strong><?= $metrics['bookings'] ?></strong></div><div class="metric"><span>Pembayaran Menunggu</span><strong><?= $metrics['pending'] ?></strong></div><div class="metric"><span>Pendapatan Terverifikasi</span><strong style="font-size:1.2rem"><?= rupiah($metrics['revenue']) ?></strong></div></div><div class="panel"><div class="panel-head"><h2>Pemesanan Terbaru</h2><a class="btn btn-outline btn-sm" href="<?= url('admin/bookings.php') ?>">Lihat Semua</a></div><div class="table-wrap"><table><thead><tr><th>Kode</th><th>Pelanggan</th><th>Paket</th><th>Tanggal Acara</th><th>Status</th><th></th></tr></thead><tbody><?php if(!$recent): ?><tr><td colspan="6" class="empty">Belum ada pemesanan.</td></tr><?php else: foreach($recent as $r): ?><tr><td><strong><?= e($r['booking_code']) ?></strong></td><td><?= e($r['customer_name']) ?></td><td><?= e($r['package_name']) ?></td><td><?= date('d-m-Y',strtotime($r['event_date'])) ?><br><small><?= substr($r['start_time'],0,5) ?></small></td><td><span class="badge badge-<?= status_class($r['status']) ?>"><?= e(booking_status_label($r['status'])) ?></span></td><td><a class="btn btn-outline btn-sm" href="<?= url('admin/booking_detail.php?id='.$r['id']) ?>">Detail</a></td></tr><?php endforeach; endif; ?></tbody></table></div></div></div></section>
<?php require __DIR__.'/../includes/footer.php'; ?>
