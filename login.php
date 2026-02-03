<?php
/**
 * File: login.php
 * Fungsi: Halaman login untuk admin dan siswa
 */


// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/index.php");
    } else {
        header("Location: siswa/index.php");
    }
    exit();
}

// Include functions
require_once 'functions/functions.php';

// Variable untuk pesan error
$error = '';

// Proses LOGIN (ketika form di-submit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Validasi login
    if (validate_login($username, $password)) {
        // Login BERHASIL → redirect ke dashboard
        if ($_SESSION['role'] == 'admin') {
            header("Location: admin/index.php");
        } else {
            header("Location: siswa/index.php");
        }
        exit();
    } else {
        // Login GAGAL → tampilkan error
        $error = 'Username atau password salah!';
    }
}

$page_title = 'Login - Pengaduan Sekolah';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="card shadow-lg">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <i class="fas fa-school fa-4x text-primary mb-3"></i>
                                <h3 class="fw-bold">Sistem Pengaduan Sekolah</h3>
                                <p class="text-muted">Silakan login untuk melanjutkan</p>
                            </div>

                            <!-- Alert Error -->
                            <?php if ($error): ?>
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <!-- Form Login -->
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-user"></i> Username
                                    </label>
                                    <input type="text" class="form-control" name="username" 
                                           placeholder="Masukkan username" required autofocus>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-lock"></i> Password
                                    </label>
                                    <input type="password" class="form-control" name="password" 
                                           placeholder="Masukkan password" required>
                                </div>

                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-sign-in-alt"></i> Login
                                    </button>
                                </div>

                                <div class="text-center">
                                    <small class="text-muted">
                                        Belum punya akun? <a href="register.php">Daftar disini</a>
                                    </small>
                                </div>
                            </form>

                            <hr class="my-4">

                            <!-- Info Login -->
                            <div class="alert alert-info mb-0">
                                <small>
                                    <strong>Info Login:</strong><br>
                                    Admin: <code>admin</code> / <code>admin123</code><br>
                                    Siswa: <code>ahmad.rizki</code> / <code>siswa123</code>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>