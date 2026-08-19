<?php

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
$items = $pdo->query("SELECT * FROM gallery WHERE status = 'active' ORDER BY id DESC")->fetchAll();
$pageTitle = 'Galeri Portofolio';
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">Portofolio</span><h1>Galeri Karya</h1><p>Hasil dokumentasi wedding, prewedding, wisuda, event, produk, dan layanan lainnya.</p></div></section>
<section class="section"><div class="container">
    <?php if (!$items): ?><div class="empty">Galeri belum tersedia.</div><?php else: ?><div class="gallery-grid"><?php foreach ($items as $item): ?><article class="gallery-item"><img src="<?= $item['photo'] ? url('uploads/gallery/' . $item['photo']) : asset('img/default-gallery.svg') ?>" alt="<?= e($item['title']) ?>"><div class="gallery-caption"><strong><?= e($item['title']) ?></strong><small><?= e($item['category']) ?><?= $item['description'] ? ' — ' . e($item['description']) : '' ?></small></div></article><?php endforeach; ?></div><?php endif; ?>
</div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
