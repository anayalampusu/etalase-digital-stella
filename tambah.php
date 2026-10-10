<?php
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $user_id = $_SESSION['user_id'];
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $link_pendukung = mysqli_real_escape_string($conn, $_POST['link_pendukung']);

    // Handle Upload Gambar
    $gambar = $_FILES['gambar']['name'];
    $tmpName = $_FILES['gambar']['tmp_name'];
    $dir = "uploads/";

    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    if ($gambar) {
        $namaFileBaru = uniqid() . '-' . basename($gambar);
        move_uploaded_file($tmpName, $dir . $namaFileBaru);
    } else {
        $namaFileBaru = "";
    }

    $query = "INSERT INTO projects (user_id, judul, deskripsi, gambar, link_pendukung) VALUES ('$user_id', '$judul', '$deskripsi', '$namaFileBaru', '$link_pendukung')";
    if (mysqli_query($conn, $query)) {
        // Redirect kembali ke dashboard sesuai role
        if ($_SESSION['role'] === 'Siswa') {
            header("Location: dashboard_user.php");
        } else {
            header("Location: dashboard_admin.php");
        }
        exit;
    } else {
        $error = "Gagal menyimpan data project.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Project - Etalase Digital Stella</title>
    <link rel="stylesheet" href="style.css/tambah.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <div class="card">
            <h2>Tambah Project Baru</h2>
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <form method="POST" enctype="multipart/form-data">
                <label>Judul Project</label>
                <input type="text" name="judul" required>

                <label>Deskripsi Project</label>
                <textarea name="deskripsi" rows="5" required></textarea>

                <label>Gambar Preview</label>
                <input type="file" name="gambar" accept="image/*" required>

                <label>Link Pendukung (GitHub / Demo URL)</label>
                <input type="url" name="link_pendukung" placeholder="https://...">

                <button type="submit" name="simpan" class="btn">Simpan Project</button>
                <a href="<?= ($_SESSION['role'] === 'Siswa') ? 'dashboard_user.php' : 'dashboard_admin.php' ?>" class="btn btn-danger" style="background: #6b7280; margin-left: 0.5rem;">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>