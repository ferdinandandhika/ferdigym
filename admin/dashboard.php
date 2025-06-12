<?php
session_start();
require_once('../config/database.php');
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Ambil pengumuman terbaru
$stmt = $conn->query("SELECT * FROM pengumuman ORDER BY waktu_buat DESC LIMIT 1");
$pengumuman = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pengumuman</title>
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
                <h1>Manajemen Pengumuman</h1>
                <div class="header-actions">
                    <a href="logout.php" class="btn btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </a>
                </div>
            </header>
            <div class="content-body">
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="proses_pengumuman.php" method="POST">
                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul Pengumuman</label>
                                <input type="text" class="form-control" id="judul" name="judul" required value="<?= isset($pengumuman['judul']) ? htmlspecialchars($pengumuman['judul']) : '' ?>">
                            </div>
                            <div class="mb-3">
                                <label for="isi" class="form-label">Isi Pengumuman</label>
                                <textarea class="form-control" id="isi" name="isi" rows="4" required><?= isset($pengumuman['isi']) ? htmlspecialchars($pengumuman['isi']) : '' ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Pengumuman</button>
                        </form>
                    </div>
                </div>
                <?php if($pengumuman): ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($pengumuman['judul']) ?></h5>
                        <p class="card-text"><?= nl2br(htmlspecialchars($pengumuman['isi'])) ?></p>
                        <small class="text-muted">Dibuat pada: <?= $pengumuman['waktu_buat'] ?></small>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>