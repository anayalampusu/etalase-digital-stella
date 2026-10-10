<?php
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? '';
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Validasi Hak Akses Hapus
if ($role === 'Siswa') {
    // Siswa hanya boleh menghapus project miliknya sendiri
    $res = mysqli_query($conn, "SELECT * FROM projects WHERE id = '$id' AND user_id = '$user_id'");
    $p = mysqli_fetch_assoc($res);
    if ($p) {
        if (!empty($p['gambar']) && file_exists("uploads/" . $p['gambar'])) {
            unlink("uploads/" . $p['gambar']);
        }
        mysqli_query($conn, "DELETE FROM projects WHERE id = '$id' AND user_id = '$user_id'");
    }
    header("Location: dashboard_user.php");
    exit;
} else {
    // Admin / Super Admin bebas menghapus project apa saja
    $res = mysqli_query($conn, "SELECT * FROM projects WHERE id = '$id'");
    $p = mysqli_fetch_assoc($res);
    if ($p) {
        if (!empty($p['gambar']) && file_exists("uploads/" . $p['gambar'])) {
            unlink("uploads/" . $p['gambar']);
        }
        mysqli_query($conn, "DELETE FROM projects WHERE id = '$id'");
    }
    header("Location: dashboard_admin.php");
    exit;
}
?>