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
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $terms = isset($_POST['terms']);
    $newsletter = isset($_POST['newsletter']);
    
    // Validation
    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirmPassword)) {
        $error = 'Silakan isi semua field yang wajib diisi';
    } elseif (strlen($name) < 3) {
        $error = 'Nama minimal 3 karakter';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid';
    } elseif ($user->emailExists($email)) {
        $error = 'Email sudah terdaftar. Silakan gunakan email lain atau login.';
    } elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter';
    } elseif ($password !== $confirmPassword) {
        $error = 'Password dan konfirmasi password tidak cocok';
    } elseif (!$terms) {
        $error = 'Anda harus menyetujui syarat dan ketentuan';
    } else {
        // Attempt to register user
        if ($user->register($name, $email, $password)) {
            $success = 'Pendaftaran berhasil! Anda sekarang bisa login.';
            // Redirect to login page after 2 seconds
            header('Refresh: 2; URL=login.php');
        } else {
            $error = 'Terjadi kesalahan. Silakan coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - KF OLX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
        }
        .register-container {
            max-width: 450px;
            margin: 0 auto;
            padding: 40px 0;
        }
        .register-card {
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
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        .social-register .btn {
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
        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 5px;
            transition: all 0.3s;
        }
        .strength-weak {
            background: #dc3545;
            width: 33%;
        }
        .strength-medium {
            background: #ffc107;
            width: 66%;
        }
        .strength-strong {
            background: #28a745;
            width: 100%;
        }
        .terms-checkbox {
            font-size: 14px;
        }
        .login-link {
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
        .validation-feedback {
            font-size: 12px;
            margin-top: 5px;
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
            <h1 class="display-4 fw-bold">Bergabung dengan KF OLX</h1>
            <p class="lead">Daftar gratis dan mulai jual beli dengan ribuan pengguna lainnya</p>
        </div>
    </section>

    <!-- Register Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="register-card">
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
                        
                        <!-- Register Form -->
                        <form method="POST" action="">
                            <h3 class="text-center mb-4">Daftar Akun Baru</h3>
                            
                            <!-- Name Field -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" class="form-control with-icon" id="name" name="name" 
                                           placeholder="Masukkan nama lengkap" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                                </div>
                                <div class="validation-feedback text-muted">Minimal 3 karakter</div>
                            </div>

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
                                <div class="validation-feedback text-muted">Contoh: user@example.com</div>
                            </div>

                            <!-- Phone Field -->
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="tel" class="form-control with-icon" id="phone" name="phone" 
                                           placeholder="0812-3456-7890" value="<?php echo htmlspecialchars($phone ?? ''); ?>" required>
                                </div>
                                <div class="validation-feedback text-muted">Format: 08xx-xxxx-xxxx</div>
                            </div>

                            <!-- Password Field -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control with-icon" id="password" name="password" 
                                           placeholder="Minimal 8 karakter" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength" id="passwordStrength"></div>
                                <div class="validation-feedback text-muted">Gunakan kombinasi huruf, angka, dan simbol</div>
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control with-icon" id="confirmPassword" name="confirmPassword" 
                                           placeholder="Ulangi password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="validation-feedback" id="confirmPasswordFeedback"></div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check terms-checkbox">
                                    <input class="form-check-input" type="checkbox" id="terms" name="terms" <?php echo isset($_POST['terms']) ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" for="terms">
                                        Saya setuju dengan <a href="#" class="text-decoration-none">Syarat & Ketentuan</a> 
                                        dan <a href="#" class="text-decoration-none">Kebijakan Privasi</a> KF OLX
                                    </label>
                                </div>
                            </div>

                            <!-- Newsletter Checkbox -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter" <?php echo isset($_POST['newsletter']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="newsletter">
                                        Saya ingin menerima newsletter dan promo menarik
                                    </label>
                                </div>
                            </div>

                            <!-- Register Button -->
                            <button type="submit" class="btn btn-primary btn-register w-100 mb-3">
                                <i class="fas fa-user-plus"></i> Daftar Sekarang
                            </button>
                        </form>

                        <!-- Divider -->
                        <div class="divider">
                            <span>atau daftar dengan</span>
                        </div>

                        <!-- Social Register -->
                        <div class="social-register">
                            <button class="btn btn-outline-primary w-100">
                                <i class="fab fa-google"></i> Google
                            </button>
                            <button class="btn btn-outline-primary w-100">
                                <i class="fab fa-facebook"></i> Facebook
                            </button>
                        </div>

                        <!-- Login Link -->
                        <div class="login-link">
                            <p>Sudah punya akun? <a href="login.php" class="text-decoration-none text-primary">Masuk di sini</a></p>
                        </div>
                    </div>
                </div>

                <!-- Benefits Section -->
                <div class="col-lg-5">
                    <div class="ps-lg-5 mt-5 mt-lg-0">
                        <h3 class="mb-4">Keuntungan Bergabung</h3>
                        <ul class="feature-list">
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <strong>Daftar Gratis</strong> - Tidak ada biaya pendaftaran
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <strong>Verifikasi Cepat</strong> - Akun aktif dalam hitungan menit
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <strong>Dashboard Lengkap</strong> - Kelola iklan dengan mudah
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <strong>Notifikasi Real-time</strong> - Update langsung ke email
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <strong>Rating System</strong> - Bangun reputasi sebagai penjual terpercaya
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <strong>Chat Secure</strong> - Komunikasi aman dengan pembeli
                            </li>
                        </ul>

                        <!-- Security Badge -->
                        <div class="alert alert-info mt-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-shield-alt fa-2x me-3 text-info"></i>
                                <div>
                                    <h6 class="mb-1">Data Anda Aman</h6>
                                    <p class="mb-0 small">Kami menggunakan enkripsi SSL untuk melindungi data pribadi Anda</p>
                                </div>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="row text-center mt-4">
                            <div class="col-4">
                                <h4 class="text-primary">500K+</h4>
                                <small class="text-muted">Pengguna Aktif</small>
                            </div>
                            <div class="col-4">
                                <h4 class="text-primary">10K+</h4>
                                <small class="text-muted">Iklan/Hari</small>
                            </div>
                            <div class="col-4">
                                <h4 class="text-primary">98%</h4>
                                <small class="text-muted">Kepuasan</small>
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
                    <i class="fas fa-question-circle fa-3x text-primary mb-2"></i>
                    <h6>Pertanyaan?</h6>
                    <p class="text-muted small">Lihat FAQ kami</p>
                    <a href="#" class="btn btn-sm btn-outline-primary">FAQ</a>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <i class="fas fa-headset fa-3x text-primary mb-2"></i>
                    <h6>Butuh Bantuan?</h6>
                    <p class="text-muted small">Hubungi support 24/7</p>
                    <a href="#" class="btn btn-sm btn-outline-primary">Hubungi Kami</a>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <i class="fas fa-book fa-3x text-primary mb-2"></i>
                    <h6>Panduan</h6>
                    <p class="text-muted small">Cara mulai berjualan</p>
                    <a href="#" class="btn btn-sm btn-outline-primary">Pelajari</a>
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

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPasswordInput = document.getElementById('confirmPassword');
            const icon = this.querySelector('i');
            
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                confirmPasswordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrength');
            
            if (password.length === 0) {
                strengthBar.className = 'password-strength';
                return;
            }
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            strengthBar.className = 'password-strength';
            
            if (strength <= 1) {
                strengthBar.classList.add('strength-weak');
            } else if (strength === 2) {
                strengthBar.classList.add('strength-medium');
            } else {
                strengthBar.classList.add('strength-strong');
            }
        });

        // Confirm password validation
        document.getElementById('confirmPassword').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const feedback = document.getElementById('confirmPasswordFeedback');
            
            if (confirmPassword.length === 0) {
                feedback.textContent = '';
                feedback.className = 'validation-feedback';
                return;
            }
            
            if (password === confirmPassword) {
                feedback.textContent = '✓ Password cocok';
                feedback.className = 'validation-feedback text-success';
            } else {
                feedback.textContent = '✗ Password tidak cocok';
                feedback.className = 'validation-feedback text-danger';
            }
        });

        // Form validation and submission
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const terms = document.getElementById('terms').checked;
            
            // Basic validation
            if (!name || !email || !phone || !password || !confirmPassword) {
                alert('Silakan isi semua field yang wajib diisi');
                return;
            }
            
            if (name.length < 3) {
                alert('Nama minimal 3 karakter');
                return;
            }
            
            if (!validateEmail(email)) {
                alert('Format email tidak valid');
                return;
            }
            
            if (password.length < 8) {
                alert('Password minimal 8 karakter');
                return;
            }
            
            if (password !== confirmPassword) {
                alert('Password dan konfirmasi password tidak cocok');
                return;
            }
            
            if (!terms) {
                alert('Anda harus menyetujui syarat dan ketentuan');
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
