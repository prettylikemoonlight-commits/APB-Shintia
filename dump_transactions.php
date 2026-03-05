<?php
require_once 'config/koneksi.php';
$stmt = $pdo->query("SELECT * FROM transaksi");
$transactions = $stmt->fetchAll();
foreach ($transactions as $t) {
    print_r($t);
}
?>
