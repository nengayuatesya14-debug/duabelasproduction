<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_role('customer');
$packageId=(int)($_GET['package'] ?? $_POST['package_id'] ?? 0);
$stmt=$pdo->prepare("SELECT * FROM packages WHERE id=? AND status='active'");$stmt->execute([$packageId]);$package=$stmt->fetch();
if(!$package){flash('danger','Paket tidak ditemukan.');redirect('packages.php');}
$error=null;
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();
 $eventDate=$_POST['event_date']??'';$startTime=$_POST['start_time']??'';$location=trim($_POST['location']??'');$notes=trim($_POST['notes']??'');
 try{
  if(!$eventDate||!$startTime||$location==='') throw new RuntimeException('Tanggal, waktu, dan lokasi wajib diisi.');
  if($eventDate<date('Y-m-d')) throw new RuntimeException('Tanggal acara tidak boleh sebelum hari ini.');
  $check=$pdo->prepare("SELECT COUNT(*) FROM bookings WHERE event_date=? AND status IN ('awaiting_payment','payment_review','confirmed')");$check->execute([$eventDate]);
  if((int)$check->fetchColumn()>0) throw new RuntimeException('Tanggal tersebut sudah memiliki jadwal aktif. Silakan pilih tanggal lain.');
  $code='DBP-'.date('ymd').'-'.strtoupper(bin2hex(random_bytes(2)));
  $dp=(float)$package['price']*((int)$package['dp_percentage']/100);
  $stmt=$pdo->prepare("INSERT INTO bookings(booking_code,user_id,package_id,event_date,start_time,location,notes,total_price,dp_amount,status) VALUES(?,?,?,?,?,?,?,?,?,'awaiting_payment')");
  $stmt->execute([$code,current_user()['id'],$packageId,$eventDate,$startTime,$location,$notes,$package['price'],$dp]);
  $id=(int)$pdo->lastInsertId();flash('success','Pemesanan berhasil dibuat. Silakan unggah bukti pembayaran DP.');redirect('customer/order_detail.php?id='.$id);
 }catch(Throwable $e){$error=$e->getMessage();}
}
$pageTitle='Buat Pemesanan';require __DIR__.'/../includes/header.php';require __DIR__.'/../includes/customer_nav.php';
?>
<section class="section" style="padding-top:30px"><div class="narrow"><div class="dashboard-head"><div><h1>Buat Pemesanan</h1><p><?= e($package['name']) ?> — <?= rupiah($package['price']) ?></p></div></div><div class="form-card"><?php if($error): ?><div class="form-error"><?= e($error) ?></div><?php endif; ?><div class="demo-box"><strong>DP minimal:</strong> <?= rupiah((float)$package['price']*((int)$package['dp_percentage']/100)) ?> (<?= (int)$package['dp_percentage'] ?>%)<br><strong>Durasi:</strong> <?= e((string)$package['duration_hours']) ?> jam</div><form method="post" style="margin-top:20px"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="package_id" value="<?= (int)$packageId ?>"><div class="form-grid"><div class="form-group"><label>Tanggal Acara</label><input type="date" name="event_date" value="<?= old('event_date') ?>" data-min-today required></div><div class="form-group"><label>Waktu Mulai</label><input type="time" name="start_time" value="<?= old('start_time','09:00') ?>" required></div><div class="form-group full"><label>Lokasi Acara</label><input name="location" value="<?= old('location') ?>" required></div><div class="form-group full"><label>Catatan / Konsep</label><textarea name="notes" placeholder="Contoh: konsep warna, jumlah orang, kebutuhan khusus..."><?= old('notes') ?></textarea></div></div><button class="btn btn-primary btn-block" style="margin-top:20px" type="submit">Simpan Pemesanan</button></form></div></div></section>
<?php require __DIR__.'/../includes/footer.php'; ?>
