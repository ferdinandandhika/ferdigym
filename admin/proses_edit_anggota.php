<?php
session_start();
require_once('../config/database.php');
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $notelp = $_POST['notelp'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $waktu_berakhir = $_POST['waktu_berakhir'];
    $deskripsi = $_POST['deskripsi'];
    $katalog_id = $_POST['katalog_id'];

    // Cek apakah upload foto baru
    $foto_name = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_name = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/' . $foto_name);

        // Hapus foto lama jika ada
        $stmt_old = $conn->prepare("SELECT foto FROM anggota WHERE id=?");
        $stmt_old->execute([$id]);
        $old = $stmt_old->fetch(PDO::FETCH_ASSOC);
        if($old && $old['foto'] && file_exists('../uploads/'.$old['foto'])) {
            unlink('../uploads/'.$old['foto']);
        }

        $stmt = $conn->prepare("UPDATE anggota SET nama=?, deskripsi=?, foto=?, notelp=?, katalog_id=?, waktu_mulai=?, waktu_berakhir=? WHERE id=?");
        $stmt->execute([$nama, $deskripsi, $foto_name, $notelp, $katalog_id, $waktu_mulai, $waktu_berakhir, $id]);
    } else {
        $stmt = $conn->prepare("UPDATE anggota SET nama=?, deskripsi=?, notelp=?, katalog_id=?, waktu_mulai=?, waktu_berakhir=? WHERE id=?");
        $stmt->execute([$nama, $deskripsi, $notelp, $katalog_id, $waktu_mulai, $waktu_berakhir, $id]);
    }

    header('Location: manajemen_anggota.php');
    exit();
}

$edit_data = [
    'id' => '',
    'nama' => '',
    'deskripsi' => '',
    'notelp' => '',
    'katalog_id' => '',
    'waktu_mulai' => '',
    'waktu_berakhir' => '',
    'foto' => ''
];
?>
