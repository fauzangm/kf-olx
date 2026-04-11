<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Iklan - KF OLX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
        }
        .price-tag {
            font-size: 2rem;
            font-weight: bold;
            color: #28a745;
        }
        .location-tag {
            color: #6c757d;
            font-size: 1rem;
        }
        .main-image {
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
        }
        .thumbnail {
            height: 80px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.2s;
        }
        .thumbnail:hover,
        .thumbnail.active {
            border-color: #007bff;
        }
        .user-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
        }
        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }
        .contact-buttons .btn {
            margin-bottom: 10px;
        }
        .spec-item {
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .spec-item:last-child {
            border-bottom: none;
        }
        .breadcrumb {
            background: transparent;
            padding: 0;
        }
        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
            color: #6c757d;
        }
        .description-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .related-ad {
            transition: box-shadow 0.2s;
        }
        .related-ad:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .related-ad img {
            height: 150px;
            object-fit: cover;
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
                        <a class="nav-link" href="#"><i class="fas fa-plus-circle"></i> Pasang Iklan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-heart"></i> Favorit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-user"></i> Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white px-3" href="#">Daftar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="container mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Elektronik</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">HP & Tablet</a></li>
                <li class="breadcrumb-item active" aria-current="page">iPhone 13 Pro Max 256GB</li>
            </ol>
        </nav>
    </div>

    <!-- Product Detail Section -->
    <section class="py-4">
        <div class="container">
            <div class="row">
                <!-- Left Column - Images -->
                <div class="col-lg-8">
                    <div class="mb-4">
                        <img src="https://placehold.co/600x400" class="main-image w-100" id="mainImage" alt="Product Image">
                        
                        <!-- Thumbnail Images -->
                        <div class="row mt-3 g-2">
                            <div class="col-3">
                                <img src="https://placehold.co/150x150" class="thumbnail w-100 active" onclick="changeMainImage(this)" alt="Thumbnail 1">
                            </div>
                            <div class="col-3">
                                <img src="https://placehold.co/150x150" class="thumbnail w-100" onclick="changeMainImage(this)" alt="Thumbnail 2">
                            </div>
                            <div class="col-3">
                                <img src="https://placehold.co/150x150" class="thumbnail w-100" onclick="changeMainImage(this)" alt="Thumbnail 3">
                            </div>
                            <div class="col-3">
                                <img src="https://placehold.co/150x150" class="thumbnail w-100" onclick="changeMainImage(this)" alt="Thumbnail 4">
                            </div>
                        </div>
                    </div>

                    <!-- Product Title and Price -->
                    <div class="mb-4">
                        <h1 class="h2 mb-3">iPhone 13 Pro Max 256GB - Sierra Blue</h1>
                        <div class="d-flex align-items-center mb-3">
                            <span class="price-tag me-4">Rp 15.500.000</span>
                            <span class="location-tag">
                                <i class="fas fa-map-marker-alt"></i> Jakarta Selatan, DKI Jakarta
                            </span>
                        </div>
                        <div class="d-flex align-items-center text-muted small">
                            <span class="me-3"><i class="fas fa-eye"></i> 1.2k dilihat</span>
                            <span class="me-3"><i class="fas fa-heart"></i> 45 favorit</span>
                            <span><i class="fas fa-clock"></i> 2 jam yang lalu</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="description-section">
                        <h4 class="mb-3">Deskripsi</h4>
                        <div class="spec-item">
                            <strong>Kondisi:</strong> Bekas (95% Mulus)
                        </div>
                        <div class="spec-item">
                            <strong>Garansi:</strong> Tidak ada garansi
                        </div>
                        <div class="spec-item">
                            <strong>Warna:</strong> Sierra Blue
                        </div>
                        <div class="spec-item">
                            <strong>Storage:</strong> 256GB
                        </div>
                        <p class="mt-3">
                            Dijual iPhone 13 Pro Max 256GB warna Sierra Blue kondisi sangat terawat. 
                            HP sudah dipasang tempered glass dan case sejak pertama beli. 
                            Baterai health masih 95%, semua fungsi normal tidak ada kendala sama sekali.
                            Kelengkapan: Unit, charger, kabel data, dus, buku manual.
                            COD di area Jakarta Selatan atau bisa kirim via J&T/Tiki.
                        </p>
                    </div>

                    <!-- Specifications -->
                    <div class="mt-4">
                        <h4 class="mb-3">Spesifikasi</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <strong>Merek:</strong> Apple
                                </div>
                                <div class="spec-item">
                                    <strong>Model:</strong> iPhone 13 Pro Max
                                </div>
                                <div class="spec-item">
                                    <strong>Tahun:</strong> 2021
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="spec-item">
                                    <strong>Storage:</strong> 256GB
                                </div>
                                <div class="spec-item">
                                    <strong>RAM:</strong> 6GB
                                </div>
                                <div class="spec-item">
                                    <strong>Kamera:</strong> 48MP + 12MP + 12MP
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - User Info & Actions -->
                <div class="col-lg-4">
                    <!-- User Card -->
                    <div class="user-card mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://ui-avatars.com/api/?name=Ahmad+Susanto" class="user-avatar me-3" alt="User Avatar">
                            <div>
                                <h6 class="mb-0">Ahmad Susanto</h6>
                                <small class="text-muted">Member sejak Oktober 2023</small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between text-center mb-3">
                            <div>
                                <strong>15</strong>
                                <div class="small text-muted">Iklan Aktif</div>
                            </div>
                            <div>
                                <strong>98%</strong>
                                <div class="small text-muted">Respon Cepat</div>
                            </div>
                            <div>
                                <strong>4.8</strong>
                                <div class="small text-muted">Rating</div>
                            </div>
                        </div>
                        <a href="#" class="btn btn-outline-primary btn-sm w-100">Lihat Profil</a>
                    </div>

                    <!-- Contact Buttons -->
                    <div class="contact-buttons mb-4">
                        <button class="btn btn-success w-100 btn-lg">
                            <i class="fas fa-comments"></i> Chat Penjual
                        </button>
                        <button class="btn btn-outline-primary w-100">
                            <i class="fas fa-phone"></i> 0812-3456-7890
                        </button>
                        <button class="btn btn-outline-secondary w-100">
                            <i class="fas fa-heart"></i> Tambah ke Favorit
                        </button>
                        <button class="btn btn-outline-secondary w-100">
                            <i class="fas fa-share-alt"></i> Bagikan
                        </button>
                    </div>

                    <!-- Safety Tips -->
                    <div class="alert alert-warning">
                        <h6 class="alert-heading"><i class="fas fa-shield-alt"></i> Tips Keamanan</h6>
                        <ul class="mb-0 small">
                            <li>Meet-up di tempat umum yang aman</li>
                            <li>Periksa barang sebelum transaksi</li>
                            <li>Hindari transfer uang muka</li>
                            <li>Gunakan rekber jika perlu</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Ads Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h3 class="mb-4">Iklan Terkait</h3>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card related-ad h-100">
                        <img src="https://placehold.co/200x150" class="card-img-top" alt="Related Product">
                        <div class="card-body">
                            <h6 class="card-title">iPhone 12 Pro 128GB</h6>
                            <p class="price-tag h5">Rp 11.000.000</p>
                            <p class="location-tag small">
                                <i class="fas fa-map-marker-alt"></i> Jakarta Pusat
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card related-ad h-100">
                        <img src="https://placehold.co/200x150" class="card-img-top" alt="Related Product">
                        <div class="card-body">
                            <h6 class="card-title">iPhone 13 Pro 256GB</h6>
                            <p class="price-tag h5">Rp 14.000.000</p>
                            <p class="location-tag small">
                                <i class="fas fa-map-marker-alt"></i> Jakarta Utara
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card related-ad h-100">
                        <img src="https://placehold.co/200x150" class="card-img-top" alt="Related Product">
                        <div class="card-body">
                            <h6 class="card-title">Samsung S22 Ultra</h6>
                            <p class="price-tag h5">Rp 11.500.000</p>
                            <p class="location-tag small">
                                <i class="fas fa-map-marker-alt"></i> Jakarta Barat
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card related-ad h-100">
                        <img src="https://placehold.co/200x150" class="card-img-top" alt="Related Product">
                        <div class="card-body">
                            <h6 class="card-title">iPhone 14 Pro 128GB</h6>
                            <p class="price-tag h5">Rp 18.000.000</p>
                            <p class="location-tag small">
                                <i class="fas fa-map-marker-alt"></i> Jakarta Timur
                            </p>
                        </div>
                    </div>
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
        function changeMainImage(thumbnail) {
            const mainImage = document.getElementById('mainImage');
            mainImage.src = thumbnail.src.replace('150x150', '600x400');
            
            // Update active thumbnail
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            thumbnail.classList.add('active');
        }
    </script>
</body>
</html>
