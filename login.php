<?php
// Include configuration file
require_once 'config.php';

// Check if user is already logged in
if ($user->isLoggedIn()) {
    header('Location: index.php');
    exit();
}

// Initialize variables
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $rememberMe = isset($_POST['rememberMe']);
    
    // Validation
    if (empty($email) || empty($password)) {
        $error = 'Silakan isi email dan password';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid';
    } else {
        // Attempt to login user
        if ($user->login($email, $password)) {
            // Set remember me cookie if checked
            if ($rememberMe) {
                $cookie_name = 'remember_me';
                $cookie_value = $email;
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), '/'); // 30 days
            }
            
            $success = 'Login berhasil! Mengalihkan...';
            header('Refresh: 1; URL=index.php');
        } else {
            $error = 'Email atau password salah';
        }
    }
}

// Check for remember me cookie
if (!empty($_COOKIE['remember_me']) && !$user->isLoggedIn()) {
    $email = $_COOKIE['remember_me'];
    // You could implement auto-login here if needed
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - KF OLX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
        }
        .login-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 40px 0;
        }
        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
            border: none;
        }
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        .social-login .btn {
            padding: 10px;
            border-radius: 8px;
            font-weight: 500;
            margin-bottom: 10px;
        }
        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #ddd;
        }
        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
            color: #6c757d;
        }
        .input-group-text {
            background: transparent;
            border-right: none;
            color: #6c757d;
        }
        .form-control.with-icon {
            border-left: none;
        }
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .signup-link {
            text-align: center;
            margin-top: 20px;
        }
        .feature-list {
            list-style: none;
            padding: 0;
        }
        .feature-list li {
            padding: 8px 0;
            color: #555;
        }
        .feature-list i {
            color: #28a745;
            margin-right: 10px;
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
                        <a class="nav-link" href="#"><i class="fas fa-plus-circle"></i> Pasang Iklan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-heart"></i> Favorit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="login.php"><i class="fas fa-user"></i> Masuk</a>
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
            <h1 class="display-4 fw-bold">Masuk ke Akun Anda</h1>
            <p class="lead">Akses ribuan iklan menarik dan jual barang dengan mudah</p>
        </div>
    </section>

    <!-- Login Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="login-card">
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
                        
                        <!-- Login Form -->
                        <form method="POST" action="">
                            <h3 class="text-center mb-4">Masuk</h3>
                            
                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control with-icon" id="email" name="email" 
                                           placeholder="nama@email.com" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                                </div>
                            </div>

                            <!-- Password Field -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control with-icon" id="password" name="password" 
                                           placeholder="Masukkan password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="remember-forgot">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberMe" <?php echo isset($_POST['rememberMe']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="rememberMe">
                                        Ingat saya
                                    </label>
                                </div>
                                <a href="#" class="text-decoration-none">Lupa password?</a>
                            </div>

                            <!-- Login Button -->
                            <button type="submit" class="btn btn-primary btn-login w-100 mb-3">
                                <i class="fas fa-sign-in-alt"></i> Masuk
                            </button>
                        </form>

                        <!-- Divider -->
                        <div class="divider">
                            <span>atau masuk dengan</span>
                        </div>

                        <!-- Social Login -->
                        <div class="social-login">
                            <button class="btn btn-outline-primary w-100">
                                <i class="fab fa-google"></i> Google
                            </button>
                            <button class="btn btn-outline-primary w-100">
                                <i class="fab fa-facebook"></i> Facebook
                            </button>
                        </div>

                        <!-- Signup Link -->
                        <div class="signup-link">
                            <p>Belum punya akun? <a href="register.php" class="text-decoration-none text-primary">Daftar sekarang</a></p>
                        </div>
                    </div>
                </div>

                <!-- Benefits Section -->
                <div class="col-lg-5 offset-lg-1">
                    <div class="ps-lg-5 mt-5 mt-lg-0">
                        <h3 class="mb-4">Mengapa bergabung dengan KF OLX?</h3>
                        <ul class="feature-list">
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <strong>Gratis Pasang Iklan</strong> - Jual barang tanpa biaya
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <strong>Jangkauan Luas</strong> - Ribuan pembeli setiap hari
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <strong>Chat Langsung</strong> - Komunikasi mudah dengan pembeli
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <strong>Keamanan Terjamin</strong> - Sistem verifikasi pengguna
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <strong>Dashboard Lengkap</strong> - Kelola iklan dengan mudah
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <strong>Notifikasi Real-time</strong> - Update langsung ke email
                            </li>
                        </ul>

                        <!-- Testimonial -->
                        <div class="alert alert-success mt-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-quote-left fa-2x me-3 text-success"></i>
                                <div>
                                    <p class="mb-0">"KF OLX membantu saya menjual mobil bekas dengan cepat dan harga yang bagus!"</p>
                                    <small class="text-muted">- Budi Santoso, Member sejak 2023</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Help Section -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-center mb-3">
                    <i class="fas fa-headset fa-3x text-primary mb-2"></i>
                    <h6>Butuh Bantuan?</h6>
                    <p class="text-muted small">Hubungi customer service kami</p>
                    <a href="#" class="btn btn-sm btn-outline-primary">Chat Support</a>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <i class="fas fa-shield-alt fa-3x text-primary mb-2"></i>
                    <h6>Keamanan</h6>
                    <p class="text-muted small">Data Anda aman bersama kami</p>
                    <a href="#" class="btn btn-sm btn-outline-primary">Pelajari Lebih</a>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <i class="fas fa-mobile-alt fa-3x text-primary mb-2"></i>
                    <h6>Download App</h6>
                    <p class="text-muted small">Akses lebih mudah di mobile</p>
                    <a href="#" class="btn btn-sm btn-outline-primary">Download</a>
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
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Form validation and submission
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            
            // Basic validation
            if (!email || !password) {
                alert('Silakan isi email dan password');
                return;
            }
            
            if (!validateEmail(email)) {
                alert('Format email tidak valid');
                return;
            }
            
            // Submit form
            this.submit();
        });
        
        // Email validation function
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    </script>
</body>
</html>
