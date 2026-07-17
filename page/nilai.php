<?php
$update = (isset($_GET['action']) AND $_GET['action'] == 'update') ? true : false;
if ($update) {
	$sql = $connection->query("SELECT * FROM nilai WHERE kd_nilai='$_GET[key]'");
	$row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" AND isset($_POST["save"])) {
	$validasi = false; $err = false;
	if ($update) {
		// Update logic
		foreach ($_POST["nilai"] as $kd_kriteria => $nilai) {
			$q = $connection->query("SELECT kd_nilai FROM nilai WHERE kd_beasiswa=$_POST[kd_beasiswa] AND kd_kriteria=$kd_kriteria AND nim=$_POST[nim]");
			if ($q->num_rows > 0) {
				$connection->query("UPDATE nilai SET nilai='$nilai' WHERE kd_beasiswa=$_POST[kd_beasiswa] AND kd_kriteria=$kd_kriteria AND nim=$_POST[nim]");
			} else {
				$connection->query("INSERT INTO nilai VALUES (NULL, '$_POST[kd_beasiswa]', '$kd_kriteria', '$_POST[nim]', '$nilai')");
			}
		}
		$validasi = false; // already executed
	} else {
		$query = "INSERT INTO nilai VALUES ";
		foreach ($_POST["nilai"] as $kd_kriteria => $nilai) {
			$query .= "(NULL, '$_POST[kd_beasiswa]', '$kd_kriteria', '$_POST[nim]', '$nilai'),";
		}
		$sql = rtrim($query, ',');
		$validasi = true;
	}

	if ($validasi) {
		// Check for duplicate for this nim and beasiswa
		$q = $connection->query("SELECT kd_nilai FROM nilai WHERE kd_beasiswa=$_POST[kd_beasiswa] AND nim=$_POST[nim] LIMIT 1");
		if ($q->num_rows) {
			echo alert("Mahasiswa ".$_POST["nim"]." sudah memiliki nilai di beasiswa ini! Gunakan fitur edit.", "?page=nilai");
			$err = true;
		}
	}

  if (!$err) {
	if ($validasi) $connection->query($sql);
	echo alert("Berhasil disimpan!", "?page=nilai");
  }
}

