<?php
session_start();
require_once('config/database.php');
$stmt = $conn->query("SELECT * FROM pengumuman ORDER BY waktu_buat DESC LIMIT 1");
$pengumuman = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = $conn->query("SELECT * FROM katalog ORDER BY id ASC");
$katalog = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferdi Gym</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar fixed-top" data-aos="fade-down" data-aos-duration="1000">
        <div class="logo">
            <a href="#home">
                <img src="assets/logo.png" alt="Ferdi Gym Logo" class="logo-img">
            </a>
        </div>
        <div class="nav-links">
            <a href="#beranda">Beranda</a>
            <a href="#pengumuman">Pengumuman</a>
            <a href="#katalog">Katalog</a>
            <a href="#pesan" class="pesan-btn">Pesan</a>
        </div>
    </nav>

    <!-- Beranda Section with Carousel -->
    <section id="beranda" data-aos="fade-up" data-aos-duration="1000">
        <div id="carouselFerdiGym" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselFerdiGym" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#carouselFerdiGym" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#carouselFerdiGym" data-bs-slide-to="2"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="assets/1.png" class="d-block w-100" alt="Gym Equipment">
                </div>
                <div class="carousel-item">
                    <img src="assets/2.png" class="d-block w-100" alt="Training Session">
                </div>
                <div class="carousel-item">
                    <img src="assets/3.png" class="d-block w-100" alt="Fitness Class">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselFerdiGym" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselFerdiGym" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- Pengumuman Section -->
    <section id="pengumuman" class="section-padding" data-aos="fade-up" data-aos-duration="1000">
        <div class="container">
            <h2 class="section-title" data-aos="zoom-in">Pengumuman</h2>
            <div class="pengumuman-content">
                <div class="card" data-aos="flip-left" data-aos-delay="200">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($pengumuman['judul'] ?? 'Pengumuman Terbaru') ?></h5>
                        <p class="card-text"><?= nl2br(htmlspecialchars($pengumuman['isi'] ?? 'Belum ada pengumuman.')) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Katalog Section -->
    <section id="katalog" class="section-padding">
        <div class="container">
            <h2 class="section-title" data-aos="zoom-in">Katalog</h2>
            <div class="row">
                <?php foreach($katalog as $item): ?>
                <div class="col-md-3" data-aos="flip-left">
                    <div class="card katalog-card"
                         style="background: linear-gradient(135deg, <?= htmlspecialchars($item['warna'] ?? '#fff') ?> 0%, #fff 100%);">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= htmlspecialchars($item['judul']) ?></h5>
                            <p class="duration"><?= htmlspecialchars($item['durasi']) ?></p>
                            <p class="price">Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                            <ul class="features">
                                <?php foreach(explode("\n", $item['fitur']) as $f): ?>
                                    <li><?= htmlspecialchars($f) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Modal Detail Katalog -->
                <div class="modal fade" id="modalKatalog<?= $item['id'] ?>" tabindex="-1" aria-labelledby="modalKatalogLabel<?= $item['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalKatalogLabel<?= $item['id'] ?>"><?= htmlspecialchars($item['judul']) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Durasi:</strong> <?= htmlspecialchars($item['durasi']) ?></p>
                                <p><strong>Harga:</strong> Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                                <p><strong>Fitur:</strong></p>
                                <ul>
                                    <?php foreach(explode("\n", $item['fitur']) as $f): ?>
                                        <li><?= htmlspecialchars($f) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Pesan Section -->
    <section id="pesan" class="section-padding" data-aos="fade-up">
        <div class="container text-center">
            <h2 class="section-title">Pesan</h2>
            <a href="https://wa.me/62822222222222" class="btn btn-pesan">
                <i class="bi bi-whatsapp"></i> Pesan di Sini
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" data-aos="fade-up">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h3>Kontak Kami</h3>
                    <div class="contact-info">
                        <p><i class="bi bi-clock"></i> Senin-Jumat: 06:00 - 21:00</p>
                        <p><i class="bi bi-geo-alt"></i> Jl Kaliurang KM 14.5, Sleman, DIY, Yogyakarta</p>
                        <p><i class="bi bi-telephone"></i> +62822222222222</p>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-outline-light admin-login-btn" data-bs-toggle="modal" data-bs-target="#loginModal">
                        Login Admin
                    </button>
                </div>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if(isset($_SESSION['login_error'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                            echo $_SESSION['login_error'];
                            unset($_SESSION['login_error']);
                            ?>
                        </div>
                    <?php endif; ?>
                    <form action="admin/proses_login.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Inisialisasi AOS
        AOS.init();

        // Smooth scrolling untuk navigasi
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Animasi untuk navbar saat di-scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });

        // Hover effect untuk katalog cards
        const katalogCards = document.querySelectorAll('.katalog-card');
        katalogCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
                this.style.transition = 'all 0.3s ease';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html> 