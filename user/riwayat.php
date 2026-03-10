<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/functions.php';
checkLogin();
checkRole('user');

$pageTitle = 'Riwayat Peminjaman';
$success = '';
$userId = $_SESSION['user_id'];

// Handle Return (initiated by user)
if (isset($_GET['return'])) {
    $id = $_GET['return'];
    // Verify ownership and status
    $stmt = $pdo->prepare("SELECT * FROM transaksi WHERE id_transaksi = ? AND id_user = ?");
    $stmt->execute([$id, $userId]);
    $t = $stmt->fetch();

    if ($t && $t['status'] === 'dipinjam') {
        // Update stock
        $pdo->prepare("UPDATE buku SET stok = stok + 1 WHERE id_buku = ?")->execute([$t['id_buku']]);
        // Calculate denda
        $denda = calculateDenda(date('Y-m-d', strtotime($t['tanggal_pinjam'] . ' + 7 days')), date('Y-m-d'));
        // Update status
        $pdo->prepare("UPDATE transaksi SET status = 'kembali', tanggal_kembali = ?, denda = ? WHERE id_transaksi = ?")
            ->execute([date('Y-m-d'), $denda, $id]);
        $success = "Buku berhasil dikembalikan! " . ($denda > 0 ? "Denda Anda: " . formatRupiah($denda) : "");
    }
}

// Fetch user transactions
$query = "SELECT t.*, b.judul, b.pengarang 
          FROM transaksi t 
          JOIN buku b ON t.id_buku = b.id_buku 
          WHERE t.id_user = ?
          ORDER BY t.created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute([$userId]);
$transactions = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<?php if ($success): ?>
    <div style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; border: 1px solid rgba(16, 185, 129, 0.2);">
        <i class="fa-solid fa-circle-check"></i> <?= $success ?>
    </div>
<?php endif; ?>

<div class="card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Buku</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Tgl Pinjam</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Tgl Kembali</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Denda</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Status</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $row): ?>
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 1rem;">
                        <p style="font-weight: 600; margin-bottom: 0.1rem;"><?= $row['judul'] ?></p>
                        <p style="font-size: 0.75rem; color: var(--text-muted);"><?= $row['pengarang'] ?></p>
                    </td>
                    <td style="padding: 1rem; font-size: 0.875rem;"><?= date('d M Y', strtotime($row['tanggal_pinjam'])) ?></td>
                    <td style="padding: 1rem; font-size: 0.875rem;"><?= $row['tanggal_kembali'] ? date('d M Y', strtotime($row['tanggal_kembali'])) : '-' ?></td>
                    <td style="padding: 1rem; font-size: 0.875rem; color: <?= $row['denda'] > 0 ? 'var(--danger)' : 'inherit' ?>;"><?= formatRupiah($row['denda']) ?></td>
                    <td style="padding: 1rem;">
                        <span style="padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.7rem; font-weight: 600; background: <?= $row['status'] === 'dipinjam' ? 'rgba(245, 158, 11, 0.1)' : 'rgba(16, 185, 129, 0.1)' ?>; color: <?= $row['status'] === 'dipinjam' ? 'var(--accent)' : 'var(--success)' ?>; text-transform: uppercase;">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        <?php if ($row['status'] === 'dipinjam'): ?>
                            <a href="?return=<?= $row['id_transaksi'] ?>" onclick="return confirm('Kembalikan buku ini?')" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.7rem; border-radius: var(--radius-sm);">Kembalikan</a>
                        <?php else: ?>
                            <i class="fa-solid fa-circle-check" style="color: var(--success); font-size: 1.25rem;"></i>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($transactions)): ?>
                <tr>
                    <td colspan="6" style="padding: 3rem; text-align: center; color: var(--text-muted);">Kamu belum pernah meminjam buku.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
