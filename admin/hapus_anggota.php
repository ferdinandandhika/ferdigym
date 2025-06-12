<?php
session_start();
require_once('../config/database.php');
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Hapus foto dari server
    $stmt = $conn->prepare("SELECT foto FROM anggota WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row && $row['foto'] && file_exists('../uploads/'.$row['foto'])) {
        unlink('../uploads/'.$row['foto']);
    }
    // Hapus data anggota
    $stmt = $conn->prepare("DELETE FROM anggota WHERE id=?");
    $stmt->execute([$id]);
}
header('Location: manajemen_anggota.php');
exit();
?>
