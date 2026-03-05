<?php
require_once 'config/koneksi.php';
$stmt = $pdo->query("SELECT id_buku, judul, stok FROM buku WHERE id_buku IN (2, 7)");
$books = $stmt->fetchAll();
foreach ($books as $b) {
    echo "ID: " . $b['id_buku'] . " | Judul: " . $b['judul'] . " | Stok: " . $b['stok'] . "\n";
}
?>
