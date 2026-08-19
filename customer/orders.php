<?php

declare(strict_types=1);
require_once __DIR__.'/../config/database.php';require_once __DIR__.'/../includes/auth.php';require_role('customer');
$stmt=$pdo->prepare("SELECT b.*,p.name package_name FROM bookings b JOIN packages p ON p.id=b.package_id WHERE b.user_id=? ORDER BY b.created_at DESC");$stmt->execute([current_user()['id']]);$rows=$stmt->fetchAll();
$pageTitle='Pesanan Saya';require __DIR__.'/../includes/header.php';require __DIR__.'/../includes/customer_nav.php';
?>
<section class="section" style="padding-top:30px"><div class="container"><div class="dashboard-head"><div><h1>Pesanan Saya</h1><p>Riwayat seluruh pemesanan layanan fotografi.</p></div><a class="btn btn-primary" href="<?= url('packages.php') ?>">+ Pesan Lagi</a></div><div class="panel"><div class="table-wrap"><table><thead><tr><th>Kode</th><th>Paket</th><th>Acara</th><th>Lokasi</th><th>DP</th><th>Status</th><th></th></tr></thead><tbody><?php if(!$rows): ?><tr><td colspan="7" class="empty">Belum ada pesanan.</td></tr><?php else: foreach($rows as $r): ?><tr><td><strong><?= e($r['booking_code']) ?></strong><br><small><?= date('d-m-Y',strtotime($r['created_at'])) ?></small></td><td><?= e($r['package_name']) ?></td><td><?= date('d-m-Y',strtotime($r['event_date'])) ?><br><?= substr($r['start_time'],0,5) ?></td><td><?= e($r['location']) ?></td><td><?= rupiah($r['dp_amount']) ?></td><td><span class="badge badge-<?= status_class($r['status']) ?>"><?= e(booking_status_label($r['status'])) ?></span></td><td><a class="btn btn-outline btn-sm" href="<?= url('customer/order_detail.php?id='.$r['id']) ?>">Detail</a></td></tr><?php endforeach; endif; ?></tbody></table></div></div></div></section>
<?php require __DIR__.'/../includes/footer.php'; ?>
