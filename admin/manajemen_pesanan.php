<?php
session_start();
require_once('../config/database.php');
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Ambil data pemesanan
$stmt = $conn->query("SELECT * FROM pemesanan ORDER BY waktu_buat DESC");
$pemesanan = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil data anggota untuk dropdown
$stmt_anggota = $conn->query("SELECT nama FROM anggota ORDER BY nama ASC");
$anggota_list = $stmt_anggota->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/admin.css" rel="stylesheet">
</head>
<body>
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Dashboard</h2>
        </div>
        <nav class="sidebar-nav">
            <ul>
            <li>
                    <a href="dashboard.php">
                        <i class="bi bi-house"></i>
                        Dashboard
                    </a>
                </li>
               <li>
                    <a href="manajemen_anggota.php">
                        <i class="bi bi-people"></i>
                        Manajemen Anggota
                    </a>
                </li>
                <li>
                    <a href="manajemen_pesanan.php">
                        <i class="bi bi-cart"></i>
                        Manajemen Pesanan
                    </a>
                </li>
                <li>
                    <a href="manajemen_katalog.php">
                    <i class="bi bi-card-checklist"></i>
                        Manajemen Katalog
                    </a>
                </li>
                <li>
                    <a href="manajemen_pengumuman.php">
                        <i class="bi bi-megaphone"></i>
                        Manajemen Pengumuman
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header class="content-header">
            <h1>Manajemen Pemesanan</h1>
            <div class="header-actions">
                <a href="logout.php" class="btn btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </a>
            </div>
        </header>
        <div class="content-body">
            <!-- Form tambah pemesanan -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="proses_tambah_pesanan.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nama_pemesan" class="form-label">Nama Pemesan</label>
                            <select class="form-control" id="nama_pemesan" name="nama_pemesan" required>
                                <option value="">Pilih Nama Anggota</option>
                                <?php foreach($anggota_list as $anggota): ?>
                                    <option value="<?= htmlspecialchars($anggota['nama']) ?>">
                                        <?= htmlspecialchars($anggota['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="bukti" class="form-label">Bukti Pemesanan (Gambar)</label>
                            <input type="file" class="form-control" id="bukti" name="bukti" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah Pemesanan</button>
                    </form>
                </div>
            </div>

            <!-- Tabel pemesanan -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Daftar Pemesanan</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pemesan</th>
                                    <th>Bukti Pemesanan</th>
                                    <th>Deskripsi</th>
                                    <th>Waktu Buat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=1; foreach($pemesanan as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama_pemesan']) ?></td>
                                    <td>
                                        <?php if($row['bukti']): ?>
                                            <img src="../uploads/<?= htmlspecialchars($row['bukti']) ?>" alt="Bukti" width="70">
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                                    <td><?= htmlspecialchars($row['waktu_buat']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(count($pemesanan) == 0): ?>
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada pemesanan.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
