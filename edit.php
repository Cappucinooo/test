<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM pelanggan WHERE id_pelanggan=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id_pelanggan'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $kontak = $_POST['kontak'];
    $conn->query("UPDATE pelanggan SET nama_pelanggan='$nama_pelanggan', alamat='$alamat', kontak='$kontak' WHERE id_pelanggan=$id");
    header("Location: pelanggan.php");
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pelanggan</title>
</head>
<body>
    <h2>Edit Pelanggan</h2>
    <form method="post" action="">
        <input type="hidden" name="id_pelanggan" value="<?php echo $row['id_pelanggan']; ?>">
        <label for="nama_pelanggan">Nama:</label>
        <input type="text" name="nama_pelanggan" value="<?php echo $row['nama_pelanggan']; ?>" required>
        <label for="alamat">Alamat:</label>
        <input type="text" name="alamat" value="<?php echo $row['alamat']; ?>" required>
        <label for="kontak">Kontak:</label>
        <input type="text" name="kontak" value="<?php echo $row['kontak']; ?>" required>
        <input type="submit" name="update" value="Update">
    </form>
</body>
</html>
