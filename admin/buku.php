<?php
require_once '../config/koneksi.php';
require_once '../config/functions.php';
checkLogin();
checkRole('admin');

$pageTitle = 'Kelola Buku';
$success = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM buku WHERE id_buku = ?");
    if ($stmt->execute([$id])) {
        $success = "Buku berhasil dihapus!";
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun'];
    $stok = $_POST['stok'];
    $kategori = $_POST['kategori'];

    if (isset($_POST['id_buku']) && !empty($_POST['id_buku'])) {
        // Edit
        $id = $_POST['id_buku'];
        $stmt = $pdo->prepare("UPDATE buku SET judul=?, pengarang=?, penerbit=?, tahun=?, stok=?, kategori=? WHERE id_buku=?");
        if ($stmt->execute([$judul, $pengarang, $penerbit, $tahun, $stok, $kategori, $id])) {
            $success = "Buku berhasil diperbarui!";
        }
    } else {
        // Add
        $stmt = $pdo->prepare("INSERT INTO buku (judul, pengarang, penerbit, tahun, stok, kategori) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$judul, $pengarang, $penerbit, $tahun, $stok, $kategori])) {
            $success = "Buku berhasil ditambahkan!";
        }
    }
}

// Fetch Search results if any
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM buku WHERE judul LIKE ? OR pengarang LIKE ? ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute(["%$search%", "%$search%"]);
$books = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <form action="" method="GET" style="display: flex; gap: 0.5rem; max-width: 400px; width: 100%;">
        <input type="text" name="search" class="form-input" placeholder="Cari judul atau pengarang..." value="<?= $search ?>">
        <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
    <button onclick="openModal()" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Buku</button>
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
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Judul</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Pengarang</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Stok</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Kategori</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $row): ?>
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 1rem; font-weight: 500;"><?= $row['judul'] ?></td>
                    <td style="padding: 1rem;"><?= $row['pengarang'] ?></td>
                    <td style="padding: 1rem;"><?= $row['stok'] ?></td>
                    <td style="padding: 1rem;"><?= $row['kategori'] ?></td>
                    <td style="padding: 1rem; text-align: right;">
                        <button onclick='editBuku(<?= json_encode($row) ?>)' class="btn btn-secondary" style="padding: 0.5rem; color: var(--primary);"><i class="fa-solid fa-pen-to-square"></i></button>
                        <a href="?delete=<?= $row['id_buku'] ?>" onclick="return confirm('Hapus buku ini?')" class="btn btn-secondary" style="padding: 0.5rem; color: var(--danger);"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($books)): ?>
                <tr>
                    <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">Tidak ada data ditemukan</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form -->
<div id="bukuModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 1rem;">
    <div class="card" style="max-width: 600px; width: 100%; position: relative; animation: fadeIn 0.3s ease;">
        <h3 id="modalTitle" style="margin-bottom: 1.5rem;">Tambah Buku</h3>
        <form action="" method="POST">
            <input type="hidden" name="id_buku" id="form_id_buku">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Judul Buku</label>
                    <input type="text" name="judul" id="form_judul" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" id="form_kategori" class="form-input">
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Pengarang</label>
                    <input type="text" name="pengarang" id="form_pengarang" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Penerbit</label>
                    <input type="text" name="penerbit" id="form_penerbit" class="form-input" required>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Tahun Terbit</label>
                    <input type="number" name="tahun" id="form_tahun" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stok" id="form_stok" class="form-input" required>
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem;">
                <button type="button" onclick="closeModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('bukuModal').style.display = 'flex';
        document.getElementById('modalTitle').innerText = 'Tambah Buku';
        document.getElementById('form_id_buku').value = '';
        document.querySelector('form').reset();
    }

    function closeModal() {
        document.getElementById('bukuModal').style.display = 'none';
    }

    function editBuku(data) {
        openModal();
        document.getElementById('modalTitle').innerText = 'Edit Buku';
        document.getElementById('form_id_buku').value = data.id_buku;
        document.getElementById('form_judul').value = data.judul;
        document.getElementById('form_kategori').value = data.kategori;
        document.getElementById('form_pengarang').value = data.pengarang;
        document.getElementById('form_penerbit').value = data.penerbit;
        document.getElementById('form_tahun').value = data.tahun;
        document.getElementById('form_stok').value = data.stok;
    }
</script>

<?php require_once '../includes/footer.php'; ?>
