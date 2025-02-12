<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Tambah Produk
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah'])) {
    $nama_barang = $_POST['nama_barang'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    
    $stmt = $conn->prepare("INSERT INTO produk (nama_barang, stok, harga) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama_barang, $stok, $harga);
    $stmt->execute();
    header("Location: produk.php");
    exit();
}

// Edit Produk
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $id_produk = $_POST['id_produk'];
    $nama_barang = $_POST['nama_barang'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    
    $stmt = $conn->prepare("UPDATE produk SET nama_barang = ?, stok = ?, harga = ? WHERE id_produk = ?");
    $stmt->bind_param("sssi", $nama_barang, $stok, $harga, $id_produk);
    $stmt->execute();
    header("Location: produk.php");
    exit();
}

// Hapus Produk
if (isset($_GET['hapus'])) {
    $id_produk = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM produk WHERE id_produk = ?");
    $stmt->bind_param("i", $id_produk);
    $stmt->execute();
    header("Location: produk.php");
    exit();
}

// Ambil Data Produk
$result = $conn->query("SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk</title>
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
    <h2>Kelola Produk</h2>
    <form method="POST">
        <input type="hidden" name="id_produk" id="id_produk">
        <label>Nama Barang:</label>
        <input type="text" name="nama_barang" id="nama_barang" required><br>
        <label>Stok:</label>
        <input type="text" name="stok" id="stok" required><br>
        <label>Harga:</label>
        <input type="text" name="harga" id="harga" required><br>
        <button type="submit" name="tambah">Tambah Produk</button>
        <button type="submit" name="edit">Simpan Perubahan</button>
    </form>
    
    <h3>Daftar Produk</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama Barang</th>
            <th>Stok</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
        <tr>
            <td><?php echo $row['id_produk']; ?></td>
            <td><?php echo $row['nama_barang']; ?></td>
            <td><?php echo $row['stok']; ?></td>
            <td><?php echo $row['harga']; ?></td>
            <td>
                <a href="#" onclick="editProduk('<?php echo $row['id_produk']; ?>', '<?php echo $row['nama_barang']; ?>', '<?php echo $row['stok']; ?>', '<?php echo $row['harga']; ?>')">Edit</a> |
                <a href="produk.php?hapus=<?php echo $row['id_produk']; ?>" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editProduk(id, nama, stok, harga) {
            document.getElementById('id_produk').value = id;
            document.getElementById('nama_barang').value = nama;
            document.getElementById('stok').value = stok;
            document.getElementById('harga').value = harga;
        }
    </script>
</body>
</html>
