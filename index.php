<?php
require_once __DIR__ . '/config/functions.php';

if (!isset($_SESSION['user_id'])) {
    redirect('auth/login.php');
} else {
    if ($_SESSION['role'] === 'admin') {
        redirect('admin/dashboard.php');
    } else {
        redirect('user/dashboard.php');
    }
}
?>
