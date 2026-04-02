<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Sistem Aduan Masyarakat' ?> - DLHPKKP Kabupaten Tojo Una-Una</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= APP_URL ?>assets/css/style.css">
    
    <link rel="icon" type="image/png" href="<?= APP_URL ?>assets/images/favicon.png">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top animate__animated animate__fadeInDown">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= APP_URL ?>">
                <div class="brand-logo me-2">
                    <i class="fas fa-leaf fa-2x"></i>
                </div>
                <div class="brand-text">
                    <span class="d-block fw-bold">DLHPKKP</span>
                    <small class="d-none d-md-block" style="font-size: 0.7rem;">Kabupaten Tojo Una-Una</small>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= ($activePage ?? '') == 'home' ? 'active' : '' ?>" href="<?= APP_URL ?>">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($activePage ?? '') == 'complaints' ? 'active' : '' ?>" href="<?= APP_URL ?>complaints.php">
                            <i class="fas fa-list me-1"></i> Daftar Aduan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($activePage ?? '') == 'track' ? 'active' : '' ?>" href="<?= APP_URL ?>track.php">
                            <i class="fas fa-search me-1"></i> Lacak Aduan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($activePage ?? '') == 'about' ? 'active' : '' ?>" href="<?= APP_URL ?>about.php">
                            <i class="fas fa-info-circle me-1"></i> Tentang
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-primary btn-submit" href="<?= APP_URL ?>submit.php">
                            <i class="fas fa-plus-circle me-1"></i> Buat Aduan
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand mb-3">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-leaf fa-2x me-2 text-primary"></i>
                            <div>
                                <h5 class="mb-0 fw-bold">DLHPKKP</h5>
                                <small>Kabupaten Tojo Una-Una</small>
                            </div>
                        </div>
                        <p class="text-muted small">
                            Sistem Aduan Masyarakat untuk pelayanan yang lebih baik dalam bidang Lingkungan Hidup, Perumahan, Kawasan Permukiman dan Pertanahan.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <h6 class="fw-bold mb-3">Kontak Kami</h6>
                    <ul class="list-unstyled contact-list">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                            <span>Jl. Poros Ampana, Kabupaten Tojo Una-Una, Sulawesi Tengah</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone me-2 text-primary"></i>
                            <span>(0458) 123456</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            <span>info@dlhpkkp-tojounauna.go.id</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock me-2 text-primary"></i>
                            <span>Senin - Jumat: 08:00 - 16:00 WITA</span>
                        </li>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <h6 class="fw-bold mb-3">Tautan Cepat</h6>
                    <ul class="list-unstyled quick-links">
                        <li><a href="<?= APP_URL ?>"><i class="fas fa-chevron-right me-2"></i>Beranda</a></li>
                        <li><a href="<?= APP_URL ?>complaints.php"><i class="fas fa-chevron-right me-2"></i>Daftar Aduan</a></li>
                        <li><a href="<?= APP_URL ?>submit.php"><i class="fas fa-chevron-right me-2"></i>Buat Aduan Baru</a></li>
                        <li><a href="<?= APP_URL ?>track.php"><i class="fas fa-chevron-right me-2"></i>Lacak Aduan</a></li>
                        <li><a href="<?= APP_URL ?>admin/login.php"><i class="fas fa-chevron-right me-2"></i>Login Admin</a></li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4 border-secondary">
            
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 small text-muted">
                        &copy; <?= date('Y') ?> DLHPKKP Kabupaten Tojo Una-Una. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="social-links">
                        <a href="#" class="me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="me-2"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= APP_URL ?>assets/js/main.js"></script>
    
    <?php if (isset($additionalScripts)): ?>
        <?php foreach ($additionalScripts as $script): ?>
            <script src="<?= APP_URL . $script ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
