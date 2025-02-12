<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'koneksi.php';

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Navbar</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .navbar {
            background-color: #333;
            overflow: hidden;
            padding: 10px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            display: inline-block;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="pelanggan.php">Pelanggan</a>
        <?php if ($role == 'Administrator') { ?>
        <a href="register.php">Buat Akun</a>
        <?php } ?>
        <a href="produk.php">Produk</a>
        <a href="pembelian.php">Pembelian</a>
        <a href="detail_pembelian.php">Laporan</a>
        <a href="logout.php" style="float:right">Logout</a>
    </div>
</body>
</html>
