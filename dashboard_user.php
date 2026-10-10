<?php
require 'koneksi.php';

// Cek apakah user sudah login dan benar-benar berperan sebagai Siswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Siswa') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$nama_user = $_SESSION['nama'];

// Proses Logout
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
    <title>Dashboard Siswa - Etalase Digital Stella</title>
    <link rel="stylesheet" href="style.css/dashboard.css">
</head>
<body>
    <header style="background: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h2>Etalase Digital Stella (Siswa)</h2>
        <div>
            <span>Halo, <b><?= htmlspecialchars($nama_user) ?></b></span>
            <a href="?logout=true" class="btn btn-danger" style="margin-left: 1rem;">Logout</a>
        </div>
    </header>

    <div class="container">
        <div style="margin-bottom: 1.5rem; display: flex; gap: 1rem;">
            <a href="tambah.php" class="btn">+ Tambah Project Baru</a>
        </div>

        <h2>Project Karya Saya</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Project</th>
                    <th>Deskripsi</th>
                    <th>Gambar Preview</th>
                    <th>Link</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                $query = mysqli_query($conn, "SELECT * FROM projects WHERE user_id = '$user_id' ORDER BY id DESC");
                if (mysqli_num_rows($query) > 0):
                    while($row = mysqli_fetch_assoc($query)):
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><b><?= htmlspecialchars($row['judul']) ?></b></td>
                    <td><?= htmlspecialchars(substr($row['deskripsi'], 0, 60)) ?>...</td>
                    <td>
                        <?php if(!empty($row['gambar'])): ?>
                            <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" width="80" style="border-radius: 4px;">
                        <?php else: ?>
                            <small>Tidak ada gambar</small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if(!empty($row['link_pendukung'])): ?>
                            <a href="<?= htmlspecialchars($row['link_pendukung']) ?>" target="_blank">🔗 Tautan</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-delete" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Hapus</a>
                    </td>
                </tr>
                <?php 
                    endwhile; 
                else:
                ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2rem;">Belum ada project yang Anda unggah.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="js/script.js"></script>
</body>
</html>