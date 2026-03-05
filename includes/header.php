<?php
// Ensure session is started in the calling file
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiLib - Sistem Perpustakaan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="brand" style="margin-bottom: 2.5rem;">
            <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">DigiLib</h1>
            <p style="font-size: 0.75rem; color: var(--text-muted);">Library System v1.0</p>
        </div>

        <nav style="display: flex; flex-direction: column; gap: 0.5rem; flex-grow: 1;">
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="dashboard.php" class="btn btn-secondary" style="justify-content: flex-start; border: none; background: <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'var(--background)' : 'transparent' ?>;">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                <a href="buku.php" class="btn btn-secondary" style="justify-content: flex-start; border: none; background: <?= basename($_SERVER['PHP_SELF']) == 'buku.php' ? 'var(--background)' : 'transparent' ?>;">
                    <i class="fa-solid fa-book"></i> Kelola Buku
                </a>
                <a href="anggota.php" class="btn btn-secondary" style="justify-content: flex-start; border: none; background: <?= basename($_SERVER['PHP_SELF']) == 'anggota.php' ? 'var(--background)' : 'transparent' ?>;">
                    <i class="fa-solid fa-users"></i> Data Anggota
                </a>
                <a href="transaksi.php" class="btn btn-secondary" style="justify-content: flex-start; border: none; background: <?= basename($_SERVER['PHP_SELF']) == 'transaksi.php' ? 'var(--background)' : 'transparent' ?>;">
                    <i class="fa-solid fa-right-left"></i> Transaksi
                </a>
            <?php else: ?>
                <a href="dashboard.php" class="btn btn-secondary" style="justify-content: flex-start; border: none; background: <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'var(--background)' : 'transparent' ?>;">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                <a href="katalog.php" class="btn btn-secondary" style="justify-content: flex-start; border: none; background: <?= basename($_SERVER['PHP_SELF']) == 'katalog.php' ? 'var(--background)' : 'transparent' ?>;">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari Buku
                </a>
                <a href="riwayat.php" class="btn btn-secondary" style="justify-content: flex-start; border: none; background: <?= basename($_SERVER['PHP_SELF']) == 'riwayat.php' ? 'var(--background)' : 'transparent' ?>;">
                    <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pinjam
                </a>
            <?php endif; ?>
        </nav>

        <div style="margin-top: auto;">
            <hr style="border: 0; border-top: 1px solid var(--border); margin-bottom: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                    <?= strtoupper(substr($_SESSION['nama'], 0, 1)) ?>
                </div>
                <div>
                    <p style="font-size: 0.875rem; font-weight: 600;"><?= $_SESSION['nama'] ?></p>
                    <p style="font-size: 0.75rem; color: var(--text-muted);"><?= ucfirst($_SESSION['role']) ?></p>
                </div>
            </div>
            <a href="../auth/logout.php" class="btn btn-secondary" style="width: 100%; border-color: var(--danger); color: var(--danger);">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
            </a>
        </div>
    </div>

    <main class="main-content">
        <header class="animate-fade-in" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-weight: 700; font-size: 1.75rem;"><?= $pageTitle ?? 'Dashboard' ?></h2>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button id="themeToggle" class="btn btn-secondary" style="padding: 0.5rem; border-radius: 50%; width: 40px; height: 40px;">
                    <i class="fa-solid fa-moon"></i>
                </button>
                <div style="color: var(--text-muted); font-size: 0.875rem;">
                    <span id="currentDate"><?= date('l, d F Y') ?></span>
                </div>
            </div>
        </header>
