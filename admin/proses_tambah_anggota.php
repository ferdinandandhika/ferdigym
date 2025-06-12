<?php
session_start();
require_once('../config/database.php');
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$nama = $_POST['nama'];
$deskripsi = $_POST['deskripsi'];
$notelp = $_POST['notelp'];
$waktu_mulai = $_POST['waktu_mulai'];
$waktu_berakhir = $_POST['waktu_berakhir'];
$katalog_id = $_POST['katalog_id'];

// Proses upload foto (jika ada)
$foto_data = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $foto_data = file_get_contents($_FILES['foto']['tmp_name']);
}

$stmt = $conn->prepare("INSERT INTO anggota (nama, deskripsi, foto, notelp, waktu_mulai, waktu_berakhir, katalog_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$nama, $deskripsi, $foto_data, $notelp, $waktu_mulai, $waktu_berakhir, $katalog_id]);
header('Location: manajemen_anggota.php');
exit();
?>
