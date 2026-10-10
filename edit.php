<?php
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? '';
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Validasi hak akses: Jika siswa, pastikan project yang diedit adalah miliknya sendiri
if ($role === 'Siswa') {
    $result = mysqli_query($conn, "SELECT * FROM projects WHERE id = '$id' AND user_id = '$user_id'");
} else {
    $result = mysqli_query($conn, "SELECT * FROM projects WHERE id = '$id'");
}

$p = mysqli_fetch_assoc($result);
if (!$p) {
    echo "Data project tidak ditemukan atau Anda tidak memiliki hak akses.";
    exit;
}

if (isset($_POST['update'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $link_pendukung = mysqli_real_escape_string($conn, $_POST['link_pendukung']);

    $gambar = $_FILES['gambar']['name'];
    $tmpName = $_FILES['gambar']['tmp_name'];
    $dir = "uploads/";

    if ($gambar) {
        $namaFileBaru = uniqid() . '-' . basename($gambar);
        move_uploaded_file($tmpName, $dir . $namaFileBaru);
        // Hapus gambar lama jika ada
        if (!empty($p['gambar']) && file_exists($dir . $p['gambar'])) {
            unlink($dir . $p['gambar']);
        }
        $query = "UPDATE projects SET judul='$judul', deskripsi='$deskripsi', gambar='$namaFileBaru', link_pendukung='$link_pendukung' WHERE id='$id'";
    } else {
        $query = "UPDATE projects SET judul='$judul', deskripsi='$deskripsi', link_pendukung='$link_pendukung' WHERE id='$id'";
    }

    if (mysqli_query($conn, $query)) {
        if ($role === 'Siswa') {
            header("Location: dashboard_user.php");
        } else {
            header("Location: dashboard_admin.php");
        }
        exit;
    } else {
        $error = "Gagal memperbarui data.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Project - Etalase Digital Stella</title>
    <link rel="stylesheet" href="style.css/edit.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <div class="card">
            <h2>Edit Project</h2>
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <form method="POST" enctype="multipart/form-data">
                <label>Judul Project</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($p['judul']) ?>" required>

                <label>Deskripsi Project</label>
                <textarea name="deskripsi" rows="5" required><?= htmlspecialchars($p['deskripsi']) ?></textarea>

                <label>Gambar Preview (Biarkan kosong jika tidak ingin diubah)</label>
                <?php if(!empty($p['gambar'])): ?>
                    <div style="margin-bottom: 0.5rem;"><img src="uploads/<?= htmlspecialchars($p['gambar']) ?>" width="100"></div>
                <?php endif; ?>
                <input type="file" name="gambar" accept="image/*">

                <label>Link Pendukung</label>
                <input type="url" name="link_pendukung" value="<?= htmlspecialchars($p['link_pendukung']) ?>">

                <button type="submit" name="update" class="btn">Update Project</button>
                <a href="<?= ($role === 'Siswa') ? 'dashboard_user.php' : 'dashboard_admin.php' ?>" class="btn btn-danger" style="background: #6b7280; margin-left: 0.5rem;">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>