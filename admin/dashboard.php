<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
checkLogin();
checkRole('admin');

$pageTitle = 'Dashboard Admin';

// Fetch stats
$countBuku = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
$countAnggota = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$countPinjam = $pdo->query("SELECT COUNT(*) FROM transaksi WHERE status = 'dipinjam'")->fetchColumn();

// Fetch recent transactions
$recentTransactions = $pdo->query("SELECT t.*, u.nama, b.judul 
                                   FROM transaksi t 
                                   JOIN users u ON t.id_user = u.id_user 
                                   JOIN buku b ON t.id_buku = b.id_buku 
                                   ORDER BY t.created_at DESC LIMIT 5")->fetchAll();

require_once '../includes/header.php';
?>

<div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
    <div class="card animate-fade-in" style="animation-delay: 0.1s; border-left: 4px solid var(--primary);">
        <p style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Total Buku</p>
        <h3 style="font-size: 2rem; margin-top: 0.5rem;"><?= $countBuku ?></h3>
    </div>
    <div class="card animate-fade-in" style="animation-delay: 0.2s; border-left: 4px solid var(--secondary);">
        <p style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Total Anggota</p>
        <h3 style="font-size: 2rem; margin-top: 0.5rem;"><?= $countAnggota ?></h3>
    </div>
    <div class="card animate-fade-in" style="animation-delay: 0.3s; border-left: 4px solid var(--accent);">
        <p style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500;">Buku Sedang Dipinjam</p>
        <h3 style="font-size: 2rem; margin-top: 0.5rem;"><?= $countPinjam ?></h3>
    </div>
</div>

<div class="card animate-fade-in" style="animation-delay: 0.4s;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h4 style="font-weight: 600;">Transaksi Terbaru</h4>
        <a href="transaksi.php" style="color: var(--primary); font-size: 0.875rem; font-weight: 600; text-decoration: none;">Lihat Semua</a>
    </div>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 1rem 0; color: var(--text-muted); font-weight: 500;">Nama Peminjam</th>
                    <th style="padding: 1rem 0; color: var(--text-muted); font-weight: 500;">Judul Buku</th>
                    <th style="padding: 1rem 0; color: var(--text-muted); font-weight: 500;">Tgl Pinjam</th>
                    <th style="padding: 1rem 0; color: var(--text-muted); font-weight: 500;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentTransactions as $row): ?>
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 1rem 0; font-weight: 500;"><?= $row['nama'] ?></td>
                    <td style="padding: 1rem 0;"><?= $row['judul'] ?></td>
                    <td style="padding: 1rem 0;"><?= date('d/m/Y', strtotime($row['tanggal_pinjam'])) ?></td>
                    <td style="padding: 1rem 0;">
                        <span style="padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 500; background: <?= $row['status'] === 'dipinjam' ? 'rgba(245, 158, 11, 0.1)' : 'rgba(16, 185, 129, 0.1)' ?>; color: <?= $row['status'] === 'dipinjam' ? 'var(--accent)' : 'var(--success)' ?>;">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($recentTransactions)): ?>
                <tr>
                    <td colspan="4" style="padding: 2rem; text-align: center; color: var(--text-muted);">Belum ada transaksi</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