if (isset($_GET['action']) AND $_GET['action'] == 'delete') {
	// Let's delete ALL criteria for this student's beasiswa entry, not just one row, so it cleans up properly.
	$sql = $connection->query("SELECT nim, kd_beasiswa FROM nilai WHERE kd_nilai='$_GET[key]'");
	if ($r = $sql->fetch_assoc()) {
		$connection->query("DELETE FROM nilai WHERE nim='$r[nim]' AND kd_beasiswa='$r[kd_beasiswa]'");
	}
	echo alert("Berhasil dihapus!", "?page=nilai");
}
?>
<div class="row">
	<div class="col-md-4">
	    <div class="card shadow-sm mb-4">
	        <div class="card-header bg-<?= ($update) ? "warning" : "primary text-white" ?>"><h5 class="text-center mb-0"><?= ($update) ? "EDIT" : "TAMBAH" ?> NILAI</h5></div>
	        <div class="card-body">
	            <form action="<?=$_SERVER["REQUEST_URI"]?>" method="post">
									<div class="mb-3">
										<label class="form-label" for="nim">Mahasiswa</label>
										<?php if ($_POST): ?>
											<input type="text" name="nim" value="<?=$_POST["nim"]?>" class="form-control" readonly="on">
										<?php else: ?>
											<select class="form-select" name="nim">
												<option>---</option>
												<?php $sql = $connection->query("SELECT * FROM mahasiswa"); while ($data = $sql->fetch_assoc()): ?>
													<option value="<?=$data["nim"]?>" <?= (!$update) ? "" : (($row["nim"] != $data["nim"]) ? "" : 'selected="selected"') ?>><?=$data["nim"]?> | <?=$data["nama"]?></option>
												<?php endwhile; ?>
											</select>
										<?php endif; ?>
									</div>
									<div class="mb-3">
	                  <label class="form-label" for="kd_beasiswa">Beasiswa</label>
										<?php if ($_POST): ?>
											<?php $q = $connection->query("SELECT nama FROM beasiswa WHERE kd_beasiswa=$_POST[kd_beasiswa]"); ?>
											<input type="text"value="<?=$q->fetch_assoc()["nama"]?>" class="form-control" readonly="on">
											<input type="hidden" name="kd_beasiswa" value="<?=$_POST["kd_beasiswa"]?>">
										<?php else: ?>
											<select class="form-select" name="kd_beasiswa" id="beasiswa">
												<option>---</option>
												<?php $sql = $connection->query("SELECT * FROM beasiswa"); while ($data = $sql->fetch_assoc()): ?>
													<option value="<?=$data["kd_beasiswa"]?>"<?= (!$update) ? "" : (($row["kd_beasiswa"] != $data["kd_beasiswa"]) ? "" : 'selected="selected"') ?>><?=$data["nama"]?></option>
												<?php endwhile; ?>
											</select>
										<?php endif; ?>
									</div>
									<?php if ($_POST): ?>
										<?php $q = $connection->query("SELECT * FROM kriteria WHERE kd_beasiswa=$_POST[kd_beasiswa]"); while ($r = $q->fetch_assoc()): ?>
				                <div class="mb-3">
					                  <label class="form-label" for="nilai"><?=ucfirst($r["nama"])?></label>
									  <input type="text" class="form-control" name="nilai[<?=$r["kd_kriteria"]?>]" placeholder="Masukkan nilai mentah (Misal: 3.25, 1000000, 2)" required>
				                </div>
										<?php endwhile; ?>
										<input type="hidden" name="save" value="true">
									<?php endif; ?>
	                <button type="submit" id="simpan" class="btn btn-<?= ($update) ? "warning" : "primary" ?> w-100 mb-2"><?=($_POST) ? "Simpan" : "Lanjut Isi Nilai"?></button>
	                <?php if ($update): ?>
										<a href="?page=nilai" class="btn btn-secondary w-100">Batal</a>
									<?php endif; ?>
	            </form>
	        </div>
	    </div>
	</div>
	<div class="col-md-8">
	    <div class="card shadow-sm">
	        <div class="card-header bg-primary text-white"><h5 class="text-center mb-0">DAFTAR NILAI MAHASISWA</h5></div>
	        <div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-hover table-striped mb-0 text-center align-middle">
						<thead>
							<tr>
								<th>No</th>
								<th>NIM</th>
								<th>Nama</th>
								<th>Beasiswa</th>
								<th>IPK</th>
								<th>Penghasilan</th>
								<th>SMT</th>
								<th>Kesejahteraan</th>
								<th>Opsi</th>
							</tr>
						</thead>
						<tbody>
							<?php $no = 1; ?>
							<?php 
							$sql_pivot = "SELECT 
											d.nim, 
											d.nama AS nama_mahasiswa, 
											c.nama AS nama_beasiswa, 
											c.kd_beasiswa,
											(SELECT kd_nilai FROM nilai WHERE nim=d.nim AND kd_beasiswa=c.kd_beasiswa LIMIT 1) AS key_edit,
											MAX(CASE WHEN b.nama = 'IPK' THEN a.nilai END) AS val_ipk,
											MAX(CASE WHEN b.nama = 'Penghasilan Orang Tua' THEN a.nilai END) AS val_penghasilan,
											MAX(CASE WHEN b.nama = 'Semester' THEN a.nilai END) AS val_semester,
											MAX(CASE WHEN b.nama = 'Pengelompokkan Kesejahteraan' THEN a.nilai END) AS val_kesejahteraan
										FROM nilai a 
										JOIN kriteria b ON a.kd_kriteria=b.kd_kriteria 
										JOIN beasiswa c ON a.kd_beasiswa=c.kd_beasiswa 
										JOIN mahasiswa d ON d.nim=a.nim
										GROUP BY d.nim, c.kd_beasiswa";
							if ($query = $connection->query($sql_pivot)): ?>
								<?php while($row = $query->fetch_assoc()): ?>
								<tr>
									<td><?=$no++?></td>
									<td class="text-start"><?=$row['nim']?></td>
									<td class="text-start fw-semibold"><?=$row['nama_mahasiswa']?></td>
									<td><span class="badge bg-info text-dark"><?=$row['nama_beasiswa']?></span></td>
									<td><?=$row['val_ipk']?></td>
									<td>Rp <?=number_format((float)$row['val_penghasilan'],0,',','.')?></td>
									<td><?=$row['val_semester']?></td>
									<td><?=$row['val_kesejahteraan']?></td>
									<td>
										<div class="btn-group btn-group-sm">
											<a href="?page=nilai&action=update&key=<?=$row['key_edit']?>" class="btn btn-warning">Edit</a>
											<a href="?page=nilai&action=delete&key=<?=$row['key_edit']?>" class="btn btn-danger">Hapus</a>
										</div>
									</td>
								</tr>
								<?php endwhile ?>
							<?php endif ?>
						</tbody>
					</table>
				</div>
	        </div>
	    </div>
	</div>
</div>
