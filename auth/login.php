<?php
require_once __DIR__ . '/../config/koneksi.php';
function_exists('redirect') ?: require_once __DIR__ . '/../config/functions.php';

if (isset($_SESSION['user_id'])) {
    redirect('../index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        redirect('../index.php');
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DigiLib</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            background: linear-gradient(45deg, #6366f1, #ec4899);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            animation: fadeIn 0.8s ease;
        }
        .brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .brand h1 {
            color: white;
            font-size: 2rem;
            font-weight: 700;
        }
        .brand p {
            color: rgba(255, 255, 255, 0.8);
        }
    </style>
</head>
<body>
    <div class="login-card glass glass-border animate-fade-in" style="border-radius: var(--radius-lg);">
        <div class="brand">
            <h1>DigiLib</h1>
            <p>Sistem Perpustakaan Digital</p>
        </div>

        <?php if ($error): ?>
            <div style="background: rgba(239, 68, 68, 0.2); color: #fee2e2; padding: 0.75rem; border-radius: var(--radius-md); margin-bottom: 1rem; font-size: 0.875rem; text-align: center;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label class="form-label" style="color: white;">Username</label>
                <input type="text" name="username" class="form-input" placeholder="Masukkan username" required autocomplete="off">
            </div>
            <div class="form-group">
                <label class="form-label" style="color: white;">Password</label>
                <input type="password" name="password" class="form-input" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Login Sekarang</button>
        </form>

        <p style="text-align: center; margin-top: 1.5rem; color: white; font-size: 0.875rem;">
            Belum punya akun? <a href="register.php" style="color: white; font-weight: 600; text-decoration: none;">Daftar</a>
        </p>
    </div>
</body>
</html>
