<?php
require_once __DIR__ . '/../config/koneksi.php';
function_exists('redirect') ?: require_once __DIR__ . '/../config/functions.php';

if (isset($_SESSION['user_id'])) {
    redirect('../index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $kelas = $_POST['kelas'];
    $role = 'user';

    // Check if username exists
    $stmt = $pdo->prepare("SELECT id_user FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $error = "Username sudah digunakan!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (nama, username, password, role, kelas) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$nama, $username, $password, $role, $kelas])) {
            $success = "Registrasi berhasil! Silakan login.";
        } else {
            $error = "Terjadi kesalahan.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - DigiLib</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            background: linear-gradient(45deg, #ec4899, #6366f1);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            width: 100%;
            max-width: 450px;
            padding: 2rem;
            animation: fadeIn 0.8s ease;
        }
    </style>
</head>
<body>
    <div class="register-card glass glass-border animate-fade-in" style="border-radius: var(--radius-lg);">
        <h2 style="color: white; text-align: center; margin-bottom: 1.5rem;">Daftar Akun</h2>

        <?php if ($error): ?>
            <div style="background: rgba(239, 68, 68, 0.2); color: #fee2e2; padding: 0.75rem; border-radius: var(--radius-md); margin-bottom: 1rem; font-size: 0.875rem; text-align: center;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div style="background: rgba(16, 185, 129, 0.2); color: #d1fae5; padding: 0.75rem; border-radius: var(--radius-md); margin-bottom: 1rem; font-size: 0.875rem; text-align: center;">
                <?= $success ?>
                <br><a href="login.php" style="color: white; text-decoration: underline;">Login di sini</a>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label class="form-label" style="color: white;">Nama Lengkap</label>
                <input type="text" name="nama" class="form-input" placeholder="Masukkan nama lengkap" required autocomplete="off">
            </div>
            <div class="form-group">
                <label class="form-label" style="color: white;">Kelas</label>
                <input type="text" name="kelas" class="form-input" placeholder="Contoh: XII RPL 1" required autocomplete="off">
            </div>
            <div class="form-group">
                <label class="form-label" style="color: white;">Username</label>
                <input type="text" name="username" class="form-input" placeholder="Pilih username" required autocomplete="off">
            </div>
            <div class="form-group">
                <label class="form-label" style="color: white;">Password</label>
                <input type="password" name="password" class="form-input" placeholder="Buat password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Daftar Sekarang</button>
        </form>

        <p style="text-align: center; margin-top: 1.5rem; color: white; font-size: 0.875rem;">
            Sudah punya akun? <a href="login.php" style="color: white; font-weight: 600; text-decoration: none;">Login</a>
        </p>
    </div>
</body>
</html>
