<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pelanggan = $_POST['id_pelanggan'];
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];
    
    // Ambil harga dan stok produk
    $produkQuery = $conn->query("SELECT harga, stok FROM produk WHERE id_produk = '$id_produk'");
    $produk = $produkQuery->fetch_assoc();
    $harga_satuan = $produk['harga'];
    $stok = $produk['stok'];
    
    if ($jumlah > $stok) {
        echo "Stok tidak mencukupi!";
    } else {
        $total_harga = $harga_satuan * $jumlah;
        
        // Kurangi stok
        $new_stok = $stok - $jumlah;
        $conn->query("UPDATE produk SET stok = '$new_stok' WHERE id_produk = '$id_produk'");
        
        // Simpan ke tabel penjualan
        $sql = "INSERT INTO penjualan (id_pelanggan, jumlah, total_harga) VALUES ('$id_pelanggan', '$jumlah', '$total_harga')";
        if ($conn->query($sql) === TRUE) {
            $id_penjualan = $conn->insert_id;
            
            // Simpan ke tabel detail_pembelian
            $detailSql = "INSERT INTO detail_penjualan (id_pelanggan, id_penjualan, jumlah, total_harga) VALUES ('$id_pelanggan', '$id_penjualan', '$jumlah', '$total_harga')";
            $conn->query($detailSql);
            
            echo "Pembelian berhasil dan detail pembelian telah ditambahkan!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$pelangganResult = $conn->query("SELECT * FROM pelanggan");
$produkResult = $conn->query("SELECT * FROM produk");

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Halaman Pembelian</title>
    <link rel="stylesheet" href="dashboard.css">
    <script>
        function updateHarga() {
            var selectedProduct = document.getElementById("id_produk").selectedOptions[0];
            var harga = selectedProduct.getAttribute("data-harga");
            document.getElementById("harga_satuan").value = harga;
            hitungTotal();
        }
        
        function hitungTotal() {
            var jumlah = document.getElementById("jumlah").value;
            var harga = document.getElementById("harga_satuan").value;
            document.getElementById("total_harga").value = jumlah * harga;
        }
    </script>
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
    <h2>Form Pembelian</h2>
    <form method="POST">
        <label for="id_pelanggan">Pilih Pelanggan:</label>
        <select name="id_pelanggan">
            <option value="">Pilih Pelanggan</option>
            <?php while ($row = $pelangganResult->fetch_assoc()) { ?>
                <option value='<?php echo $row['id_pelanggan']; ?>'><?php echo $row['nama_pelanggan']; ?></option>
            <?php } ?>
        </select><br>
        
        <label for="id_produk">Pilih Produk:</label>
        <select name="id_produk" id="id_produk" onchange="updateHarga()">
            <option value="">Pilih Produk</option>
            <?php while ($row = $produkResult->fetch_assoc()) { ?>
                <option value='<?php echo $row['id_produk']; ?>' data-harga='<?php echo $row['harga']; ?>'><?php echo $row['nama_barang']; ?></option>
            <?php } ?>
        </select><br>
        
        <label for="harga_satuan">Harga Satuan:</label>
        <input type="text" id="harga_satuan" readonly><br>
        
        <label for="jumlah">Jumlah:</label>
        <input type="number" name="jumlah" id="jumlah" required oninput="hitungTotal()"><br>
        
        <label for="total_harga">Total Harga:</label>
        <input type="text" name="total_harga" id="total_harga" readonly><br>
        
        <button type="submit">Beli</button>
    </form>
</body>
</html>
