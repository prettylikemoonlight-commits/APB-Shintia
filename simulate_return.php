<?php
require_once 'config/koneksi.php';
require_once 'config/functions.php';

$id = 3; // The ID we just created
$userId = 5;

// Simulate logic in riwayat.php
$stmt = $pdo->prepare("SELECT * FROM transaksi WHERE id_transaksi = ? AND id_user = ?");
$stmt->execute([$id, $userId]);
$t = $stmt->fetch();

if ($t) {
    echo "Found transaction. Status: " . $t['status'] . "\n";
    if ($t['status'] === 'dipinjam') {
        // Update stock
        $pdo->prepare("UPDATE buku SET stok = stok + 1 WHERE id_buku = ?")->execute([$t['id_buku']]);
        // Calculate denda
        $denda = calculateDenda(date('Y-m-d', strtotime($t['tanggal_pinjam'] . ' + 7 days')), date('Y-m-d'));
        // Update status
        $pdo->prepare("UPDATE transaksi SET status = 'kembali', tanggal_kembali = ?, denda = ? WHERE id_transaksi = ?")
            ->execute([date('Y-m-d'), $denda, $id]);
        echo "Returned successfully. Denda: $denda\n";
    } else {
        echo "Status is not 'dipinjam'\n";
    }
} else {
    echo "Transaction not found\n";
}
?>
