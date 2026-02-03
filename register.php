<?php
/**
 * File: register.php
 * Fungsi: Halaman pendaftaran akun baru (siswa)
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

// Variable untuk pesan
$errors = [];
$success_message = '';

// Proses REGISTER (ketika form di-submit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil dan bersihkan input
    $nama = clean_input($_POST['nama']);
    $username = clean_input($_POST['username']);
    $password = $_POST['password']; // Jangan clean password sebelum hash
    $password_confirm = $_POST['password_confirm'];
    $kelas = clean_input($_POST['kelas']);

    // ===== VALIDASI =====

    // Cek field kosong
    if (empty($nama)) {
        $errors[] = "Nama lengkap wajib diisi";
    }
    if (empty($username)) {
        $errors[] = "Username wajib diisi";
    }
    if (empty($password)) {
        $errors[] = "Password wajib diisi";
    }
    if (empty($kelas)) {
        $errors[] = "Kelas wajib diisi";
    }

    // Cek panjang username (min 3, max 20 karakter)
    if (!empty($username) && (strlen($username) < 3 || strlen($username) > 20)) {
        $errors[] = "Username harus 3-20 karakter";
    }

    // Cek username hanya huruf, angka, dan underscore
    if (!empty($username) && !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = "Username hanya boleh huruf, angka, dan underscore (_)";
    }

    // Cek panjang password (min 6 karakter)
    if (!empty($password) && strlen($password) < 6) {
        $errors[] = "Password harus minimal 6 karakter";
    }

    // Cek password dan confirm password sesuai
    if (!empty($password) && !empty($password_confirm) && $password !== $password_confirm) {
        $errors[] = "Password dan Konfirmasi Password tidak sesuai";
    }

    // Jika tidak ada error, proses register
    if (empty($errors)) {
        $result = register_user($nama, $username, $password, $kelas);

        if ($result === 'username_exist') {
            $errors[] = "Username sudah digunakan! Pilih username lain.";
        } elseif ($result === true) {
            $success_message = "Pendaftaran berhasil! Anda bisa login sekarang.";
        } else {
            $errors[] = "Gagal mendaftar. Coba lagi.";
        }
    }
}

$page_title = 'Daftar Akun - Pengaduan Sekolah';
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
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 0;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-lg">
                        <div class="card-body p-5">
                            <!-- Header -->
                            <div class="text-center mb-4">
                                <i class="fas fa-user-plus fa-4x text-primary mb-3"></i>
                                <h3 class="fw-bold">Daftar Akun Baru</h3>
                                <p class="text-muted">Isi data di bawah untuk membuat akun</p>
                            </div>

                            <!-- Alert Success -->
                            <?php if ($success_message): ?>
                                <div class="alert alert-success text-center">
                                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                                    <br><br>
                                    <a href="login.php" class="btn btn-primary">
                                        <i class="fas fa-sign-in-alt"></i> Login Sekarang
                                    </a>
                                </div>
                            <?php endif; ?>

                            <!-- Alert Errors -->
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <strong><i class="fas fa-exclamation-triangle"></i> Kesalahan:</strong>
                                    <ul class="mb-0 mt-2">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo $error; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <!-- Form Register -->
                            <?php if (!$success_message): ?>
                            <form method="POST" action="">
                                <!-- Nama Lengkap -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-user text-primary"></i> Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg" name="nama"
                                           placeholder="Contoh: Ahmad Rizki Pratama"
                                           value="<?php echo isset($_POST['nama']) ? $_POST['nama'] : ''; ?>"
                                           required>
                                </div>

                                <!-- Username -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-at text-primary"></i> Username <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg" name="username"
                                           placeholder="Contoh: ahmad_rizki"
                                           value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>"
                                           minlength="3" maxlength="20"
                                           required>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> 3-20 karakter, huruf/angka/underscore saja
                                    </small>
                                </div>

                                <!-- Kelas -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-users text-primary"></i> Kelas <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg" name="kelas"
                                           placeholder="Contoh: XII RPL 1"
                                           value="<?php echo isset($_POST['kelas']) ? $_POST['kelas'] : ''; ?>"
                                           required>
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-lock text-primary"></i> Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control form-control-lg" name="password"
                                           placeholder="Min. 6 karakter"
                                           minlength="6"
                                           required>
                                </div>

                                <!-- Konfirmasi Password -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-lock text-primary"></i> Konfirmasi Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control form-control-lg" name="password_confirm"
                                           placeholder="Ulangi password Anda"
                                           minlength="6"
                                           required>
                                </div>

                                <!-- Tombol Register -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-user-plus"></i> Daftar Akun
                                    </button>
                                </div>
                            </form>
                            <?php endif; ?>

                            <!-- Link ke Login -->
                            <div class="text-center mt-4">
                                <p class="text-muted mb-0">
                                    Sudah punya akun? <a href="login.php"><strong>Login di sini</strong></a>
                                </p>
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