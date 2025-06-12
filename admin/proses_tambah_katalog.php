<?php
session_start();
require_once('../config/database.php');
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $durasi = $_POST['durasi'];
    $harga = $_POST['harga'];
    $fitur = $_POST['fitur'];
    $warna = $_POST['warna'];
    $stmt = $conn->prepare("INSERT INTO katalog (judul, durasi, harga, fitur, warna) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$judul, $durasi, $harga, $fitur, $warna]);
    header('Location: manajemen_katalog.php');
    exit();
}
?>
