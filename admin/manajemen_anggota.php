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

// Ambil data katalog untuk dropdown
$stmt_katalog = $conn->query("SELECT id, judul FROM katalog ORDER BY judul ASC");
$katalog_list = $stmt_katalog->fetchAll(PDO::FETCH_ASSOC);

$edit_mode = false;
$edit_data = [
    'id' => '',
    'nama' => '',
    'deskripsi' => '',
    'notelp' => '',
    'tier' => '',
    'waktu_mulai' => '',
    'waktu_berakhir' => '',
    'foto' => '',
    'katalog_id' => ''
];

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = intval($_GET['edit']);
    $stmt_edit = $conn->prepare("SELECT * FROM anggota WHERE id = ?");
    $stmt_edit->execute([$id]);
    $edit_data = $stmt_edit->fetch(PDO::FETCH_ASSOC);
}
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
                    <form action="<?= $edit_mode ? 'proses_edit_anggota.php' : 'proses_tambah_anggota.php' ?>" method="POST" enctype="multipart/form-data">
                        <?php if($edit_mode): ?>
                            <input type="hidden" name="id" value="<?= htmlspecialchars($edit_data['id']) ?>">
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required value="<?= htmlspecialchars($edit_data['nama']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="2"><?= htmlspecialchars($edit_data['deskripsi']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto <?= $edit_mode && $edit_data['foto'] ? '(Kosongkan jika tidak ingin ganti)' : '' ?></label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*" <?= $edit_mode ? '' : 'required' ?> onchange="previewFoto(event)">
                            <br>
                            <img id="preview-foto" src="<?= $edit_mode && $edit_data['foto'] ? '../uploads/'.htmlspecialchars($edit_data['foto']) : '#' ?>" alt="Preview Foto" style="max-width:100px;<?= $edit_mode && $edit_data['foto'] ? '' : 'display:none;' ?>">
                        </div>
                        <div class="mb-3">
                            <label for="notelp" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="notelp" name="notelp" required value="<?= htmlspecialchars($edit_data['notelp']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="katalog_id" class="form-label">Paket Katalog</label>
                            <select class="form-control" id="katalog_id" name="katalog_id" required>
                                <option value="">Pilih Paket Katalog</option>
                                <?php foreach($katalog_list as $katalog): ?>
                                    <option value="<?= $katalog['id'] ?>"
                                        <?= (isset($edit_data['katalog_id']) && $edit_data['katalog_id'] == $katalog['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($katalog['judul']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                            <input type="date" class="form-control" id="waktu_mulai" name="waktu_mulai" required value="<?= htmlspecialchars($edit_data['waktu_mulai']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="waktu_berakhir" class="form-label">Waktu Berakhir</label>
                            <input type="date" class="form-control" id="waktu_berakhir" name="waktu_berakhir" required value="<?= htmlspecialchars($edit_data['waktu_berakhir']) ?>">
                        </div>
                        <button type="submit" class="btn btn-primary"><?= $edit_mode ? 'Edit' : 'Tambah' ?> Anggota</button>
                        <?php if($edit_mode): ?>
                            <a href="manajemen_anggota.php" class="btn btn-secondary ms-2">Batal</a>
                        <?php endif; ?>
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
                                    <th>Deskripsi</th>
                                    <th>No. Telp</th>
                                    <th>Paket Katalog</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Berakhir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=1; foreach($anggota as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td>
                                        <?php if($row['foto']): ?>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalFoto<?= $row['id'] ?>">
                                                <img src="data:image/jpeg;base64,<?= base64_encode($row['foto']) ?>" alt="Foto" width="70" style="cursor:pointer;">
                                            </a>
                                            <!-- Modal Preview Foto -->
                                            <div class="modal fade" id="modalFoto<?= $row['id'] ?>" tabindex="-1" aria-labelledby="modalFotoLabel<?= $row['id'] ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center">
                                                            <img src="data:image/jpeg;base64,<?= base64_encode($row['foto']) ?>" alt="Foto" style="max-width:100%;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">Tidak ada foto</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                                    <td><?= htmlspecialchars($row['notelp']) ?></td>
                                    <td>
                                        <?php
                                        $katalog_nama = '';
                                        foreach($katalog_list as $katalog) {
                                            if($katalog['id'] == $row['katalog_id']) {
                                                $katalog_nama = $katalog['judul'];
                                                break;
                                            }
                                        }
                                        echo htmlspecialchars($katalog_nama);
                                        ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['waktu_mulai']) ?></td>
                                    <td><?= htmlspecialchars($row['waktu_berakhir']) ?></td>
                                    <td>
                                        <a href="manajemen_anggota.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="hapus_anggota.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus anggota ini?')">Delete</a>
                                    </td>
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
<script>
function previewFoto(event) {
    const [file] = event.target.files;
    if (file) {
        const preview = document.getElementById('preview-foto');
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    }
}
</script>
</body>
</html>
