<?php
require_once 'config/koneksi.php';
$stmt = $pdo->query("SELECT t.id_transaksi, t.status, u.username FROM transaksi t JOIN users u ON t.id_user = u.id_user");
$rows = $stmt->fetchAll();
foreach ($rows as $row) {
    echo "ID: {$row['id_transaksi']} | Status: {$row['status']} | User: {$row['username']}\n";
}
?>
