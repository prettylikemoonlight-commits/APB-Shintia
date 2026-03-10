<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/functions.php';
checkLogin();
checkRole('user');

$pageTitle = 'Dashboard Siswa';

// Stats for user
$userId = $_SESSION['user_id'];
$countDipinjam = $pdo->prepare("SELECT COUNT(*) FROM transaksi WHERE id_user = ? AND status = 'dipinjam'");
$countDipinjam->execute([$userId]);
$dipinjam = $countDipinjam->fetchColumn();

// Recommended books
$books = $pdo->query("SELECT * FROM buku ORDER BY created_at DESC LIMIT 4")->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="card animate-fade-in" style="background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: white; display: flex; align-items: center; justify-content: space-between; border: none; margin-bottom: 2.5rem;">
    <div>
        <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">Halo, <?= $_SESSION['nama'] ?>! 👋</h3>
        <p style="opacity: 0.9;">Kamu sedang meminjam <strong><?= $dipinjam ?></strong> buku saat ini.</p>
        <a href="katalog.php" class="btn btn-secondary" style="margin-top: 1.5rem; color: var(--primary); font-weight: 600;">Cari Buku Baru</a>
    </div>
    <div style="font-size: 5rem; opacity: 0.2; transform: rotate(15deg);">
        <i class="fa-solid fa-book-open-reader"></i>
    </div>
</div>

<h4 style="margin-bottom: 1.5rem; font-weight: 600;">Buku Terbaru</h4>
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
    <?php foreach ($books as $row): ?>
    <div class="card animate-fade-in">
        <div style="width: 100%; height: 180px; background: #f1f5f9; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
            <i class="fa-solid fa-book" style="font-size: 3rem; color: var(--border);"></i>
        </div>
        <p style="font-size: 0.75rem; color: var(--primary); font-weight: 600; text-transform: uppercase;"><?= $row['kategori'] ?></p>
        <h5 style="font-size: 1rem; margin: 0.25rem 0 0.5rem 0; height: 3rem; overflow: hidden;"><?= $row['judul'] ?></h5>
        <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1rem;">Oleh: <?= $row['pengarang'] ?></p>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 0.75rem; color: <?= $row['stok'] > 0 ? 'var(--success)' : 'var(--danger)' ?>;">
                Stok: <?= $row['stok'] ?>
            </span>
            <a href="katalog.php" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">Pinjam</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
