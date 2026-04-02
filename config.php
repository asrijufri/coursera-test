<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'dlhpkkp_tojo_unauna');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application settings
define('APP_NAME', 'Sistem Aduan Masyarakat DLHPKKP');
define('APP_FULL_NAME', 'Dinas Lingkungan Hidup, Perumahan, Kawasan Permukiman dan Pertanahan Kabupaten Tojo Una-Una');
define('APP_URL', 'http://localhost/aduan-masyarakat/');

// Upload settings
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf']);

// Session settings
ini_set('session.cookie_httponly', 1);
session_start();

// Database connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Helper functions
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function generateToken() {
    return bin2hex(random_bytes(32));
}

function uploadFile($file) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Gagal mengupload file'];
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_EXTENSIONS)) {
        return ['success' => false, 'message' => 'Tipe file tidak diizinkan'];
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar'];
    }
    
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $destination = UPLOAD_DIR . $filename;
    
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => false, 'message' => 'Gagal menyimpan file'];
    }
    
    return ['success' => true, 'filename' => $filename];
}

function getStatusColor($status) {
    $colors = [
        'pending' => 'warning',
        'proses' => 'info',
        'selesai' => 'success',
        'ditolak' => 'danger'
    ];
    return $colors[$status] ?? 'secondary';
}
?>
