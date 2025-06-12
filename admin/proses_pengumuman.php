<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$judul = $_POST['judul'];
$isi = $_POST['isi'];

// Cek apakah sudah ada pengumuman, update jika ada, insert jika belum
$stmt = $conn->query("SELECT id FROM pengumuman ORDER BY waktu_buat DESC LIMIT 1");
$pengumuman = $stmt->fetch(PDO::FETCH_ASSOC);

if ($pengumuman) {
    // Update pengumuman terbaru
    $update = $conn->prepare("UPDATE pengumuman SET judul=?, isi=?, waktu_buat=NOW() WHERE id=?");
    $update->execute([$judul, $isi, $pengumuman['id']]);
} else {
    // Insert pengumuman baru
    $insert = $conn->prepare("INSERT INTO pengumuman (judul, isi) VALUES (?, ?)");
    $insert->execute([$judul, $isi]);
}

header('Location: manajemen_pengumuman.php');
exit();