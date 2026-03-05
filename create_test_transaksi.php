<?php
require_once 'config/koneksi.php';
// Insert a dummy transaction for user ID 5 and book ID 5
$stmt = $pdo->prepare("INSERT INTO transaksi (id_user, id_buku, tanggal_pinjam, status) VALUES (5, 5, '2026-03-01', 'dipinjam')");
$stmt->execute();
echo "Inserted transaction ID: " . $pdo->lastInsertId() . "\n";
?>
