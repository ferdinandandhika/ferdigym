<?php
session_start();
require_once('../config/database.php');
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $notelp = $_POST['notelp'];
    $tier = $_POST['tier'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $waktu_berakhir = $_POST['waktu_berakhir'];

    // Upload foto
    $foto_name = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_name = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/' . $foto_name);
    }

    $stmt = $conn->prepare("INSERT INTO anggota (nama, foto, notelp, tier, waktu_mulai, waktu_berakhir) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nama, $foto_name, $notelp, $tier, $waktu_mulai, $waktu_berakhir]);

    header('Location: manajemen_anggota.php');
    exit();
}
?>
