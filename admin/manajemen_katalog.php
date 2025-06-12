<?php
session_start();
require_once('../config/database.php');
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Cek apakah sedang edit
$edit_mode = false;
$edit_data = [
    'id' => '',
    'judul' => '',
    'durasi' => '',
    'harga' => '',
    'fitur' => '',
    'warna' => '#ffffff'
];

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = intval($_GET['edit']);
    $stmt_edit = $conn->prepare("SELECT * FROM katalog WHERE id = ?");
    $stmt_edit->execute([$id]);
    $edit_data = $stmt_edit->fetch(PDO::FETCH_ASSOC);
}

// Ambil data katalog
$stmt = $conn->query("SELECT * FROM katalog ORDER BY id DESC");
$katalog = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Katalog</title>
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
                    <a href="dashboard.php"><i class="bi bi-house"></i> Dashboard</a>
                </li>
                <li>
                    <a href="manajemen_anggota.php"><i class="bi bi-people"></i> Manajemen Anggota</a>
                </li>
                <li>
                    <a href="manajemen_pesanan.php"><i class="bi bi-cart"></i> Manajemen Pesanan</a>
                </li>
                <li class="active">
                    <a href="manajemen_katalog.php"><i class="bi bi-card-checklist"></i> Manajemen Katalog</a>
                </li>
                <li>
                    <a href="manajemen_pengumuman.php"><i class="bi bi-megaphone"></i> Manajemen Pengumuman</a>
                </li>
            </ul>
        </nav>
    </div>
    <!-- Main Content -->
    <div class="main-content">
        <header class="content-header">
            <h1>Manajemen Katalog</h1>
            <div class="header-actions">
                <a href="logout.php" class="btn btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </header>
        <div class="content-body">
            <!-- Form tambah/edit katalog -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="<?= $edit_mode ? 'proses_edit_katalog.php' : 'proses_tambah_katalog.php' ?>" method="POST">
                        <?php if($edit_mode): ?>
                            <input type="hidden" name="id" value="<?= htmlspecialchars($edit_data['id']) ?>">
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="judul" class="form-label">Nama Paket</label>
                            <input type="text" class="form-control" id="judul" name="judul" required value="<?= htmlspecialchars($edit_data['judul']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="durasi" class="form-label">Durasi</label>
                            <input type="text" class="form-control" id="durasi" name="durasi" required value="<?= htmlspecialchars($edit_data['durasi']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga" required value="<?= htmlspecialchars($edit_data['harga']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="fitur" class="form-label">Fitur</label>
                            <textarea class="form-control" id="fitur" name="fitur" rows="3" required><?= htmlspecialchars($edit_data['fitur']) ?></textarea>
                            <div class="form-text">Pisahkan setiap fitur dengan baris baru.</div>
                        </div>
                        <div class="mb-3">
                            <label for="warna" class="form-label">Warna Gradasi</label>
                            <input type="color" class="form-control form-control-color" id="warna" name="warna"
                                value="<?= isset($edit_data['warna']) ? htmlspecialchars($edit_data['warna']) : '#ffffff' ?>">
                        </div>
                        <button type="submit" class="btn btn-primary"><?= $edit_mode ? 'Edit' : 'Tambah' ?></button>
                        <?php if($edit_mode): ?>
                            <a href="manajemen_katalog.php" class="btn btn-secondary ms-2">Batal</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <!-- Tabel katalog -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Daftar Katalog</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Paket</th>
                                    <th>Durasi</th>
                                    <th>Harga</th>
                                    <th>Fitur</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($katalog as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['judul']) ?></td>
                                    <td><?= htmlspecialchars($item['durasi']) ?></td>
                                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                    <td>
                                        <ul>
                                        <?php foreach(explode("\n", $item['fitur']) as $f): ?>
                                            <li><?= htmlspecialchars($f) ?></li>
                                        <?php endforeach; ?>
                                        </ul>
                                    </td>
                                    <td>
                                        <a href="manajemen_katalog.php?edit=<?= $item['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="hapus_katalog.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(count($katalog) == 0): ?>
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada katalog.</td>
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