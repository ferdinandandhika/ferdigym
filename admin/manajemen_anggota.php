<?php
session_start();
require_once('../config/database.php');
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Ambil data anggota
$stmt = $conn->query("SELECT * FROM anggota ORDER BY id DESC");
$anggota = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Daftar tier/paket
$tier_list = ['Bronze', 'Silver', 'Gold', 'Platinum'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Anggota</title>
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
            <h1>Manajemen Anggota</h1>
            <div class="header-actions">
                <a href="logout.php" class="btn btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </a>
            </div>
        </header>
        <div class="content-body">
            <!-- Form tambah anggota -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="proses_tambah_anggota.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="notelp" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="notelp" name="notelp" required>
                        </div>
                        <div class="mb-3">
                            <label for="tier" class="form-label">Tier/Paket</label>
                            <select class="form-control" id="tier" name="tier" required>
                                <option value="">Pilih Tier</option>
                                <?php foreach($tier_list as $tier): ?>
                                    <option value="<?= $tier ?>"><?= $tier ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                            <input type="date" class="form-control" id="waktu_mulai" name="waktu_mulai" required>
                        </div>
                        <div class="mb-3">
                            <label for="waktu_berakhir" class="form-label">Waktu Berakhir</label>
                            <input type="date" class="form-control" id="waktu_berakhir" name="waktu_berakhir" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah Anggota</button>
                    </form>
                </div>
            </div>

            <!-- Tabel anggota -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Daftar Anggota</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Foto</th>
                                    <th>No. Telp</th>
                                    <th>Tier</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Berakhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=1; foreach($anggota as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td>
                                        <?php if($row['foto']): ?>
                                            <img src="../uploads/<?= htmlspecialchars($row['foto']) ?>" alt="Foto" width="50">
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['notelp']) ?></td>
                                    <td><?= htmlspecialchars($row['tier']) ?></td>
                                    <td><?= htmlspecialchars($row['waktu_mulai']) ?></td>
                                    <td><?= htmlspecialchars($row['waktu_berakhir']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(count($anggota) == 0): ?>
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada anggota.</td>
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
