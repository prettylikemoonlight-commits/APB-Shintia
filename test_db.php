<?php
require 'c:/xampp/htdocs/APB-shin/config/koneksi.php';
try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Connected to database: " . $db . "\n";
    echo "Tables: " . implode(", ", $tables) . "\n";
    
    $stmt = $pdo->query("SELECT count(*) FROM users");
    echo "User count: " . $stmt->fetchColumn() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
