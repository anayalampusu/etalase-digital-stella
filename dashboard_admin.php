<?php
require 'koneksi.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Super Admin')) {
    header("Location: login.php");
    exit;
}

$nama_user = $_SESSION['nama'];
$role_user = $_SESSION['role'];

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Etalase Digital Stella</title>
    <link rel="stylesheet" href="style.css/dashboard.css">
</head>
<body>
    <header style="background: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h2>Etalase Digital Stella (<?= htmlspecialchars($role_user) ?>)</h2>
        <div>
            <span>Halo, <b><?= htmlspecialchars($nama_user) ?></b></span>
            <a href="?logout=true" class="btn btn-danger" style="margin-left: 1rem;">Logout</a>
        </div>
    </header>

    <div class="container">
        <div style="margin-bottom: 1.5rem;">
            <a href="tambah.php" class="btn">+ Tambah Project</a>
        </div>

        <h2>Kelola Seluruh Project Siswa</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Project</th>
                    <th>Pemilik (Siswa)</th>
                    <th>Deskripsi</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                $query = mysqli_query($conn, "SELECT projects.*, users.nama FROM projects JOIN users ON projects.user_id = users.id ORDER BY projects.id DESC");
                while($row = mysqli_fetch_assoc($query)):
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><b><?= htmlspecialchars($row['judul']) ?></b></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars(substr($row['deskripsi'], 0, 50)) ?>...</td>
                    <td>
                        <?php if(!empty($row['gambar'])): ?>
                            <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" width="70" style="border-radius: 4px;">
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-delete" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="js/script.js"></script>
</body>
</html>