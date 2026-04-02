<?php
require_once 'config.php';

$pageTitle = 'Daftar Aduan';
$activePage = 'complaints';

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search and filter
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? sanitize($_GET['status']) : '';
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Build query
$where_clause = [];
$params = [];

if (!empty($search)) {
    $where_clause[] = "(c.title LIKE ? OR c.description LIKE ? OR c.complaint_number LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($status_filter)) {
    $where_clause[] = "c.status = ?";
    $params[] = $status_filter;
}

if ($category_filter > 0) {
    $where_clause[] = "c.category_id = ?";
    $params[] = $category_filter;
}

$where_sql = !empty($where_clause) ? "WHERE " . implode(" AND ", $where_clause) : "";

try {
    // Get total count
    $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM complaints c $where_sql");
    $count_stmt->execute($params);
    $total = $count_stmt->fetchColumn();
    $total_pages = ceil($total / $limit);
    
    // Get complaints
    $stmt = $pdo->prepare("SELECT c.*, cat.name as category_name 
        FROM complaints c 
        LEFT JOIN categories cat ON c.category_id = cat.id 
        $where_sql
        ORDER BY c.created_at DESC 
        LIMIT $limit OFFSET $offset");
    $stmt->execute($params);
    $complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get categories for filter
    $stmt = $pdo->query("SELECT * FROM categories WHERE is_active = TRUE ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $complaints = [];
    $categories = [];
    $total = 0;
    $total_pages = 0;
}

ob_start();
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold mb-3"><i class="fas fa-list me-2"></i>Daftar Aduan Masyarakat</h1>
            <p class="text-muted">Lihat semua aduan yang masuk dan status penanganannya</p>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" action="" class="row g-3">
                <div class="col-md-4">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari aduan..." 
                               value="<?= htmlspecialchars($search) ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" <?= $status_filter == 'pending' ? 'selected' : '' ?>>Menunggu</option>
                        <option value="proses" <?= $status_filter == 'proses' ? 'selected' : '' ?>>Diproses</option>
                        <option value="selesai" <?= $status_filter == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                        <option value="ditolak" <?= $status_filter == 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $category_filter == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Complaints List -->
    <?php if (empty($complaints)): ?>
    <div class="text-center py-5">
        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">Belum ada aduan</h5>
        <p class="text-muted">Jadilah yang pertama mengajukan aduan</p>
        <a href="<?= APP_URL ?>submit.php" class="btn btn-primary mt-3">
            <i class="fas fa-plus-circle me-1"></i>Buat Aduan
        </a>
    </div>
    <?php else: ?>
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <?php foreach ($complaints as $complaint): ?>
            <div class="complaint-card" data-category="<?= $complaint['category_id'] ?>">
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
                <p class="text-muted mb-3"><?= htmlspecialchars(substr($complaint['description'], 0, 200)) ?>...</p>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="fas fa-user me-1"></i><?= htmlspecialchars($complaint['reporter_name']) ?>
                    </small>
                    <a href="<?= APP_URL ?>track.php?token=<?= $complaint['token'] ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye me-1"></i>Lihat Detail
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($status_filter) ? '&status=' . $status_filter : '' ?><?= $category_filter > 0 ? '&category=' . $category_filter : '' ?>">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
            
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($status_filter) ? '&status=' . $status_filter : '' ?><?= $category_filter > 0 ? '&category=' . $category_filter : '' ?>">
                    <?= $i ?>
                </a>
            </li>
            <?php endfor; ?>
            
            <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($status_filter) ? '&status=' . $status_filter : '' ?><?= $category_filter > 0 ? '&category=' . $category_filter : '' ?>">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'templates/header.php';
?>
