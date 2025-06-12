<?php
session_start();
require_once('../config/database.php');
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pemesan = $_POST['nama_pemesan'];
    $deskripsi = $_POST['deskripsi'];

    // Upload bukti gambar
    $bukti_name = '';
    if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] == 0) {
        $ext = pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION);
        $bukti_name = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['bukti']['tmp_name'], '../uploads/' . $bukti_name);
    }

    $stmt = $conn->prepare("INSERT INTO pemesanan (nama_pemesan, bukti, deskripsi) VALUES (?, ?, ?)");
    $stmt->execute([$nama_pemesan, $bukti_name, $deskripsi]);

    header('Location: manajemen_pesanan.php');
    exit();
}
?>
