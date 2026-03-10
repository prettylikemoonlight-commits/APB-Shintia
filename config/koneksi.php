<?php
// Database configuration using environment variables for hosting

$dbUrl = getenv('DATABASE_URL') ?: getenv('MYSQL_URL');

if ($dbUrl) {
    // Vercel / Aiven typically provides a DATABASE_URL
    $url = parse_url($dbUrl);
    $host = $url['host'];
    $port = isset($url['port']) ? $url['port'] : '3306';
    $user = $url['user'];
    $pass = isset($url['pass']) ? $url['pass'] : '';
    $db   = ltrim($url['path'], '/');
} else {
    // Local fallback to XAMPP defaults
    $host = getenv('DB_HOST') ?: 'localhost';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: '';
    $db   = getenv('DB_NAME') ?: 'db_perpustakaan';
    $port = getenv('DB_PORT') ?: '3306';
}

try {
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    // For Aiven MySQL, SSL is required. This applies if host is remote.
    if ($host !== 'localhost' && defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT')) {
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        // Depending on configuration, sometimes this is needed:
        // $options[PDO::MYSQL_ATTR_SSL_CA] = '/path/to/ca.pem';
    }

    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, $options);
    
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
