<style>
    .rank-medal {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        font-weight: 700;
        font-size: 1rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .rank-1 { background: linear-gradient(45deg, #FFD700, #FDB931); color: #855a00; border: 2px solid #fff; }
    .rank-2 { background: linear-gradient(45deg, #E0E0E0, #BDBDBD); color: #424242; border: 2px solid #fff; }
    .rank-3 { background: linear-gradient(45deg, #CD7F32, #A0522D); color: #fff; border: 2px solid #fff; }
    .rank-other { background: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6; box-shadow: none;}
    
    .score-badge {
        font-size: 1.1rem;
        font-weight: 800;
        padding: 8px 15px;
        border-radius: 8px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        color: #800000;
    }
</style>

<div class="row">
	<div class="col-md-12">
	<?php if (isset($_GET["beasiswa"])) {
		$kd_beasiswa = intval($_GET["beasiswa"]);
		$sqlKriteria = "";
		$namaKriteria = [];
		$queryKriteria = $connection->query("SELECT a.kd_kriteria, a.nama FROM kriteria a JOIN model b USING(kd_kriteria) WHERE b.kd_beasiswa=$kd_beasiswa");
		while ($kr = $queryKriteria->fetch_assoc()) {
			$sqlKriteria .= "SUM(
				IF(
					c.kd_kriteria=".$kr["kd_kriteria"].",
					IF(c.sifat='max', nilai.nilai/c.normalization, c.normalization/nilai.nilai), 0
				)
			) AS ".strtolower(str_replace(" ", "_", $kr["nama"])).",";
			$namaKriteria[] = strtolower(str_replace(" ", "_", $kr["nama"]));
		}
		
		$sql = "SELECT
			(SELECT nama FROM mahasiswa WHERE nim=mhs.nim) AS nama,
			(SELECT nim FROM mahasiswa WHERE nim=mhs.nim) AS nim,
			(SELECT tahun_mengajukan FROM mahasiswa WHERE nim=mhs.nim) AS tahun,
			$sqlKriteria
			SUM(
				IF(
						c.sifat = 'max',
						nilai.nilai / c.normalization,
						c.normalization / nilai.nilai
				) * c.bobot
			) AS rangking,
			(SELECT nilai FROM nilai n JOIN kriteria k USING(kd_kriteria) WHERE n.nim=mhs.nim AND k.kd_beasiswa=$kd_beasiswa AND k.nama='IPK' LIMIT 1) AS val_ipk,
			(SELECT nilai FROM nilai n JOIN kriteria k USING(kd_kriteria) WHERE n.nim=mhs.nim AND k.kd_beasiswa=$kd_beasiswa AND k.nama='Penghasilan Orang Tua' LIMIT 1) AS val_penghasilan,
			(SELECT nilai FROM nilai n JOIN kriteria k USING(kd_kriteria) WHERE n.nim=mhs.nim AND k.kd_beasiswa=$kd_beasiswa AND k.nama='Semester' LIMIT 1) AS val_semester,
			(SELECT nilai FROM nilai n JOIN kriteria k USING(kd_kriteria) WHERE n.nim=mhs.nim AND k.kd_beasiswa=$kd_beasiswa AND k.nama='Pengelompokkan Kesejahteraan' LIMIT 1) AS val_kesejahteraan
		FROM
			nilai
			JOIN mahasiswa mhs USING(nim)
			JOIN (
				SELECT
						nilai.kd_kriteria AS kd_kriteria,
						kriteria.sifat AS sifat,
						(
							SELECT bobot FROM model WHERE kd_kriteria=kriteria.kd_kriteria AND kd_beasiswa=beasiswa.kd_beasiswa
						) AS bobot,
						ROUND(
							IF(kriteria.sifat='max', MAX(nilai.nilai), MIN(nilai.nilai)), 1
						) AS normalization
					FROM nilai
					JOIN kriteria USING(kd_kriteria)
					JOIN beasiswa ON kriteria.kd_beasiswa=beasiswa.kd_beasiswa
					WHERE beasiswa.kd_beasiswa=$kd_beasiswa
				GROUP BY nilai.kd_kriteria
			) c USING(kd_kriteria)
		WHERE kd_beasiswa=$kd_beasiswa
		GROUP BY nilai.nim
		ORDER BY rangking DESC"; 
		?>
	  <div class="card border-0 shadow" style="border-radius: 15px; overflow: hidden;">
	      <div class="card-header bg-white border-bottom-0 pt-4 pb-3 text-center">
              <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-circle mb-3" style="width: 60px; height: 60px;">
                  <i class="fa-solid fa-trophy fa-2x"></i>
              </div>
			  <h3 class="fw-bold text-dark mb-1">
				  <?php $query = $connection->query("SELECT * FROM beasiswa WHERE kd_beasiswa=$kd_beasiswa"); echo $query->fetch_assoc()["nama"]; ?>
			  </h3>
			  <p class="text-muted mb-0">Hasil Perhitungan & Peringkat Rekomendasi SAW</p>
		  </div>
	      <div class="card-body p-0">
	          <div class="table-responsive">
				<table class="table table-hover align-middle mb-0 text-center">
					<thead style="background-color: #f8f9fa;">
						<tr>
							<th width="15%" class="py-3">Peringkat</th>
							<th width="15%" class="py-3 text-start">NIM</th>
							<th width="30%" class="py-3 text-start">Nama Mahasiswa</th>
							<th width="20%" class="py-3">Status Kelayakan</th>
							<th width="20%" class="py-3">Nilai SAW</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$query = $connection->query($sql); 
						$rank = 1;
						$has_data = false;
						while($row = $query->fetch_assoc()): 
							$has_data = true;
							$rangking = number_format((float) $row["rangking"], 4, '.', '');
							
							// Logic Filtering sesuai spesifikasi baru
							$is_layak = false;
							$alasan_tidak_layak = "";
							
							$ipk = floatval($row['val_ipk']);
							$penghasilan = floatval($row['val_penghasilan']);
							$semester = intval($row['val_semester']);
							$kesejahteraan = intval($row['val_kesejahteraan']);
							
							if ($kd_beasiswa == 1) { // BPA
								$semesters_allowed = [2, 4, 6];
								$kesejahteraan_allowed = [1, 2, 3];
								if ($ipk >= 3.25 && $penghasilan <= 1000000 && in_array($semester, $semesters_allowed) && in_array($kesejahteraan, $kesejahteraan_allowed)) {
									$is_layak = true;
								} else {
									$alasan_tidak_layak = "Tidak memenuhi batas kriteria BPA.";
								}
							} else if ($kd_beasiswa == 2) { // LazisMU
								$semesters_allowed = [3, 4, 5, 6, 7, 8];
								$kesejahteraan_allowed = [1, 2, 3];
								if ($ipk <= 3.00 && $penghasilan <= 500000 && in_array($semester, $semesters_allowed) && in_array($kesejahteraan, $kesejahteraan_allowed)) {
									$is_layak = true;
								} else {
									$alasan_tidak_layak = "Tidak memenuhi batas kriteria LazisMU.";
								}
							}

							$q = $connection->query("SELECT nim FROM hasil WHERE nim='$row[nim]' AND kd_beasiswa='$kd_beasiswa' AND tahun='$row[tahun]'");
							if (!$q->num_rows) {
								$connection->query("INSERT INTO hasil VALUES(NULL, '$kd_beasiswa', '$row[nim]', '".$rangking."', '$row[tahun]')");
							}
                            
                            $medalClass = ($rank <= 3 && $is_layak) ? "rank-".$rank : "rank-other";
						?>
						<tr class="<?= $is_layak ? '' : 'opacity-50 bg-light' ?>" style="transition: all 0.2s;">
							<td class="py-3">
								<div class="rank-medal <?= $medalClass ?>">
									<?= $rank++ ?>
								</div>
							</td>
							<td class="text-start"><span class="fw-bold text-muted"><?=$row["nim"]?></span></td>
							<td class="text-start">
                                <div class="d-flex align-items-center">
                                    <div class="bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fa-solid fa-user text-secondary"></i>
                                    </div>
                                    <span class="fw-bold text-dark" style="font-size: 1.05rem;"><?=$row["nama"]?></span>
                                </div>
                            </td>
							<td>
								<?php if($is_layak): ?>
									<span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill"><i class="fa-solid fa-check-circle me-1"></i> Direkomendasikan</span>
								<?php else: ?>
									<span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill" title="<?= $alasan_tidak_layak ?>"><i class="fa-solid fa-xmark-circle me-1"></i> Gugur Seleksi</span>
								<?php endif; ?>
							</td>
							<td>
								<div class="score-badge">
                                    <i class="fa-solid fa-star text-warning me-1 small"></i> <?=$rangking?>
                                </div>
							</td>
						</tr>
						<?php endwhile;?>
						
						<?php if(!$has_data): ?>
						<tr>
							<td colspan="5" class="text-center py-5 text-muted">
								<i class="fa-solid fa-folder-open fa-4x mb-3 text-light"></i>
								<h5 class="fw-bold text-secondary">Belum Ada Data</h5>
                                <p class="mb-0">Silakan input nilai mahasiswa terlebih dahulu untuk melihat hasil perhitungan.</p>
							</td>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>
			  </div>
	      </div>
	  </div>
	<?php } else { ?>
		<div class="text-center py-5 mt-5">
			<div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-4 shadow-sm" style="width: 100px; height: 100px;">
                <i class="fa-solid fa-ranking-star fa-3x"></i>
            </div>
			<h2 class="fw-bold text-dark">Hasil Perhitungan SAW</h2>
			<p class="text-muted lead" style="max-width: 500px; margin: 0 auto;">Pilih jenis beasiswa pada menu navigasi samping (Sidebar) untuk melihat daftar peringkat mahasiswa.</p>
		</div>
	<?php } ?>
	</div>
</div>
