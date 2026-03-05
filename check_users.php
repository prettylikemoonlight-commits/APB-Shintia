<?php
require_once 'config/koneksi.php';
$stmt = $pdo->query("SELECT id_user, username, nama, role FROM users");
$users = $stmt->fetchAll();
foreach ($users as $u) {
    echo "ID: " . $u['id_user'] . " | Username: " . $u['username'] . " | Role: " . $u['role'] . "\n";
}
?>
