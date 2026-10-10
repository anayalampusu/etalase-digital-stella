<?php
require 'koneksi.php';

$success = "";
$error = "";

if (isset($_POST['register'])) {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // Disimpan langsung teks biasa sesuai skema uji coba
    $role     = 'Siswa'; // Otomatis mendaftar sebagai Siswa

    // Cek apakah username sudah digunakan
    $cek_username = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek_username) > 0) {
        $error = "Username sudah terpakai, silakan gunakan yang lain!";
    } else {
        $query = "INSERT INTO users (nama, username, password, role) VALUES ('$nama', '$username', '$password', '$role')";
        if (mysqli_query($conn, $query)) {
            $success = "Pendaftaran berhasil! Silakan login.";
        } else {
            $error = "Terjadi kesalahan, gagal mendaftar.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Etalase Digital Stella</title>
    <link rel="stylesheet" href="style.css/register.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Daftar Akun Siswa</h2>
            
            <?php if (!empty($error)) echo "<p style='color:#ef4444; font-size:0.85rem; margin-top:0.5rem; text-align:center;'>$error</p>"; ?>
            <?php if (!empty($success)) echo "<p style='color:#10b981; font-size:0.85rem; margin-top:0.5rem; text-align:center;'>$success</p>"; ?>

            <form method="POST">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" required placeholder="Contoh: Budi Santoso">

                <label>Username</label>
                <input type="text" name="username" required placeholder="Contoh: budisiswa">

                <label>Password</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter">

                <button type="submit" name="register" class="btn">Daftar Sekarang</button>
            </form>

            <div class="link-login">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </div>
        </div>
    </div>
</body>
</html>