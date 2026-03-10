<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        redirect('../auth/login.php');
    }
}

function checkRole($role) {
    if ($_SESSION['role'] !== $role) {
        redirect('../index.php');
    }
}

function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

function calculateDenda($tanggal_kembali_seharusnya, $tanggal_kembali_aktual) {
    $tgl1 = new DateTime($tanggal_kembali_seharusnya);
    $tgl2 = new DateTime($tanggal_kembali_aktual);
    
    if ($tgl2 <= $tgl1) {
        return 0;
    }
    
    $diff = $tgl2->diff($tgl1);
    $hari = $diff->days;
    return $hari * 1000; // Denda 1000 per hari
}
