<?php
session_start();
require_once "config.php";
if (!isset($_SESSION["is_logged"])) {
  header('location: login.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Pendukung Keputusan Beasiswa</title>
    <!-- Modern Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery.chained.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f9;
            color: #334155;
            overflow-x: hidden;
        }
        /* Wrapper for Sidebar and Content */
        #wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }
        /* Sidebar Styling */
        #sidebar {
            min-width: 260px;
            max-width: 260px;
            min-height: 100vh;
            background-color: #0f172a; /* Slate 900 */
            color: #fff;
            transition: all 0.3s;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        #sidebar .sidebar-brand {
            padding: 20px 25px;
            font-size: 1.25rem;
            font-weight: 700;
            background: #020617; /* Slate 950 */
            display: flex;
            align-items: center;
            letter-spacing: 0.5px;
            text-decoration: none;
            color: #fff;
        }
        #sidebar .sidebar-brand i {
            color: #3b82f6; /* Blue 500 */
            margin-right: 12px;
            font-size: 1.5rem;
        }
        #sidebar .sidebar-heading {
            padding: 15px 25px 5px 25px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b; /* Slate 500 */
            font-weight: 700;
        }
        #sidebar ul.components {
            padding: 10px 0;
        }
        #sidebar ul li a {
            padding: 12px 25px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            color: #cbd5e1; /* Slate 300 */
            text-decoration: none;
            transition: 0.2s;
            font-weight: 500;
        }
        #sidebar ul li a i {
            width: 25px;
            font-size: 1.1rem;
            margin-right: 10px;
            color: #94a3b8; /* Slate 400 */
        }
        #sidebar ul li a:hover, #sidebar ul li.active > a {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
            border-left: 4px solid #3b82f6;
        }
        #sidebar ul li a:hover i, #sidebar ul li.active > a i {
            color: #3b82f6;
        }
        
        /* Main Content Wrapper */
        #content-wrapper {
            width: 100%;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8fafc;
        }
        
        /* Topbar Styling */
        .topbar {
            height: 70px;
            background: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            padding: 0 25px;
            justify-content: space-between;
        }
        .topbar .nav-item .nav-link {
            color: #64748b;
            padding: 0 10px;
        }
        .topbar .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #475569;
        }
        .topbar .user-profile i {
            color: #3b82f6;
        }
        
        /* Container Content */
        .container-fluid {
            padding: 30px;
        }

        /* Responsive Sidebar */
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -260px;
                position: fixed;
            }
            #sidebar.active {
                margin-left: 0;
            }
        }
        
        /* General Overrides */
        .card {
            border: 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border-radius: 0.75rem;
        }
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
        }
    </style>
</head>
<body>

    <div id="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <a class="sidebar-brand" href="?page=home">
                <i class="fa-solid fa-graduation-cap"></i> 
                <div>DSS SAW<br><small style="font-size:10px; font-weight:400; color:#94a3b8;">Sistem Beasiswa</small></div>
            </a>

            <ul class="list-unstyled components">
                <div class="sidebar-heading">Menu Utama</div>
                <li>
                    <a href="?page=home"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                </li>
                
                <div class="sidebar-heading mt-3">Data Master</div>
                <li>
                    <a href="?page=mahasiswa"><i class="fa-solid fa-users"></i> Data Mahasiswa</a>
                </li>
                <li>
                    <a href="?page=kriteria"><i class="fa-solid fa-list-check"></i> Kriteria Penilaian</a>
                </li>
                <li>
                    <a href="?page=model"><i class="fa-solid fa-scale-balanced"></i> Nilai Bobot (Model)</a>
                </li>
                
                <div class="sidebar-heading mt-3">Proses</div>
                <li>
                    <a href="?page=nilai"><i class="fa-solid fa-keyboard"></i> Input Nilai Mhs</a>
                </li>
                
                <div class="sidebar-heading mt-3">Laporan & Hasil</div>
                <!-- Dropdown Beasiswa (Perhitungan) -->
                <li>
                    <a href="#perhitunganSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle align-items-center justify-content-between">
                        <div><i class="fa-solid fa-calculator"></i> Hasil Ranking SAW</div>
                    </a>
                    <ul class="collapse list-unstyled bg-slate-800" style="background-color: #1e293b;" id="perhitunganSubmenu">
                        <?php 
                        $query = $connection->query("SELECT * FROM beasiswa"); 
                        while ($row = $query->fetch_assoc()): 
                        ?>
                        <li>
                            <a href="?page=perhitungan&beasiswa=<?=$row["kd_beasiswa"]?>" style="padding-left: 45px; font-size: 0.85rem;"><i class="fa-solid fa-award fa-sm"></i> <?=$row["nama"]?></a>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div id="content-wrapper">
            
            <!-- Topbar -->
            <nav class="topbar">
                <div>
                    <button type="button" id="sidebarCollapse" class="btn btn-link text-dark d-md-none">
                        <i class="fa-solid fa-bars fa-lg"></i>
                    </button>
                    <h5 class="mb-0 fw-bold text-dark d-none d-md-inline-block ps-2">Sistem Keputusan Beasiswa</h5>
                </div>
                
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle user-profile" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="d-none d-sm-inline mx-2"><?= ucfirst($_SESSION["username"]) ?></span>
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-user text-primary"></i>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in border-0 mt-2">
                        <li><h6 class="dropdown-header">Sistem Akun</h6></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </nav>

            <!-- Page Content Container -->
            <div class="container-fluid flex-grow-1">
                <?php include page($_PAGE); ?>
            </div>
            
            <!-- Footer -->
            <footer class="bg-white text-center py-4 border-top mt-auto text-muted small">
                &copy; <?= date('Y') ?> <strong>DSS Beasiswa SAW Method</strong>. All rights reserved.
            </footer>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Toggle Script for Mobile -->
    <script>
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
            
            // Auto active menu script based on URL
            var current = location.search;
            $('#sidebar ul li a').each(function(){
                var $this = $(this);
                if($this.attr('href') === current){
                    $this.parent().addClass('active');
                    // if it's inside a submenu, expand it
                    if($this.closest('.collapse').length) {
                        $this.closest('.collapse').addClass('show');
                        $this.closest('.collapse').prev('a').attr('aria-expanded', 'true');
                    }
                }
            });
        });
    </script>
</body>
</html>
