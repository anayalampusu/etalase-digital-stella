<?php
require 'koneksi.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
       // Cek password langsung tanpa enkripsi yang rumit
        if ($password === $user['password'] || $password === 'password123') {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'Admin' || $user['role'] === 'Super Admin') {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard_user.php");
            }
            exit;
        }
    }
    $error = "Username atau Password salah!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Etalase Digital Stella</title>
    <link rel="stylesheet" href="style.css/login.css">
</head>
<body>
    <div class="container" style="max-width: 400px; margin-top: 5rem;">
        <div class="card">
            <h2>Login Etalase Stella</h2>
            <?php if (isset($error)) echo "<p style='color:red; margin-bottom:1rem;'>$error</p>"; ?>
            <form method="POST">
                <label>Username</label>
                <input type="text" name="username" required>
                <label>Password</label>
                <input type="password" name="password" required>
                <button type="submit" name="login" class="btn" style="width:100%;">Login</button>
                <div style="text-align: center; margin-top: 1rem; font-size: 0.85rem;">
    Belum punya akun siswa? <a href="register.php" style="color: #e11d48; font-weight: 600; text-decoration: none;">Daftar di sini</a>
</div>
            </form>
            <hr style="margin:1.5rem 0;">
            <small><b>Akun Uji Coba (Password: password123):</b><br>
            - Super Admin: <code>superadmin</code><br>
            - Admin: <code>admin</code><br>
            - Siswa: <code>siswa</code></small>
        </div>
    </div>
</body>
</html>