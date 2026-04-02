<?php
require_once 'config.php';

$pageTitle = 'Tentang';
$activePage = 'about';

ob_start();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Header -->
            <div class="text-center mb-5 animate__animated animate__fadeInDown">
                <h1 class="fw-bold mb-3">Tentang DLHPKKP Kabupaten Tojo Una-Una</h1>
                <p class="lead text-muted">Dinas Lingkungan Hidup, Perumahan, Kawasan Permukiman dan Pertanahan</p>
            </div>
            
            <!-- Vision & Mission -->
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="card border-0 shadow-lg h-100">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <i class="fas fa-eye fa-3x text-primary"></i>
                            </div>
                            <h3 class="fw-bold mb-3">Visi</h3>
                            <p class="text-muted">
                                Terwujudnya Kabupaten Tojo Una-Una yang asri, layak huni, dan berkelanjutan 
                                dengan pengelolaan lingkungan hidup, perumahan, kawasan permukiman, dan pertanahan 
                                yang profesional dan berorientasi pada pelayanan masyarakat.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card border-0 shadow-lg h-100">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <i class="fas fa-bullseye fa-3x text-success"></i>
                            </div>
                            <h3 class="fw-bold mb-3">Misi</h3>
                            <ul class="text-muted text-start list-unstyled">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Meningkatkan kualitas pengelolaan lingkungan hidup</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Menyediakan perumahan yang layak dan terjangkau</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Mengembangkan kawasan permukiman yang berkelanjutan</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Meningkatkan pelayanan administrasi pertanahan</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></Mendorong partisipasi masyarakat dalam pembangunan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- About System -->
            <div class="card border-0 shadow-lg mb-5">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-4"><i class="fas fa-info-circle me-2"></i>Tentang Sistem Aduan Masyarakat</h2>
                    <p class="text-muted mb-3">
                        Sistem Aduan Masyarakat DLHPKKP Kabupaten Tojo Una-Una adalah platform digital yang dirancang 
                        untuk memudahkan masyarakat dalam menyampaikan aspirasi, keluhan, dan laporan terkait bidang:
                    </p>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-leaf text-primary fa-lg me-3 mt-1"></i>
                                <div>
                                    <strong>Lingkungan Hidup</strong>
                                    <p class="small text-muted mb-0">Pencemaran, sampah, kerusakan lingkungan, dll</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-home text-primary fa-lg me-3 mt-1"></i>
                                <div>
                                    <strong>Perumahan</strong>
                                    <p class="small text-muted mb-0">Perumahan rakyat, hunian layak, dll</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-building text-primary fa-lg me-3 mt-1"></i>
                                <div>
                                    <strong>Kawasan Permukiman</strong>
                                    <p class="small text-muted mb-0">Infrastruktur permukiman, drainase, dll</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-map text-primary fa-lg me-3 mt-1"></i>
                                <div>
                                    <strong>Pertanahan</strong>
                                    <p class="small text-muted mb-0">Sertifikat tanah, batas tanah, sengketa, dll</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Keunggulan Sistem:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Pengajuan aduan mudah dan cepat secara online</li>
                            <li>Token unik untuk melacak status aduan secara real-time</li>
                            <li>Transparansi proses penanganan aduan</li>
                            <li>Respon cepat dari tim DLHPKKP</li>
                            <li>Dokumentasi lengkap setiap tahapan penanganan</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Contact Info -->
            <div class="card border-0 shadow-lg mb-5">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-4"><i class="fas fa-address-card me-2"></i>Informasi Kontak</h2>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <i class="fas fa-map-marker-alt text-primary fa-lg me-3 mt-1"></i>
                                <div>
                                    <strong>Alamat Kantor</strong>
                                    <p class="text-muted mb-0">
                                        Jl. Poros Ampana<br>
                                        Kabupaten Tojo Una-Una<br>
                                        Sulawesi Tengah
                                    </p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-3">
                                <i class="fas fa-phone text-primary fa-lg me-3 mt-1"></i>
                                <div>
                                    <strong>Telepon</strong>
                                    <p class="text-muted mb-0">(0458) 123456</p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start">
                                <i class="fas fa-envelope text-primary fa-lg me-3 mt-1"></i>
                                <div>
                                    <strong>Email</strong>
                                    <p class="text-muted mb-0">info@dlhpkkp-tojounauna.go.id</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <i class="fas fa-clock text-primary fa-lg me-3 mt-1"></i>
                                <div>
                                    <strong>Jam Pelayanan</strong>
                                    <p class="text-muted mb-0">
                                        Senin - Kamis: 08:00 - 16:00 WITA<br>
                                        Jumat: 08:00 - 11:30 WITA<br>
                                        Sabtu - Minggu: Tutup
                                    </p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start">
                                <i class="fas fa-headset text-primary fa-lg me-3 mt-1"></i>
                                <div>
                                    <strong>Layanan Pengaduan</strong>
                                    <p class="text-muted mb-0">
                                        24 Jam melalui sistem online<br>
                                        Respon pada hari kerja
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Call to Action -->
            <div class="text-center py-4" style="background: var(--gradient-primary); border-radius: 16px;">
                <h3 class="fw-bold text-white mb-3">Siap Untuk Berpartisipasi?</h3>
                <p class="text-white opacity-75 mb-4">
                    Mari bersama-sama wujudkan Kabupaten Tojo Una-Una yang lebih baik
                </p>
                <a href="<?= APP_URL ?>submit.php" class="btn btn-light btn-lg px-4">
                    <i class="fas fa-paper-plane me-2"></i>Ajukan Aduan Sekarang
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'templates/header.php';
?>
