<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/functions.php';
checkLogin();
checkRole('admin');

$pageTitle = 'Semua Transaksi';
$success = '';

// Handle Return
if (isset($_GET['return'])) {
    $id = $_GET['return'];
    // Get transaction details to update stock
    $stmt = $pdo->prepare("SELECT * FROM transaksi WHERE id_transaksi = ?");
    $stmt->execute([$id]);
    $t = $stmt->fetch();

    if ($t && $t['status'] === 'dipinjam') {
        // Update stock
        $pdo->prepare("UPDATE buku SET stok = stok + 1 WHERE id_buku = ?")->execute([$t['id_buku']]);
        // Calculate denda (assuming pinjam period is 7 days)
        $denda = calculateDenda(date('Y-m-d', strtotime($t['tanggal_pinjam'] . ' + 7 days')), date('Y-m-d'));
        // Update status
        $pdo->prepare("UPDATE transaksi SET status = 'kembali', tanggal_kembali = ?, denda = ? WHERE id_transaksi = ?")
            ->execute([date('Y-m-d'), $denda, $id]);
        $success = "Buku berhasil dikembalikan! Denda: " . formatRupiah($denda);
    }
}

// Handle Search
$search = $_GET['search'] ?? '';
$query = "SELECT t.*, u.nama, b.judul 
          FROM transaksi t 
          JOIN users u ON t.id_user = u.id_user 
          JOIN buku b ON t.id_buku = b.id_buku 
          WHERE u.nama LIKE ? OR b.judul LIKE ?
          ORDER BY t.created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute(["%$search%", "%$search%"]);
$transactions = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <form action="" method="GET" style="display: flex; gap: 0.5rem; max-width: 400px; width: 100%;">
        <input type="text" name="search" class="form-input" placeholder="Cari peminjaman..." value="<?= $search ?>">
        <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
</div>

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
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Peminjam</th>
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
                    <td style="padding: 1rem; font-weight: 500;"><?= $row['nama'] ?></td>
                    <td style="padding: 1rem;"><?= $row['judul'] ?></td>
                    <td style="padding: 1rem;"><?= date('d/m/Y', strtotime($row['tanggal_pinjam'])) ?></td>
                    <td style="padding: 1rem;"><?= $row['tanggal_kembali'] ? date('d/m/Y', strtotime($row['tanggal_kembali'])) : '-' ?></td>
                    <td style="padding: 1rem;"><?= formatRupiah($row['denda']) ?></td>
                    <td style="padding: 1rem;">
                        <span style="padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 500; background: <?= $row['status'] === 'dipinjam' ? 'rgba(245, 158, 11, 0.1)' : 'rgba(16, 185, 129, 0.1)' ?>; color: <?= $row['status'] === 'dipinjam' ? 'var(--accent)' : 'var(--success)' ?>;">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        <?php if ($row['status'] === 'dipinjam'): ?>
                            <a href="?return=<?= $row['id_transaksi'] ?>" onclick="return confirm('Konfirmasi pengembalian buku?')" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.75rem; color: var(--primary);">Kembalikan</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($transactions)): ?>
                <tr>
                    <td colspan="7" style="padding: 2rem; text-align: center; color: var(--text-muted);">Tidak ada riwayat transaksi</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
