<?php
require 'koneksi.php'; // Pastikan file ini berisi koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    
    if (!empty($username) && !empty($password) && !empty($role)) {
        // Remove password hashing
        $sql = "INSERT INTO user (username, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $role);
        
        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            echo "<script>alert('Registrasi berhasil!'); window.location.href='login.php';</script>";
            exit();
        } else {
            echo "<script>alert('Registrasi gagal! Silakan coba lagi.'); window.location.href='register.php';</script>";
        }
        
        $stmt->close();
    } else {
        echo "<script>alert('Harap isi semua bidang!'); window.location.href='register.php';</script>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <label>Role:</label>
        <select name="role" required>
            <option value="Administrator">Administrator</option>
            <option value="Petugas">Petugas</option>
        </select><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
