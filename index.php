<?php
require_once 'config.php';

$pageTitle = 'Beranda';
$activePage = 'home';

// Get statistics
try {
    $stmt = $pdo->query("SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'proses' THEN 1 ELSE 0 END) as proses,
        SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai
        FROM complaints");
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $stats = ['total' => 0, 'pending' => 0, 'proses' => 0, 'selesai' => 0];
}

// Get categories
try {
    $stmt = $pdo->query("SELECT * FROM categories WHERE is_active = TRUE ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categories = [];
}

// Get recent complaints
try {
    $stmt = $pdo->query("SELECT c.*, cat.name as category_name 
        FROM complaints c 
        LEFT JOIN categories cat ON c.category_id = cat.id 
        ORDER BY c.created_at DESC LIMIT 5");
    $recentComplaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $recentComplaints = [];
}

ob_start();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 animate__animated animate__fadeInLeft">
                <h1 class="hero-title">Sistem Aduan Masyarakat DLHPKKP</h1>
                <p class="hero-subtitle">
                    Dinas Lingkungan Hidup, Perumahan, Kawasan Permukiman dan Pertanahan 
                    Kabupaten Tojo Una-Una
                </p>
                <p class="lead mb-4" style="opacity: 0.9;">
                    Salurkan aspirasi dan aduan Anda untuk pembangunan daerah yang lebih baik. 
                    Kami siap melayani dan menindaklanjuti setiap laporan masyarakat.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= APP_URL ?>submit.php" class="btn btn-light btn-lg px-4 py-3 fw-bold">
                        <i class="fas fa-plus-circle me-2"></i>Buat Aduan Baru
                    </a>
                    <a href="<?= APP_URL ?>track.php" class="btn btn-outline-light btn-lg px-4 py-3">
                        <i class="fas fa-search me-2"></i>Lacak Aduan
                    </a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block animate__animated animate__fadeInRight">
                <div class="text-center">
                    <i class="fas fa-city" style="font-size: 15rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="stats-card" data-target="<?= $stats['total'] ?>">
                    <div class="stats-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stats-number" data-target="<?= $stats['total'] ?>"><?= $stats['total'] ?></div>
                    <div class="stats-label">Total Aduan</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card">
                    <div class="stats-icon" style="background: var(--gradient-secondary);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-number" style="color: var(--secondary-color);"><?= $stats['pending'] ?></div>
                    <div class="stats-label">Menunggu</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card">
                    <div class="stats-icon" style="background: var(--gradient-accent);">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="stats-number" style="color: var(--accent-color);"><?= $stats['proses'] ?></div>
                    <div class="stats-label">Diproses</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card">
                    <div class="stats-icon" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-number" style="color: #28a745;"><?= $stats['selesai'] ?></div>
                    <div class="stats-label">Selesai</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Kategori Aduan</h2>
            <p class="text-muted">Pilih kategori sesuai dengan jenis aduan Anda</p>
        </div>
        
        <div class="row g-4">
            <?php foreach ($categories as $category): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="category-card" onclick="window.location='<?= APP_URL ?>submit.php?category=<?= $category['id'] ?>'">
                    <div class="category-icon">
                        <i class="fas <?= $category['icon'] ?? 'fa-tag' ?>"></i>
                    </div>
                    <h4 class="category-title"><?= htmlspecialchars($category['name']) ?></h4>
                    <p class="category-description"><?= htmlspecialchars($category['description']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Cara Mengajukan Aduan</h2>
            <p class="text-muted">Ikuti langkah-langkah berikut untuk mengajukan aduan</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <div class="position-relative mb-3">
                    <div style="width: 80px; height: 80px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <span class="text-white fw-bold" style="font-size: 2rem;">1</span>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Isi Formulir</h5>
                <p class="text-muted">Lengkapi formulir aduan dengan data yang benar dan lengkap</p>
            </div>
            
            <div class="col-md-3 text-center">
                <div class="position-relative mb-3">
                    <div style="width: 80px; height: 80px; background: var(--gradient-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <span class="text-white fw-bold" style="font-size: 2rem;">2</span>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Upload Bukti</h5>
                <p class="text-muted">Unggah foto atau dokumen pendukung sebagai bukti aduan</p>
            </div>
            
            <div class="col-md-3 text-center">
                <div class="position-relative mb-3">
                    <div style="width: 80px; height: 80px; background: var(--gradient-accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <span class="text-white fw-bold" style="font-size: 2rem;">3</span>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Dapatkan Token</h5>
                <p class="text-muted">Simpan token unik untuk melacak status aduan Anda</p>
            </div>
            
            <div class="col-md-3 text-center">
                <div class="position-relative mb-3">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <span class="text-white fw-bold" style="font-size: 2rem;">4</span>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Pantau Progress</h5>
                <p class="text-muted">Lacak perkembangan aduan Anda secara real-time</p>
            </div>
        </div>
    </div>
</section>

<!-- Recent Complaints -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Aduan Terbaru</h2>
            <a href="<?= APP_URL ?>complaints.php" class="btn btn-outline-primary">
                Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        
        <?php if (empty($recentComplaints)): ?>
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">Belum ada aduan</p>
        </div>
        <?php else: ?>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <?php foreach ($recentComplaints as $complaint): ?>
                <div class="complaint-card">
                    <div class="complaint-header">
                        <span class="complaint-number">
                            <i class="fas fa-hashtag me-1"></i><?= htmlspecialchars($complaint['complaint_number']) ?>
                        </span>
                        <span class="complaint-status status-<?= $complaint['status'] ?>">
                            <?= ucfirst($complaint['status']) ?>
                        </span>
                    </div>
                    <h4 class="complaint-title"><?= htmlspecialchars($complaint['title']) ?></h4>
                    <div class="complaint-meta">
                        <span><i class="fas fa-folder"></i> <?= htmlspecialchars($complaint['category_name'] ?? 'Umum') ?></span>
                        <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($complaint['location']) ?></span>
                        <span><i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($complaint['created_at'])) ?></span>
                    </div>
                    <p class="text-muted mb-0"><?= htmlspecialchars(substr($complaint['description'], 0, 150)) ?>...</p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5" style="background: var(--gradient-primary);">
    <div class="container text-center text-white">
        <h2 class="fw-bold mb-3">Siap Untuk Mengajukan Aduan?</h2>
        <p class="lead mb-4">Partisipasi Anda sangat berarti untuk pembangunan Kabupaten Tojo Una-Una yang lebih baik</p>
        <a href="<?= APP_URL ?>submit.php" class="btn btn-light btn-lg px-5 py-3 fw-bold">
            <i class="fas fa-paper-plane me-2"></i>Ajukan Aduan Sekarang
        </a>
    </div>
</section>

<?php
$content = ob_get_clean();
include 'templates/header.php';
?>
