<?php
require_once 'config/koneksi.php';
$stmt = $pdo->query("SELECT t.*, b.judul FROM transaksi t JOIN buku b ON t.id_buku = b.id_buku");
$transactions = $stmt->fetchAll();
foreach ($transactions as $t) {
    echo "ID: " . $t['id_transaksi'] . " | Buku: " . $t['judul'] . " | Status: " . $t['status'] . "\n";
}
?>
