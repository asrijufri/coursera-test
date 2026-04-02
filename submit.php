<?php
require_once 'config.php';

$pageTitle = 'Buat Aduan Baru';
$activePage = 'submit';

// Get categories
try {
    $stmt = $pdo->query("SELECT * FROM categories WHERE is_active = TRUE ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categories = [];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $reporter_name = sanitize($_POST['reporter_name']);
        $reporter_email = sanitize($_POST['reporter_email']);
        $reporter_phone = sanitize($_POST['reporter_phone']);
        $reporter_address = sanitize($_POST['reporter_address']);
        $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
        $title = sanitize($_POST['title']);
        $description = sanitize($_POST['description']);
        $location = sanitize($_POST['location']);
        
        // Validate required fields
        if (empty($reporter_name) || empty($reporter_phone) || empty($reporter_address) || 
            empty($title) || empty($description) || empty($location)) {
            throw new Exception('Semua field wajib diisi!');
        }
        
        // Generate complaint number and token
        $complaint_number = 'ADU-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $token = generateToken();
        
        // Handle file upload
        $attachment = null;
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $upload_result = uploadFile($_FILES['attachment']);
            if ($upload_result['success']) {
                $attachment = $upload_result['filename'];
            }
        }
        
        // Insert complaint
        $stmt = $pdo->prepare("INSERT INTO complaints 
            (complaint_number, reporter_name, reporter_email, reporter_phone, reporter_address, 
             category_id, title, description, location, attachment, token) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $complaint_number, $reporter_name, $reporter_email, $reporter_phone, 
            $reporter_address, $category_id, $title, $description, $location, $attachment, $token
        ]);
        
        // Success message with token
        $success_message = "Aduan berhasil diajukan! Nomor aduan: <strong>$complaint_number</strong><br>";
        $success_message .= "Token pelacakan: <strong>$token</strong><br>";
        $success_message .= "Simpan nomor dan token ini untuk melacak status aduan Anda.";
        
        // Redirect to success page or show success message
        header("Location: " . APP_URL . "success.php?token=" . $token);
        exit;
        
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    } catch (PDOException $e) {
        $error_message = "Terjadi kesalahan saat menyimpan aduan. Silakan coba lagi.";
    }
}

ob_start();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-container animate__animated animate__fadeInUp">
                <div class="text-center mb-4">
                    <h1 class="form-title">Buat Aduan Baru</h1>
                    <p class="form-subtitle">Lengkapi formulir di bawah ini untuk mengajukan aduan</p>
                </div>
                
                <?php if (isset($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?= $error_message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?php if (isset($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= $success_message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="" enctype="multipart/form-data" data-validate>
                    <!-- Data Pelapor -->
                    <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-user me-2"></i>Data Pelapor</h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="reporter_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="reporter_name" name="reporter_name" 
                                   value="<?= htmlspecialchars($_POST['reporter_name'] ?? '') ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="reporter_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="reporter_email" name="reporter_email"
                                   value="<?= htmlspecialchars($_POST['reporter_email'] ?? '') ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="reporter_phone" class="form-label">Nomor Telepon/WA <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="reporter_phone" name="reporter_phone"
                                   value="<?= htmlspecialchars($_POST['reporter_phone'] ?? '') ?>" required 
                                   placeholder="08xxxxxxxxxx">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="reporter_address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="reporter_address" name="reporter_address" rows="2" required><?= htmlspecialchars($_POST['reporter_address'] ?? '') ?></textarea>
                        </div>
                    </div>
                    
                    <!-- Detail Aduan -->
                    <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-file-alt me-2"></i>Detail Aduan</h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Kategori Aduan</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= (($_POST['category_id'] ?? '') == $category['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="location" class="form-label">Lokasi Kejadian <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="location" name="location"
                                   value="<?= htmlspecialchars($_POST['location'] ?? '') ?>" required
                                   placeholder="Contoh: Jl. Poros Ampana, Desa XYZ">
                        </div>
                        
                        <div class="col-12">
                            <label for="title" class="form-label">Judul Aduan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title"
                                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required
                                   placeholder="Ringkasan singkat tentang aduan Anda">
                        </div>
                        
                        <div class="col-12">
                            <label for="description" class="form-label">Uraian Aduan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="5" required 
                                      data-max-length="1000" placeholder="Jelaskan detail aduan Anda secara lengkap"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="col-12">
                            <label for="attachment" class="form-label">Upload Bukti/Foto (Opsional)</label>
                            <input type="file" class="form-control" id="attachment" name="attachment" accept="image/*,.pdf">
                            <div class="form-text">Format yang diterima: JPG, JPEG, PNG, PDF. Maksimal 5MB.</div>
                            <div class="file-preview"></div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Penting:</strong> Pastikan semua data yang Anda masukkan adalah benar. 
                        Setelah mengajukan aduan, Anda akan mendapatkan nomor aduan dan token unik untuk melacak progress aduan.
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="<?= APP_URL ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary px-4" data-loading-text="Mengirim...">
                            <i class="fas fa-paper-plane me-1"></i>Kirim Aduan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'templates/header.php';
?>
