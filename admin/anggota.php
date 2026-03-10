<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/functions.php';
checkLogin();
checkRole('admin');

$pageTitle = 'Data Anggota';
$success = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id_user = ? AND role = 'user'");
    if ($stmt->execute([$id])) {
        $success = "Anggota berhasil dihapus!";
    }
}

// Handle Search
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM users WHERE role = 'user' AND (nama LIKE ? OR username LIKE ?) ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute(["%$search%", "%$search%"]);
$members = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <form action="" method="GET" style="display: flex; gap: 0.5rem; max-width: 400px; width: 100%;">
        <input type="text" name="search" class="form-input" placeholder="Cari nama atau username..." value="<?= $search ?>">
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
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Nama</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Username</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Kelas</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Tgl Daftar</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $row): ?>
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 1rem; font-weight: 500;"><?= $row['nama'] ?></td>
                    <td style="padding: 1rem;"><?= $row['username'] ?></td>
                    <td style="padding: 1rem;"><?= $row['kelas'] ?></td>
                    <td style="padding: 1rem;"><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                    <td style="padding: 1rem; text-align: right;">
                        <a href="?delete=<?= $row['id_user'] ?>" onclick="return confirm('Hapus anggota ini?')" class="btn btn-secondary" style="padding: 0.5rem; color: var(--danger);"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($members)): ?>
                <tr>
                    <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">Tidak ada anggota ditemukan</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
