<?php
// Include configuration file
require_once 'config.php';

// Check if user is logged in
if (!$user->isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Get all categories
$categories = $category->getAllCategories();

// Initialize variables
$error = '';
$success = '';
$formData = [
    'category' => '',
    'title' => '',
    'description' => '',
    'condition' => '',
    'price' => '',
    'location' => '',
    'contactName' => '',
    'contactPhone' => '',
    'nego' => ''
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and extract form data
    $formData = [
        'category' => trim($_POST['category'] ?? ''),
        'title' => trim($_POST['title'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'condition' => trim($_POST['condition'] ?? ''),
        'price' => trim($_POST['price'] ?? ''),
        'location' => trim($_POST['location'] ?? ''),
        'contactName' => trim($_POST['contactName'] ?? ''),
        'contactPhone' => trim($_POST['contactPhone'] ?? ''),
        'nego' => isset($_POST['nego'])
    ];
    
    // Validation
    if (empty($formData['category'])) {
        $error = 'Silakan pilih kategori';
    } elseif (empty($formData['title'])) {
        $error = 'Judul iklan wajib diisi';
    } elseif (strlen($formData['title']) < 10) {
        $error = 'Judul iklan minimal 10 karakter';
    } elseif (strlen($formData['title']) > 150) {
        $error = 'Judul iklan maksimal 150 karakter';
    } elseif (empty($formData['description'])) {
        $error = 'Deskripsi iklan wajib diisi';
    } elseif (strlen($formData['description']) < 20) {
        $error = 'Deskripsi iklan minimal 20 karakter';
    } elseif (empty($formData['condition'])) {
        $error = 'Silakan pilih kondisi barang';
    } elseif (empty($formData['price']) || !is_numeric($formData['price']) || $formData['price'] <= 0) {
        $error = 'Harga harus berupa angka yang valid';
    } elseif (empty($formData['location'])) {
        $error = 'Lokasi wajib diisi';
    } elseif (empty($formData['contactName'])) {
        $error = 'Nama kontak wajib diisi';
    } elseif (strlen($formData['contactName']) < 3) {
        $error = 'Nama kontak minimal 3 karakter';
    } elseif (empty($formData['contactPhone'])) {
        $error = 'No. HP/WhatsApp wajib diisi';
    } else {
        // Handle image upload
        $uploadedImages = [];
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $files = $_FILES['images'];
            $fileCount = count($files['name']);
            
            for ($i = 0; $i < $fileCount; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $fileName = $files['name'][$i];
                    $fileTmpName = $files['tmp_name'][$i];
                    $fileSize = $files['size'][$i];
                    $fileError = $files['error'][$i];
                    
                    // Validate file
                    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    $maxSize = 5 * 1024 * 1024; // 5MB
                    
                    if (!in_array($files['type'][$i], $allowedTypes)) {
                        $error = 'Hanya file gambar (JPG, PNG, GIF) yang diperbolehkan';
                        break;
                    }
                    
                    if ($fileSize > $maxSize) {
                        $error = 'Ukuran file maksimal 5MB';
                        break;
                    }
                    
                    // Generate unique filename
                    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                    $newFileName = 'ad_' . time() . '_' . uniqid() . '.' . $fileExtension;
                    $uploadPath = UPLOAD_PATH . $newFileName;
                    
                    // Move file to upload directory
                    if (move_uploaded_file($fileTmpName, $uploadPath)) {
                        $uploadedImages[] = $newFileName;
                    } else {
                        $error = 'Gagal mengupload gambar';
                        break;
                    }
                }
            }
        }
        
        if (empty($error)) {
            // Create ad in database
            $userId = $_SESSION['user_id'];
            $categoryId = $formData['category'];
            $title = $formData['title'];
            $description = $formData['description'];
            $price = floatval($formData['price']);
            $location = $formData['location'];
            
            $adId = $ad->createAd($userId, $categoryId, $title, $description, $price, $location);
            
            if ($adId) {
                // Upload images to database
                foreach ($uploadedImages as $imagePath) {
                    $adImage->addImage($adId, $imagePath);
                }
                
                $success = 'Iklan berhasil dipublikasikan! Mengalihkan...';
                header('Refresh: 2; URL=index.php');
            } else {
                $error = 'Terjadi kesalahan saat menyimpan iklan. Silakan coba lagi.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasang Iklan - KF OLX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
        }
        .post-ad-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 0;
        }
        .post-ad-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
            border: none;
        }
        .form-control, .form-select {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-post-ad {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s;
        }
        .btn-post-ad:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        .image-upload-area {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #fafafa;
        }
        .image-upload-area:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }
        .image-upload-area.dragover {
            border-color: #667eea;
            background: #f0f4ff;
        }
        .image-preview {
            position: relative;
            margin-bottom: 10px;
            border-radius: 8px;
            overflow: hidden;
        }
        .image-preview img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .image-preview .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(220, 53, 69, 0.8);
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }
        .step::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #ddd;
            z-index: 1;
        }
        .step:last-child::after {
            display: none;
        }
        .step.active .step-circle {
            background: #667eea;
            color: white;
        }
        .step.completed .step-circle {
            background: #28a745;
            color: white;
        }
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ddd;
            color: #666;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
        }
        .step-label {
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
        .step.active .step-label {
            color: #667eea;
            font-weight: 600;
        }
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .category-item {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .category-item:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }
        .category-item.selected {
            border-color: #667eea;
            background: #f0f4ff;
        }
        .category-icon {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #667eea;
        }
        .price-input-group {
            position: relative;
        }
        .price-input-group .input-group-text {
            background: transparent;
            border-right: none;
            color: #666;
        }
        .price-input-group .form-control {
            border-left: none;
        }
        .character-count {
            font-size: 12px;
            color: #6c757d;
            text-align: right;
            margin-top: 5px;
        }
        .tips-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        .tips-section h6 {
            color: #667eea;
            margin-bottom: 15px;
        }
        .tips-section ul {
            font-size: 14px;
            color: #666;
        }
        .alert-custom {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index.php">
                <i class="fas fa-shopping-bag"></i> KF OLX
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="fas fa-plus-circle"></i> Pasang Iklan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-heart"></i> Favorit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php"><i class="fas fa-user"></i> Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white px-3" href="#">Daftar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">Pasang Iklan Baru</h1>
            <p class="lead">Jual barang Anda dengan cepat dan mudah</p>
        </div>
    </section>

    <!-- Post Ad Section -->
    <section class="py-5">
        <div class="container">
            <div class="post-ad-container">
                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step active" id="step1">
                        <div class="step-circle">1</div>
                        <div class="step-label">Kategori</div>
                    </div>
                    <div class="step" id="step2">
                        <div class="step-circle">2</div>
                        <div class="step-label">Detail Iklan</div>
                    </div>
                    <div class="step" id="step3">
                        <div class="step-circle">3</div>
                        <div class="step-label">Foto</div>
                    </div>
                    <div class="step" id="step4">
                        <div class="step-circle">4</div>
                        <div class="step-label">Preview</div>
                    </div>
                </div>

                <!-- Post Ad Form -->
                <div class="post-ad-card">
                    <!-- Error/Success Messages -->
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-custom">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success alert-custom">
                            <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" enctype="multipart/form-data">
                        <!-- Step 1: Category Selection -->
                        <div id="step1Content" class="step-content">
                            <h3 class="mb-4">Pilih Kategori</h3>
                            
                            <div class="category-grid">
                                <?php foreach ($categories as $cat): ?>
                                    <div class="category-item" data-category="<?php echo $cat['id']; ?>">
                                        <div class="category-icon">
                                            <i class="<?php echo $cat['icon'] ?? 'fas fa-tag'; ?>"></i>
                                        </div>
                                        <h6><?php echo htmlspecialchars($cat['name']); ?></h6>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="text-end mt-4">
                                <button type="button" class="btn btn-primary btn-post-ad" onclick="nextStep()">
                                    Lanjut <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Ad Details -->
                        <div id="step2Content" class="step-content" style="display: none;">
                            <h3 class="mb-4">Detail Iklan</h3>
                            
                            <!-- Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Iklan *</label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       placeholder="Contoh: iPhone 13 Pro Max 256GB" maxlength="150" 
                                       value="<?php echo htmlspecialchars($formData['title']); ?>" required>
                                <div class="character-count">
                                    <span id="titleCount"><?php echo strlen($formData['title']); ?></span>/150 karakter
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi *</label>
                                <textarea class="form-control" id="description" name="description" rows="5" 
                                          placeholder="Jelaskan kondisi barang, kelengkapan, alasan jual, dll..." maxlength="1000" 
                                          required><?php echo htmlspecialchars($formData['description']); ?></textarea>
                                <div class="character-count">
                                    <span id="descCount"><?php echo strlen($formData['description']); ?></span>/1000 karakter
                                </div>
                            </div>

                            <!-- Condition -->
                            <div class="mb-3">
                                <label for="condition" class="form-label">Kondisi *</label>
                                <select class="form-select" id="condition" name="condition" required>
                                    <option value="">Pilih kondisi</option>
                                    <option value="baru" <?php echo $formData['condition'] === 'baru' ? 'selected' : ''; ?>>Baru</option>
                                    <option value="bekas" <?php echo $formData['condition'] === 'bekas' ? 'selected' : ''; ?>>Bekas</option>
                                </select>
                            </div>

                            <!-- Price -->
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga *</label>
                                <div class="price-input-group">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="price" name="price" 
                                               placeholder="0" min="0" step="0.01" 
                                               value="<?php echo htmlspecialchars($formData['price']); ?>" required>
                                    </div>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="nego" name="nego" <?php echo $formData['nego'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="nego">
                                        Bisa nego
                                    </label>
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="mb-3">
                                <label for="location" class="form-label">Lokasi *</label>
                                <input type="text" class="form-control" id="location" name="location" 
                                       placeholder="Contoh: Jakarta Selatan, DKI Jakarta" 
                                       value="<?php echo htmlspecialchars($formData['location']); ?>" required>
                            </div>

                            <!-- Contact Info -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contactName" class="form-label">Nama Kontak *</label>
                                    <input type="text" class="form-control" id="contactName" name="contactName" 
                                           placeholder="Nama Anda" 
                                           value="<?php echo htmlspecialchars($formData['contactName']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contactPhone" class="form-label">No. HP/WhatsApp *</label>
                                    <input type="tel" class="form-control" id="contactPhone" name="contactPhone" 
                                           placeholder="0812-3456-7890" 
                                           value="<?php echo htmlspecialchars($formData['contactPhone']); ?>" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="prevStep()">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>
                                <button type="button" class="btn btn-primary btn-post-ad" onclick="nextStep()">
                                    Lanjut <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Image Upload -->
                        <div id="step3Content" class="step-content" style="display: none;">
                            <h3 class="mb-4">Upload Foto</h3>
                            
                            <div class="image-upload-area" id="imageUploadArea">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <h5>Drag & drop foto di sini</h5>
                                <p class="text-muted">atau klik untuk memilih file</p>
                                <p class="text-muted small">Maksimal 8 foto, format JPG/PNG, maks 5MB per foto</p>
                                <input type="file" id="imageInput" name="images[]" multiple accept="image/*" style="display: none;">
                            </div>

                            <div id="imagePreview" class="row g-2 mt-3"></div>

                            <div class="tips-section">
                                <h6><i class="fas fa-lightbulb"></i> Tips Foto yang Baik:</h6>
                                <ul>
                                    <li>Gunakan pencahayaan yang baik</li>
                                    <li>Ambil foto dari berbagai sudut</li>
                                    <li>Tampilkan detail kondisi barang</li>
                                    <li>Gunakan latar yang bersih</li>
                                    <li>Hindari filter yang berlebihan</li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="prevStep()">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>
                                <button type="button" class="btn btn-primary btn-post-ad" onclick="nextStep()">
                                    Lanjut <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 4: Preview -->
                        <div id="step4Content" class="step-content" style="display: none;">
                            <h3 class="mb-4">Preview Iklan</h3>
                            
                            <div class="card">
                                <img src="https://via.placeholder.com/600x400" class="card-img-top" alt="Preview">
                                <div class="card-body">
                                    <h4 id="previewTitle">Judul Iklan</h4>
                                    <p class="text-muted" id="previewCategory">Kategori</p>
                                    <h3 class="text-success" id="previewPrice">Rp 0</h3>
                                    <p id="previewLocation"><i class="fas fa-map-marker-alt"></i> Lokasi</p>
                                    <p id="previewDescription">Deskripsi</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong id="previewContact">Nama Kontak</strong><br>
                                            <small class="text-muted" id="previewPhone">No. HP</small>
                                        </div>
                                        <span class="badge bg-success" id="previewCondition">Kondisi</span>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-warning mt-3">
                                <h6><i class="fas fa-exclamation-triangle"></i> Perhatian:</h6>
                                <p class="mb-0">Pastikan semua informasi sudah benar. Iklan yang sudah dipublikasi tidak dapat diedit selama 24 jam pertama.</p>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="prevStep()">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>
                                <div>
                                    <button type="button" class="btn btn-outline-primary me-2" onclick="saveDraft()">
                                        <i class="fas fa-save"></i> Simpan Draft
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check"></i> Publikasikan Iklan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-light py-4">
        <div class="container">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 KF OLX. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentStep = 1;
        let selectedCategory = '';
        let uploadedImages = [];

        // Step navigation
        function nextStep() {
            if (validateStep(currentStep)) {
                document.getElementById(`step${currentStep}Content`).style.display = 'none';
                document.getElementById(`step${currentStep}`).classList.remove('active');
                document.getElementById(`step${currentStep}`).classList.add('completed');
                
                currentStep++;
                
                document.getElementById(`step${currentStep}Content`).style.display = 'block';
                document.getElementById(`step${currentStep}`).classList.add('active');
                
                if (currentStep === 4) {
                    updatePreview();
                }
            }
        }

        function prevStep() {
            document.getElementById(`step${currentStep}Content`).style.display = 'none';
            document.getElementById(`step${currentStep}`).classList.remove('active');
            
            currentStep--;
            
            document.getElementById(`step${currentStep}Content`).style.display = 'block';
            document.getElementById(`step${currentStep}`).classList.remove('completed');
            document.getElementById(`step${currentStep}`).classList.add('active');
        }

        // Category selection
        document.querySelectorAll('.category-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.category-item').forEach(cat => cat.classList.remove('selected'));
                this.classList.add('selected');
                selectedCategory = this.dataset.category;
            });
        });

        // Character counters
        document.getElementById('adTitle').addEventListener('input', function() {
            document.getElementById('titleCount').textContent = this.value.length;
        });

        document.getElementById('adDescription').addEventListener('input', function() {
            document.getElementById('descCount').textContent = this.value.length;
        });

        // Image upload
        const imageUploadArea = document.getElementById('imageUploadArea');
        const imageInput = document.getElementById('imageInput');

        imageUploadArea.addEventListener('click', () => imageInput.click());

        imageUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            imageUploadArea.classList.add('dragover');
        });

        imageUploadArea.addEventListener('dragleave', () => {
            imageUploadArea.classList.remove('dragover');
        });

        imageUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            imageUploadArea.classList.remove('dragover');
            handleFiles(e.dataTransfer.files);
        });

        imageInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        function handleFiles(files) {
            const imagePreview = document.getElementById('imagePreview');
            
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/') && uploadedImages.length < 8) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const imageId = Date.now() + Math.random();
                        uploadedImages.push({
                            id: imageId,
                            src: e.target.result,
                            name: file.name
                        });
                        
                        const imageDiv = document.createElement('div');
                        imageDiv.className = 'col-md-3';
                        imageDiv.innerHTML = `
                            <div class="image-preview">
                                <img src="${e.target.result}" alt="${file.name}">
                                <button type="button" class="remove-btn" onclick="removeImage(${imageId})">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                        imagePreview.appendChild(imageDiv);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        function removeImage(imageId) {
            uploadedImages = uploadedImages.filter(img => img.id !== imageId);
            const imagePreview = document.getElementById('imagePreview');
            imagePreview.innerHTML = '';
            uploadedImages.forEach(img => {
                const imageDiv = document.createElement('div');
                imageDiv.className = 'col-md-3';
                imageDiv.innerHTML = `
                    <div class="image-preview">
                        <img src="${img.src}" alt="${img.name}">
                        <button type="button" class="remove-btn" onclick="removeImage(${img.id})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                imagePreview.appendChild(imageDiv);
            });
        }

        // Update preview
        function updatePreview() {
            document.getElementById('previewTitle').textContent = document.getElementById('adTitle').value;
            document.getElementById('previewCategory').textContent = selectedCategory;
            document.getElementById('previewPrice').textContent = 'Rp ' + parseInt(document.getElementById('price').value).toLocaleString('id-ID');
            document.getElementById('previewLocation').innerHTML = '<i class="fas fa-map-marker-alt"></i> ' + document.getElementById('location').value;
            document.getElementById('previewDescription').textContent = document.getElementById('adDescription').value;
            document.getElementById('previewContact').textContent = document.getElementById('contactName').value;
            document.getElementById('previewPhone').textContent = document.getElementById('contactPhone').value;
            document.getElementById('previewCondition').textContent = document.getElementById('condition').value === 'baru' ? 'Baru' : 'Bekas';
        }

        // Validation
        function validateStep(step) {
            if (step === 1) {
                if (!selectedCategory) {
                    alert('Silakan pilih kategori terlebih dahulu');
                    return false;
                }
            } else if (step === 2) {
                const title = document.getElementById('adTitle').value;
                const description = document.getElementById('adDescription').value;
                const condition = document.getElementById('condition').value;
                const price = document.getElementById('price').value;
                const location = document.getElementById('location').value;
                const contactName = document.getElementById('contactName').value;
                const contactPhone = document.getElementById('contactPhone').value;
                
                if (!title || !description || !condition || !price || !location || !contactName || !contactPhone) {
                    alert('Silakan lengkapi semua field yang wajib diisi');
                    return false;
                }
            } else if (step === 3) {
                if (uploadedImages.length === 0) {
                    alert('Silakan upload minimal 1 foto');
                    return false;
                }
            }
            return true;
        }

        // Form submission
        document.getElementById('postAdForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const adData = {
                category: selectedCategory,
                title: document.getElementById('adTitle').value,
                description: document.getElementById('adDescription').value,
                condition: document.getElementById('condition').value,
                price: document.getElementById('price').value,
                location: document.getElementById('location').value,
                contactName: document.getElementById('contactName').value,
                contactPhone: document.getElementById('contactPhone').value,
                images: uploadedImages
            };
            
            console.log('Posting ad:', adData);
            alert('Iklan berhasil dipublikasikan! (Ini hanya demo)');
            // window.location.href = 'index.php';
        });

        function saveDraft() {
            alert('Draft berhasil disimpan! (Ini hanya demo)');
        }
    </script>
</body>
</html>
