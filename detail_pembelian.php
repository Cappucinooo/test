<?php
require 'koneksi.php';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pembelian</title>
</head>
<body>
    <h2>Detail Pembelian</h2>
    <a href="pembelian.php">Tambah Pembelian</a>
    <button onclick="window.print()">Print Laporan</button>

    <table border="1">
        <thead>
            <tr>
                <th>ID Detail</th>
                <th>ID Penjualan</th>
                <th>Total Harga</th>
                <th>Tanggal Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query_detail = mysqli_query($conn, "SELECT * FROM detail_penjualan ORDER BY tanggal_penjualan DESC");
            if (mysqli_num_rows($query_detail) > 0) {
                while ($row = mysqli_fetch_assoc($query_detail)) {
                    echo "<tr>
                            <td>{$row['id_detail_penjualan']}</td>
                            <td>{$row['id_penjualan']}</td>
                            <td>Rp. ".number_format($row['total_harga'], 0, ',', '.')."</td>
                            <td>{$row['tanggal_penjualan']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Belum ada transaksi</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
