<?php

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$category = trim($_GET['category'] ?? '');
$params = [];
$sql = "SELECT * FROM packages WHERE status = 'active'";
if ($category !== '') {
    $sql .= " AND category = ?";
    $params[] = $category;
}
$sql .= " ORDER BY price ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$packages = $stmt->fetchAll();
$categories = $pdo->query("SELECT DISTINCT category FROM packages WHERE status = 'active' ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

$pageTitle = 'Paket Fotografi';
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">Katalog Layanan</span><h1>Paket Fotografi</h1><p>Informasi paket, harga, durasi, fasilitas, dan uang muka tersaji secara jelas.</p></div></section>
<section class="section">
    <div class="container">
        <div class="panel no-print"><div class="panel-body"><form class="filter-bar" method="get"><div class="form-group"><label>Kategori</label><select name="category"><option value="">Semua kategori</option><?php foreach ($categories as $item): ?><option value="<?= e($item) ?>" <?= $category === $item ? 'selected' : '' ?>><?= e($item) ?></option><?php endforeach; ?></select></div><button class="btn btn-dark" type="submit">Terapkan</button><a class="btn btn-outline" href="<?= url('packages.php') ?>">Reset</a></form></div></div>
        <?php if (!$packages): ?><div class="empty">Belum ada paket pada kategori tersebut.</div><?php else: ?>
            <div class="card-grid">
                <?php foreach ($packages as $package): ?>
                    <article class="card" id="package-<?= (int) $package['id'] ?>">
                        <img class="card-image" src="<?= $package['photo'] ? url('uploads/packages/' . $package['photo']) : asset('img/default-package.svg') ?>" alt="<?= e($package['name']) ?>">
                        <div class="card-body">
                            <div class="package-meta"><span class="chip"><?= e($package['category']) ?></span><span class="chip"><?= e((string) $package['duration_hours']) ?> jam</span><span class="chip">DP <?= (int) $package['dp_percentage'] ?>%</span></div>
                            <h3><?= e($package['name']) ?></h3>
                            <p><?= e($package['description']) ?></p>
                            <?php if ($package['features']): ?><ul class="feature-list"><?php foreach (preg_split('/\r\n|\r|\n/', $package['features']) as $feature): ?><li><?= e($feature) ?></li><?php endforeach; ?></ul><?php endif; ?>
                            <div class="price"><?= rupiah($package['price']) ?></div>
                            <div class="card-actions"><a class="btn btn-primary" href="<?= url('customer/booking_create.php?package=' . $package['id']) ?>">Pesan Paket</a></div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
