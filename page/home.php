<style>
    /* OMG Level Animations & Styling */
    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    @keyframes floatUp {
        0% { transform: translateY(30px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    
    @keyframes pulseGlow {
        0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(59, 130, 246, 0); }
        100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }

    .welcome-banner {
        background: linear-gradient(-45deg, #1e3a8a, #1e40af, #3b82f6, #2563eb);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
        border-radius: 20px;
        color: white;
        padding: 3rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(30, 58, 138, 0.3);
        z-index: 1;
    }
    
    /* Abstract glass shapes in background */
    .welcome-banner::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        top: -100px;
        right: -50px;
        z-index: -1;
    }
    
    .welcome-banner::after {
        content: '\f19d'; /* Graduation cap */
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        right: 20px;
        bottom: -20px;
        font-size: 14rem;
        opacity: 0.15;
        transform: rotate(-15deg);
        z-index: -1;
    }

    .stat-card {
        animation: floatUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.03);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .stat-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
        border-color: rgba(128,0,0,0.1);
    }

    .icon-box-omg {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(255,255,255,1) 0%, rgba(240,240,240,1) 100%);
        box-shadow: inset 0 2px 4px rgba(255,255,255,0.8), 0 4px 10px rgba(0,0,0,0.05);
    }

    .action-card {
        background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
        color: white;
    }

    .btn-omg {
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        border: none;
        animation: pulseGlow 2s infinite;
        transition: all 0.3s ease;
    }
    .btn-omg:hover {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        transform: scale(1.05);
    }

    .kriteria-omg-card {
        border-radius: 20px;
        border: none;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        transition: transform 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .kriteria-omg-card:hover {
        transform: translateY(-5px);
    }
    .kriteria-omg-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 5px;
    }
    .kriteria-bpa::before { background: linear-gradient(90deg, #3498db, #2980b9); }
    .kriteria-lazismu::before { background: linear-gradient(90deg, #2ecc71, #27ae60); }

    .val-badge-omg {
        font-weight: 700;
        background: #f8f9fa;
        padding: 6px 15px;
        border-radius: 8px;
        color: #2c3e50;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
</style>

<!-- Welcome Banner -->
<div class="row mb-5">
    <div class="col-12">
        <div class="welcome-banner">
            <span class="badge bg-white text-danger px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm" style="font-size: 0.9rem;">
                <i class="fa-solid fa-rocket me-1"></i> Dashboard Sistem SAW
            </span>
            <h1 class="fw-bold mb-3 display-5" style="text-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                Selamat Datang, <?= ucfirst($_SESSION["username"]) ?>!
            </h1>
            <p class="lead mb-0" style="max-width: 650px; font-weight: 300; opacity: 0.9;">
                Sistem Cerdas Penyeleksian Penerima Beasiswa Prestasi Akademik (BPA) & LazisMU menggunakan algoritma <strong>Simple Additive Weighting</strong>.
            </p>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-4 mb-5">
    <!-- Card Mahasiswa -->
    <div class="col-md-4">
        <div class="card stat-card shadow-sm h-100" style="animation-delay: 0.1s;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="icon-box-omg">
                        <i class="fa-solid fa-user-graduate fa-2x" style="background: -webkit-linear-gradient(#3498db, #2980b9); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                    </div>
                    <?php $mhs = $connection->query("SELECT COUNT(*) as total FROM mahasiswa")->fetch_assoc(); ?>
                    <h1 class="fw-bold text-dark mb-0 display-4"><?= $mhs['total'] ?></h1>
                </div>
                <h5 class="text-secondary fw-bold mb-3">Data Mahasiswa</h5>
                <a href="?page=mahasiswa" class="btn btn-light w-100 fw-bold shadow-sm" style="color: #3498db; border-radius: 10px;">Kelola Data <i class="fa-solid fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    </div>
    
    <!-- Card Beasiswa -->
    <div class="col-md-4">
        <div class="card stat-card shadow-sm h-100" style="animation-delay: 0.2s;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="icon-box-omg">
                        <i class="fa-solid fa-award fa-2x" style="background: -webkit-linear-gradient(#2ecc71, #27ae60); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                    </div>
                    <?php $bea = $connection->query("SELECT COUNT(*) as total FROM beasiswa")->fetch_assoc(); ?>
                    <h1 class="fw-bold text-dark mb-0 display-4"><?= $bea['total'] ?></h1>
                </div>
                <h5 class="text-secondary fw-bold mb-3">Jenis Beasiswa</h5>
                <a href="?page=beasiswa" class="btn btn-light w-100 fw-bold shadow-sm" style="color: #27ae60; border-radius: 10px;">Lihat Kategori <i class="fa-solid fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    </div>

    <!-- Card Perhitungan OMG -->
    <div class="col-md-4">
        <div class="card stat-card action-card shadow-lg h-100" style="animation-delay: 0.3s; transform-origin: center;">
            <div class="card-body p-4 d-flex flex-column position-relative overflow-hidden">
                <i class="fa-solid fa-calculator position-absolute" style="font-size: 10rem; opacity: 0.05; right: -20px; bottom: -20px; transform: rotate(15deg);"></i>
                
                <div class="d-flex justify-content-between align-items-center mb-4 z-1">
                    <div class="icon-box-omg bg-transparent border border-light border-opacity-25">
                        <i class="fa-solid fa-ranking-star fa-2x text-warning"></i>
                    </div>
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold shadow-sm"><i class="fa-solid fa-bolt text-danger me-1"></i> Quick Action</span>
                </div>
                
                <h4 class="text-white fw-bold mb-1 z-1">Proses SAW</h4>
                <p class="text-white-50 small mb-4 z-1">Hitung & tentukan siapa yang layak.</p>
                
                <div class="dropdown d-grid mt-auto z-1">
                    <button class="btn btn-omg btn-lg dropdown-toggle fw-bold text-white text-start d-flex justify-content-between align-items-center rounded-3" type="button" data-bs-toggle="dropdown">
                        <span><i class="fa-solid fa-play-circle me-2 fs-5 align-middle"></i> Hitung Ranking</span>
                    </button>
                    <ul class="dropdown-menu w-100 shadow-lg border-0 mt-2 p-2" style="border-radius: 12px; background: rgba(255,255,255,0.98); backdrop-filter: blur(10px);">
                        <?php 
                        $query = $connection->query("SELECT * FROM beasiswa"); 
                        while ($row = $query->fetch_assoc()): 
                        ?>
                        <li>
                            <a class="dropdown-item py-3 px-3 fw-bold text-dark rounded-2 mb-1" href="?page=perhitungan&beasiswa=<?=$row["kd_beasiswa"]?>" style="transition: all 0.2s;">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-arrow-trend-up"></i>
                                    </div>
                                    <?=$row["nama"]?>
                                </div>
                            </a>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kriteria Section -->
<div class="row" style="animation: floatUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; animation-delay: 0.4s;">
    <div class="col-12">
        <div class="d-flex align-items-center mb-4">
            <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                <i class="fa-solid fa-fingerprint"></i>
            </div>
            <h4 class="fw-bold text-dark mb-0">Matriks Syarat Utama</h4>
        </div>
        
        <div class="row g-4">
            <!-- BPA Card -->
            <div class="col-md-6">
                <div class="kriteria-omg-card kriteria-bpa p-4 p-lg-5 h-100">
                    <div class="d-flex align-items-center mb-5">
                        <div class="icon-box-omg me-4 shadow" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                            <i class="fa-solid fa-medal fa-2x text-white"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1 text-dark">Prestasi Akademik</h4>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-1 rounded-pill">Jalur BPA</span>
                        </div>
                    </div>
                    <ul class="list-unstyled kriteria-list mb-0">
                        <li class="py-3">
                            <span class="text-secondary fw-semibold"><i class="fa-solid fa-circle-check text-primary me-3"></i>Target IPK Minimal</span>
                            <span class="val-badge-omg border border-primary text-primary">3.25</span>
                        </li>
                        <li class="py-3">
                            <span class="text-secondary fw-semibold"><i class="fa-solid fa-circle-check text-primary me-3"></i>Batas Penghasilan Ortu</span>
                            <span class="val-badge-omg border">Rp 1.000.000</span>
                        </li>
                        <li class="py-3">
                            <span class="text-secondary fw-semibold"><i class="fa-solid fa-circle-check text-primary me-3"></i>Syarat Semester Aktif</span>
                            <span class="val-badge-omg border">2, 4, 6</span>
                        </li>
                        <li class="py-3">
                            <span class="text-secondary fw-semibold"><i class="fa-solid fa-circle-check text-primary me-3"></i>Level Kesejahteraan</span>
                            <span class="val-badge-omg border">Desil 1, 2, 3</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- LazisMU Card -->
            <div class="col-md-6">
                <div class="kriteria-omg-card kriteria-lazismu p-4 p-lg-5 h-100">
                    <div class="d-flex align-items-center mb-5">
                        <div class="icon-box-omg me-4 shadow" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
                            <i class="fa-solid fa-hand-holding-heart fa-2x text-white"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1 text-dark">Bantuan Sosial</h4>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-1 rounded-pill">Jalur LazisMU</span>
                        </div>
                    </div>
                    <ul class="list-unstyled kriteria-list mb-0">
                        <li class="py-3">
                            <span class="text-secondary fw-semibold"><i class="fa-solid fa-circle-check text-success me-3"></i>Batas IPK Maksimal</span>
                            <span class="val-badge-omg border border-success text-success">3.00</span>
                        </li>
                        <li class="py-3">
                            <span class="text-secondary fw-semibold"><i class="fa-solid fa-circle-check text-success me-3"></i>Batas Penghasilan Ortu</span>
                            <span class="val-badge-omg border">Rp 500.000</span>
                        </li>
                        <li class="py-3">
                            <span class="text-secondary fw-semibold"><i class="fa-solid fa-circle-check text-success me-3"></i>Syarat Semester Aktif</span>
                            <span class="val-badge-omg border">3, 4, 5, 6, 7, 8</span>
                        </li>
                        <li class="py-3">
                            <span class="text-secondary fw-semibold"><i class="fa-solid fa-circle-check text-success me-3"></i>Level Kesejahteraan</span>
                            <span class="val-badge-omg border">Desil 1, 2, 3</span>
                        </li>
                    </ul>
                </div>
            </div>
            
        </div>
    </div>
</div>