<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KF OLX - Jual Beli Online Terpercaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .category-card {
            transition: transform 0.2s;
            cursor: pointer;
        }
        .category-card:hover {
            transform: translateY(-5px);
        }
        .ad-card {
            transition: box-shadow 0.2s;
        }
        .ad-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .price-tag {
            font-weight: bold;
            color: #28a745;
        }
        .location-tag {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
        }
        .search-box {
            max-width: 600px;
            margin: 0 auto;
        }
        .category-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .ad-image {
            height: 200px;
            object-fit: cover;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 40px 0;
            margin-top: 50px;
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

    <!-- Hero Section with Search -->
    <section class="hero-section">
        <div class="container">
            <div class="text-center mb-4">
                <h1 class="display-4 fw-bold">Jual Beli Online Terpercaya</h1>
                <p class="lead">Temukan barang impian Anda atau jual barang bekas dengan mudah</p>
            </div>
            
            <div class="search-box">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control" placeholder="Cari barang, mobil, properti, atau jasa...">
                    <button class="btn btn-warning" type="button">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Kategori Populer</h2>
            <div class="row g-3">
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="card category-card text-center p-3">
                        <div class="category-icon text-primary">
                            <i class="fas fa-car"></i>
                        </div>
                        <h6>Mobil</h6>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="card category-card text-center p-3">
                        <div class="category-icon text-primary">
                            <i class="fas fa-motorcycle"></i>
                        </div>
                        <h6>Motor</h6>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="card category-card text-center p-3">
                        <div class="category-icon text-primary">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h6>HP & Tablet</h6>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="card category-card text-center p-3">
                        <div class="category-icon text-primary">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <h6>Elektronik</h6>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="card category-card text-center p-3">
                        <div class="category-icon text-primary">
                            <i class="fas fa-tshirt"></i>
                        </div>
                        <h6>Fashion</h6>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="card category-card text-center p-3">
                        <div class="category-icon text-primary">
                            <i class="fas fa-home"></i>
                        </div>
                        <h6>Properti</h6>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Ads Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Iklan Terbaru</h2>
                <a href="#" class="btn btn-outline-primary">Lihat Semua</a>
            </div>
            
            <div class="row g-4">
                <!-- Sample Ad Cards -->
                <div class="col-md-6 col-lg-4">
                    <div class="card ad-card h-100">
                        <img src="https://placehold.co/600x400" class="card-img-top ad-image" alt="Product Image">
                        <div class="card-body">
                            <a href="detail.php" class="stretched-link"></a>
                            <h5 class="card-title">iPhone 13 Pro Max 256GB</h5>
                            <p class="card-text text-muted small">Elektronik > HP & Tablet</p>
                            <p class="price-tag h4">Rp 15.500.000</p>
                            <p class="location-tag">
                                <i class="fas fa-map-marker-alt"></i> Jakarta Selatan
                            </p>
                            <small class="text-muted">2 jam yang lalu</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card ad-card h-100">
                        <img src="https://via.placeholder.com/300x200" class="card-img-top ad-image" alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title">Honda Vario 125 2022</h5>
                            <p class="card-text text-muted small">Motor > Honda</p>
                            <p class="price-tag h4">Rp 18.000.000</p>
                            <p class="location-tag">
                                <i class="fas fa-map-marker-alt"></i> Bandung
                            </p>
                            <small class="text-muted">5 jam yang lalu</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card ad-card h-100">
                        <img src="https://via.placeholder.com/300x200" class="card-img-top ad-image" alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title">MacBook Air M1 2020</h5>
                            <p class="card-text text-muted small">Elektronik > Laptop</p>
                            <p class="price-tag h4">Rp 12.000.000</p>
                            <p class="location-tag">
                                <i class="fas fa-map-marker-alt"></i> Surabaya
                            </p>
                            <small class="text-muted">1 hari yang lalu</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card ad-card h-100">
                        <img src="https://via.placeholder.com/300x200" class="card-img-top ad-image" alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title">Toyota Avanza 2019</h5>
                            <p class="card-text text-muted small">Mobil > Toyota</p>
                            <p class="price-tag h4">Rp 145.000.000</p>
                            <p class="location-tag">
                                <i class="fas fa-map-marker-alt"></i> Tangerang
                            </p>
                            <small class="text-muted">2 hari yang lalu</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card ad-card h-100">
                        <img src="https://via.placeholder.com/300x200" class="card-img-top ad-image" alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title">Samsung Galaxy S22 Ultra</h5>
                            <p class="card-text text-muted small">Elektronik > HP & Tablet</p>
                            <p class="price-tag h4">Rp 11.000.000</p>
                            <p class="location-tag">
                                <i class="fas fa-map-marker-alt"></i> Yogyakarta
                            </p>
                            <small class="text-muted">3 hari yang lalu</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card ad-card h-100">
                        <img src="https://via.placeholder.com/300x200" class="card-img-top ad-image" alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title">Kost Putra Jakarta Pusat</h5>
                            <p class="card-text text-muted small">Properti > Kost</p>
                            <p class="price-tag h4">Rp 1.500.000/bulan</p>
                            <p class="location-tag">
                                <i class="fas fa-map-marker-alt"></i> Jakarta Pusat
                            </p>
                            <small class="text-muted">4 hari yang lalu</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Cara Kerja KF OLX</h2>
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-search fa-3x text-primary"></i>
                    </div>
                    <h5>1. Cari Barang</h5>
                    <p class="text-muted">Gunakan fitur pencarian untuk menemukan barang yang Anda inginkan</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-comments fa-3x text-primary"></i>
                    </div>
                    <h5>2. Hubungi Penjual</h5>
                    <p class="text-muted">Chat langsung dengan penjual untuk negosiasi harga</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-handshake fa-3x text-primary"></i>
                    </div>
                    <h5>3. Transaksi Aman</h5>
                    <p class="text-muted">Lakukan transaksi dengan aman dan nyaman</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-shopping-bag"></i> KF OLX</h5>
                    <p>Platform jual beli online terpercaya di Indonesia. Mudah, aman, dan cepat.</p>
                </div>
                <div class="col-md-4">
                    <h5>Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-decoration-none">Tentang Kami</a></li>
                        <li><a href="#" class="text-decoration-none">Cara Kerja</a></li>
                        <li><a href="#" class="text-decoration-none">Keamanan</a></li>
                        <li><a href="#" class="text-decoration-none">Bantuan</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Hubungi Kami</h5>
                    <p><i class="fas fa-envelope"></i> support@kf-olx.com</p>
                    <p><i class="fas fa-phone"></i> 0800-1234-5678</p>
                    <div class="mt-3">
                        <a href="#" class="text-primary me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-primary me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-primary me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-primary"><i class="fab fa-whatsapp fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 KF OLX. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
