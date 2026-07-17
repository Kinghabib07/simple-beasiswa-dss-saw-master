<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once "config.php";
    $sql = "SELECT * FROM pengguna WHERE username='$_POST[username]' AND password='" . md5($_POST['password']) . "'";
    if ($query = $connection->query($sql)) {
        if ($query->num_rows) {
            session_start();
            while ($data = $query->fetch_array()) {
                $_SESSION["is_logged"] = true;
                $_SESSION["as"] = $data["status"];
                $_SESSION["username"] = $data["username"];
              }
            header('location: index.php');
        } else {
            echo alert("Username / Password tidak sesuai!", "login.php");
        }
    } else {
        echo "Query error!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistem Keputusan Beasiswa</title>
    <!-- Modern Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #800000 0%, #4a0000 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 1rem 3rem rgba(0,0,0,0.4);
            overflow: hidden;
        }
        .login-left {
            background: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=1000&auto=format&fit=crop') center center / cover no-repeat;
            min-height: 400px;
            position: relative;
        }
        .login-left::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(128, 0, 0, 0.7);
        }
        .login-left-content {
            position: relative;
            z-index: 1;
            padding: 3rem;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
        }
        .login-right {
            padding: 3rem;
            background: #ffffff;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #800000;
            background-color: #fff;
        }
        .btn-login {
            background-color: #800000;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
        }
        .btn-login:hover {
            background-color: #5c0000;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(128,0,0,0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card login-card my-5">
                    <div class="row g-0">
                        <div class="col-lg-6 d-none d-lg-block login-left">
                            <div class="login-left-content">
                                <h2 class="fw-bold mb-3">DSS Beasiswa</h2>
                                <p class="lead">Sistem Pendukung Keputusan Penentuan Penerima Beasiswa Menggunakan Metode Simple Additive Weighting (SAW).</p>
                                <div class="mt-auto">
                                    <small class="opacity-75"><i class="fa-solid fa-shield-halved me-1"></i> Secure Authentication</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 login-right">
                            <div class="text-center mb-4">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                    <i class="fa-solid fa-user-lock fa-2x" style="color: #800000;"></i>
                                </div>
                                <h4 class="fw-bold text-dark">Welcome Back!</h4>
                                <p class="text-muted">Silakan login untuk masuk ke dashboard</p>
                            </div>
                            
                            <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-semibold small">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                                        <input type="text" name="username" class="form-control border-start-0 ps-0" placeholder="Masukkan username..." required autofocus>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-muted fw-semibold small">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                                        <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Masukkan password..." required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-login w-100">
                                    Login ke Sistem <i class="fa-solid fa-arrow-right ms-2"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
