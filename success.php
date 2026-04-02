<?php
require_once 'config.php';

$pageTitle = 'Aduan Berhasil Diajukan';
$activePage = '';

// Get complaint data
$complaint = null;
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = sanitize($_GET['token']);
    
    try {
        $stmt = $pdo->prepare("SELECT c.*, cat.name as category_name 
            FROM complaints c 
            LEFT JOIN categories cat ON c.category_id = cat.id 
            WHERE c.token = ?");
        $stmt->execute([$token]);
        $complaint = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Error handling
    }
}

ob_start();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <?php if ($complaint): ?>
            <div class="form-container text-center animate__animated animate__fadeInUp">
                <div class="mb-4">
                    <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <i class="fas fa-check fa-3x text-white"></i>
                    </div>
                </div>
                
                <h1 class="fw-bold mb-3 text-success">Aduan Berhasil Diajukan!</h1>
                <p class="text-muted mb-4">
                    Terima kasih telah berpartisipasi. Aduan Anda telah kami terima dan akan segera diproses.
                </p>
                
                <div class="card bg-light border-0 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Informasi Aduan</h5>
                        
                        <div class="row g-3 text-start">
                            <div class="col-md-6">
                                <label class="small text-muted">Nomor Aduan</label>
                                <div class="d-flex align-items-center">
                                    <strong class="fs-5"><?= htmlspecialchars($complaint['complaint_number']) ?></strong>
                                    <button class="btn btn-sm btn-outline-primary ms-2" 
                                            onclick="navigator.clipboard.writeText('<?= $complaint['complaint_number'] ?>'); Swal.fire('Berhasil!', 'Nomor aduan disalin', 'success');">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="small text-muted">Token Pelacakan</label>
                                <div class="d-flex align-items-center">
                                    <strong class="fs-5"><?= htmlspecialchars($complaint['token']) ?></strong>
                                    <button class="btn btn-sm btn-outline-primary ms-2" 
                                            onclick="navigator.clipboard.writeText('<?= $complaint['token'] ?>'); Swal.fire('Berhasil!', 'Token disalin', 'success');">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="small text-muted">Judul Aduan</label>
                                <p class="mb-0 fw-medium"><?= htmlspecialchars($complaint['title']) ?></p>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="small text-muted">Kategori</label>
                                <p class="mb-0 fw-medium"><?= htmlspecialchars($complaint['category_name'] ?? 'Umum') ?></p>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="small text-muted">Tanggal Pengajuan</label>
                                <p class="mb-0 fw-medium"><?= date('d F Y, H:i', strtotime($complaint['created_at'])) ?> WITA</p>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="small text-muted">Status</label>
                                <span class="badge bg-warning text-dark fs-6">Pending</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info text-start">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Penting!</strong> Simpan nomor aduan dan token di atas untuk melacak progress aduan Anda.
                    Token ini adalah satu-satunya cara untuk mengakses informasi status aduan.
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                    <a href="<?= APP_URL ?>track.php?token=<?= $complaint['token'] ?>" class="btn btn-primary px-4">
                        <i class="fas fa-search me-1"></i>Lacak Aduan
                    </a>
                    <a href="<?= APP_URL ?>" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-home me-1"></i>Kembali ke Beranda
                    </a>
                    <a href="<?= APP_URL ?>submit.php" class="btn btn-outline-primary px-4">
                        <i class="fas fa-plus me-1"></i>Buat Aduan Baru
                    </a>
                </div>
            </div>
            
            <?php else: ?>
            <div class="form-container text-center animate__animated animate__fadeIn">
                <div class="mb-4">
                    <div style="width: 100px; height: 100px; background: #dc3545; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <i class="fas fa-times fa-3x text-white"></i>
                    </div>
                </div>
                
                <h1 class="fw-bold mb-3 text-danger">Data Tidak Ditemukan</h1>
                <p class="text-muted mb-4">
                    Maaf, aduan yang Anda cari tidak ditemukan atau token tidak valid.
                </p>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="<?= APP_URL ?>" class="btn btn-primary px-4">
                        <i class="fas fa-home me-1"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'templates/header.php';
?>
