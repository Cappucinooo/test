<?php
require 'koneksi.php';

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan'])) {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $kontak = $_POST['kontak'];
    $sql = "INSERT INTO pelanggan (nama_pelanggan, alamat, kontak) VALUES ('$nama_pelanggan', '$alamat', '$kontak')";
    $conn->query($sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM pelanggan WHERE id_pelanggan=$id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id_pelanggan'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $kontak = $_POST['kontak'];
    $conn->query("UPDATE pelanggan SET nama_pelanggan='$nama_pelanggan', alamat='$alamat', kontak='$kontak' WHERE id_pelanggan=$id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$sql = "SELECT * FROM pelanggan";
$result = $conn->query($sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Pelanggan</title>
    <link rel="stylesheet" href="dashboard.css">
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
    <h2>Tambah Pelanggan</h2>
    <form method="post" action="">
        <label for="nama_pelanggan">Nama:</label>
        <input type="text" name="nama_pelanggan" required>
        <label for="alamat">Alamat:</label>
        <input type="text" name="alamat" required>
        <label for="kontak">Kontak:</label>
        <input type="text" name="kontak" required>
        <input type="submit" name="simpan" value="Simpan">
    </form>

    <h2>Daftar Pelanggan</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Kontak</th>
            <th>Aksi</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["id_pelanggan"]; ?></td>
                    <td><?php echo $row["nama_pelanggan"]; ?></td>
                    <td><?php echo $row["alamat"]; ?></td>
                    <td><?php echo $row["kontak"]; ?></td>
                    <td>
                        <a href="?hapus=<?php echo $row['id_pelanggan']; ?>" onclick="return confirm('Hapus data?');">Hapus</a>
                        <a href="edit.php?id=<?php echo $row['id_pelanggan']; ?>">Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">Tidak ada data</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>