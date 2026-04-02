<?php
require_once 'config.php';

$pageTitle = 'Lacak Aduan';
$activePage = 'track';

$complaint = null;
$responses = [];
$timeline = [];

// Check if token is provided
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = sanitize($_GET['token']);
    
    try {
        // Get complaint details
        $stmt = $pdo->prepare("SELECT c.*, cat.name as category_name 
            FROM complaints c 
            LEFT JOIN categories cat ON c.category_id = cat.id 
            WHERE c.token = ?");
        $stmt->execute([$token]);
        $complaint = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($complaint) {
            // Get responses
            $stmt = $pdo->prepare("SELECT r.*, u.full_name as responder_name 
                FROM complaint_responses r 
                LEFT JOIN users u ON r.user_id = u.id 
                WHERE r.complaint_id = ? AND r.is_public = TRUE 
                ORDER BY r.created_at ASC");
            $stmt->execute([$complaint['id']]);
            $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get timeline
            $stmt = $pdo->prepare("SELECT t.*, u.full_name as updated_by 
                FROM complaint_timeline t 
                LEFT JOIN users u ON t.user_id = u.id 
                WHERE t.complaint_id = ? 
                ORDER BY t.created_at ASC");
            $stmt->execute([$complaint['id']]);
            $timeline = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        $error_message = "Terjadi kesalahan saat mengambil data aduan.";
    }
}

ob_start();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Search Form -->
            <div class="form-container mb-4 animate__animated animate__fadeInDown">
                <h2 class="fw-bold text-center mb-3"><i class="fas fa-search me-2"></i>Lacak Aduan Anda</h2>
                <p class="text-center text-muted mb-4">Masukkan token yang Anda dapatkan saat mengajukan aduan</p>
                
                <form method="GET" action="" class="d-flex gap-2">
                    <input type="text" name="token" class="form-control form-control-lg" 
                           placeholder="Masukkan token pelacakan" 
                           value="<?= htmlspecialchars($_GET['token'] ?? '') ?>" required>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-search me-1"></i>Cari
                    </button>
                </form>
            </div>
            
            <?php if (isset($complaint) && $complaint): ?>
            <!-- Complaint Details -->
            <div class="animate__animated animate__fadeInUp">
                <div class="card border-0 shadow-lg mb-4">
                    <div class="card-header bg-primary text-white p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h4 class="mb-1"><?= htmlspecialchars($complaint['title']) ?></h4>
                                <p class="mb-0 opacity-75">
                                    <i class="fas fa-hashtag me-1"></i><?= htmlspecialchars($complaint['complaint_number']) ?>
                                </p>
                            </div>
                            <span class="badge bg-<?= getStatusColor($complaint['status']) ?> fs-6 px-3 py-2">
                                <?= ucfirst($complaint['status']) ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-folder text-primary me-2"></i>
                                    <strong>Kategori:</strong>
                                    <span class="ms-2"><?= htmlspecialchars($complaint['category_name'] ?? 'Umum') ?></span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    <strong>Lokasi:</strong>
                                    <span class="ms-2"><?= htmlspecialchars($complaint['location']) ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    <strong>Tanggal:</strong>
                                    <span class="ms-2"><?= date('d F Y, H:i', strtotime($complaint['created_at'])) ?> WITA</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    <strong>Pelapor:</strong>
                                    <span class="ms-2"><?= htmlspecialchars($complaint['reporter_name']) ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h5 class="fw-bold mb-3"><i class="fas fa-comment-alt me-2"></i>Uraian Aduan</h5>
                        <p class="text-muted"><?= nl2br(htmlspecialchars($complaint['description'])) ?></p>
                        
                        <?php if ($complaint['attachment']): ?>
                        <div class="mt-3">
                            <a href="<?= APP_URL ?>uploads/<?= htmlspecialchars($complaint['attachment']) ?>" 
                               class="btn btn-outline-primary btn-sm" target="_blank">
                                <i class="fas fa-download me-1"></i>Lihat Lampiran
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Timeline -->
                <div class="card border-0 shadow-lg mb-4">
                    <div class="card-header bg-light p-3">
                        <h5 class="fw-bold mb-0"><i class="fas fa-history me-2"></i>Timeline Progress</h5>
                    </div>
                    <div class="card-body p-4">
                        <?php if (empty($timeline)): ?>
                        <p class="text-muted text-center mb-0">Belum ada update progress</p>
                        <?php else: ?>
                        <div class="timeline">
                            <?php foreach ($timeline as $item): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker">
                                    <i class="fas fa-<?= $item['new_status'] == 'selesai' ? 'check' : 'clock' ?>"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-date">
                                        <i class="far fa-clock me-1"></i>
                                        <?= date('d F Y, H:i', strtotime($item['created_at'])) ?> WITA
                                        <?php if ($item['updated_by']): ?>
                                        <span class="ms-2">oleh <?= htmlspecialchars($item['updated_by']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="timeline-title">
                                        Status: <span class="badge bg-<?= getStatusColor($item['new_status']) ?>">
                                            <?= ucfirst($item['new_status']) ?>
                                        </span>
                                    </div>
                                    <?php if ($item['notes']): ?>
                                    <p class="mb-0 text-muted"><?= nl2br(htmlspecialchars($item['notes'])) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Responses -->
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-light p-3">
                        <h5 class="fw-bold mb-0"><i class="fas fa-comments me-2"></i>Tanggapan</h5>
                    </div>
                    <div class="card-body p-4">
                        <?php if (empty($responses)): ?>
                        <p class="text-muted text-center mb-0">Belum ada tanggapan dari admin</p>
                        <?php else: ?>
                        <?php foreach ($responses as $response): ?>
                        <div class="border rounded p-3 mb-3 <?= $response['response_type'] == 'update_status' ? 'bg-info bg-opacity-10' : '' ?>">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>
                                    <i class="fas fa-user-circle me-1"></i>
                                    <?= htmlspecialchars($response['responder_name'] ?? 'Admin') ?>
                                </strong>
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i>
                                    <?= date('d M Y, H:i', strtotime($response['created_at'])) ?> WITA
                                </small>
                            </div>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($response['message'])) ?></p>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <?php elseif (isset($_GET['token']) && empty($complaint)): ?>
            <div class="alert alert-warning text-center animate__animated animate__fadeIn">
                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                <h5>Aduan Tidak Ditemukan</h5>
                <p>Token yang Anda masukkan tidak valid atau aduan sudah tidak tersedia.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'templates/header.php';
?>
