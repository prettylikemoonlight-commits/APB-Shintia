<?php
// Database configuration using environment variables for hosting
// Local fallback to XAMPP defaults
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db   = getenv('DB_NAME') ?: 'db_perpustakaan';
$port = getenv('DB_PORT') ?: '3306';

try {
    // Aiven and some remote DBs might require SSL. 
    // We add SSL options which will be ignored if not provided by the platform.
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, $options);
    
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>

