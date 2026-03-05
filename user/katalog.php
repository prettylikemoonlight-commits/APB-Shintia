<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
checkLogin();
checkRole('user');

$pageTitle = 'Cari Buku';
$success = '';
$error = '';

// Handle Borrow
if (isset($_GET['pinjam'])) {
    $idBuku = $_GET['pinjam'];
    $userId = $_SESSION['user_id'];

    // Check if book exists and stock > 0
    $stmt = $pdo->prepare("SELECT * FROM buku WHERE id_buku = ?");
    $stmt->execute([$idBuku]);
    $buku = $stmt->fetch();

    if ($buku && $buku['stok'] > 0) {
        // Check if user already borrowing this book
        $stmt = $pdo->prepare("SELECT id_transaksi FROM transaksi WHERE id_user = ? AND id_buku = ? AND status = 'dipinjam'");
        $stmt->execute([$userId, $idBuku]);
        
        if ($stmt->fetch()) {
            $error = "Kamu sedang meminjam buku ini!";
        } else {
            // Insert transaction
            $stmt = $pdo->prepare("INSERT INTO transaksi (id_user, id_buku, tanggal_pinjam, status) VALUES (?, ?, ?, 'dipinjam')");
            if ($stmt->execute([$userId, $idBuku, date('Y-m-d')])) {
                // Diminish stock
                $pdo->prepare("UPDATE buku SET stok = stok - 1 WHERE id_buku = ?")->execute([$idBuku]);
                $success = "Buku '" . $buku['judul'] . "' berhasil dipinjam!";
            }
        }
    } else {
        $error = "Stok buku habis!";
    }
}

// Search Logic
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM buku WHERE judul LIKE ? OR pengarang LIKE ? OR kategori LIKE ? ORDER BY judul ASC";
$stmt = $pdo->prepare($query);
$stmt->execute(["%$search%", "%$search%", "%$search%"]);
$books = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div style="margin-bottom: 2.5rem;">
    <form action="" method="GET" style="display: flex; gap: 0.75rem; max-width: 600px;">
        <div style="flex-grow: 1; position: relative;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
            <input type="text" name="search" class="form-input" placeholder="Cari judul, pengarang, atau kategori..." value="<?= $search ?>" style="padding-left: 2.75rem;">
        </div>
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>
</div>

<?php if ($success): ?>
    <div style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; border: 1px solid rgba(16, 185, 129, 0.2);">
        <i class="fa-solid fa-circle-check"></i> <?= $success ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div style="background: rgba(239, 68, 68, 0.1); color: var(--danger); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; border: 1px solid rgba(239, 68, 68, 0.2);">
        <i class="fa-solid fa-circle-xmark"></i> <?= $error ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
    <?php foreach ($books as $row): ?>
    <div class="card animate-fade-in">
        <div style="display: flex; gap: 1rem;">
            <div style="width: 80px; height: 110px; background: #f1f5f9; border-radius: var(--radius-sm); flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-book" style="font-size: 2rem; color: var(--border);"></i>
            </div>
            <div style="flex-grow: 1;">
                <p style="font-size: 0.7rem; color: var(--primary); font-weight: 700; text-transform: uppercase; margin-bottom: 0.25rem;"><?= $row['kategori'] ?: 'Tanpa Kategori' ?></p>
                <h5 style="font-size: 0.95rem; line-height: 1.2; margin-bottom: 0.25rem; height: 2.3rem; overflow: hidden;"><?= $row['judul'] ?></h5>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Penerbit: <?= $row['penerbit'] ?></p>
            </div>
        </div>
        <hr style="border: 0; border-top: 1px solid var(--border); margin: 1rem 0;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="font-size: 0.75rem; color: var(--text-muted);">Pengarang: <strong><?= $row['pengarang'] ?></strong></p>
                <p style="font-size: 0.75rem; color: <?= $row['stok'] > 0 ? 'var(--success)' : 'var(--danger)' ?>;">Stok: <?= $row['stok'] ?></p>
            </div>
            <?php if ($row['stok'] > 0): ?>
                <a href="?pinjam=<?= $row['id_buku'] ?>&search=<?= $search ?>" onclick="return confirm('Pinjam buku ini?')" class="btn btn-primary" style="padding: 0.5rem 1rem;">Pinjam</a>
            <?php else: ?>
                <button class="btn btn-secondary" disabled style="padding: 0.5rem 1rem; opacity: 0.5;">Habis</button>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($books)): ?>
    <div style="grid-column: 1 / -1; text-align: center; padding: 4rem;">
        <i class="fa-solid fa-box-open" style="font-size: 3rem; color: var(--border); margin-bottom: 1rem;"></i>
        <p style="color: var(--text-muted);">Tidak ada buku yang ditemukan.</p>
    </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
