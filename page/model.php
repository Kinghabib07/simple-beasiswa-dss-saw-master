<?php
$update = (isset($_GET['action']) AND $_GET['action'] == 'update') ? true : false;
if ($update) {
	$sql = $connection->query("SELECT * FROM model WHERE kd_model='$_GET[key]'");
	$row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$validasi = false; $err = false;
	if ($update) {
		$sql = "UPDATE model SET kd_kriteria='$_POST[kd_kriteria]', kd_beasiswa='$_POST[kd_beasiswa]', bobot='$_POST[bobot]' WHERE kd_model='$_GET[key]'";
	} else {
		$sql = "INSERT INTO model VALUES (NULL, '$_POST[kd_beasiswa]', '$_POST[kd_kriteria]', '$_POST[bobot]')";
		$validasi = true;
	}

	if ($validasi) {
		$q = $connection->query("SELECT kd_model FROM model WHERE kd_beasiswa=$_POST[kd_beasiswa] AND kd_kriteria=$_POST[kd_kriteria] AND bobot LIKE '%$_POST[bobot]%'");
		if ($q->num_rows) {
			echo alert("Model sudah ada!", "?page=model");
			$err = true;
		}
	}

  if (!$err AND $connection->query($sql)) {
		echo alert("Berhasil!", "?page=model");
	} else {
		echo alert("Gagal!", "?page=model");
	}
}

if (isset($_GET['action']) AND $_GET['action'] == 'delete') {
  $connection->query("DELETE FROM model WHERE kd_model='$_GET[key]'");
	echo alert("Berhasil!", "?page=model");
}
?>
<div class="row">
	<div class="col-md-4">
	    <div class="card shadow-sm mb-4 border-0">
	        <div class="card-header bg-<?= ($update) ? "warning" : "primary text-white" ?> border-0 pt-4 pb-3">
                <h5 class="text-center mb-0 fw-bold"><i class="fa-solid <?= ($update) ? "fa-pen-to-square" : "fa-weight-scale" ?> me-2"></i><?= ($update) ? "EDIT" : "TAMBAH" ?> NILAI BOBOT</h5>
            </div>
	        <div class="card-body bg-light">
	            <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
									<div class="mb-3">
	                  <label class="form-label fw-semibold text-muted" for="kd_beasiswa">Jenis Beasiswa</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-graduation-cap text-muted"></i></span>
                                            <select class="form-select border-start-0 ps-0" name="kd_beasiswa" id="beasiswa">
                                                <option>--- Pilih Beasiswa ---</option>
                                                <?php $sql = $connection->query("SELECT * FROM beasiswa") ?>
                                                <?php while ($data = $sql->fetch_assoc()): ?>
                                                    <option value="<?=$data["kd_beasiswa"]?>"<?= (!$update) ?: (($row["kd_beasiswa"] != $data["kd_beasiswa"]) ?: ' selected="on"') ?>><?=$data["nama"]?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
									</div>
									<div class="mb-3">
	                  <label class="form-label fw-semibold text-muted" for="kd_kriteria">Kriteria</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-list-check text-muted"></i></span>
                                            <select class="form-select border-start-0 ps-0" name="kd_kriteria" id="kriteria">
                                                <option>--- Pilih Kriteria ---</option>
                                                <?php $sql = $connection->query("SELECT * FROM kriteria") ?>
                                                <?php while ($data = $sql->fetch_assoc()): ?>
                                                    <option value="<?=$data["kd_kriteria"]?>" class="<?=$data["kd_beasiswa"]?>"<?= (!$update) ?: (($row["kd_kriteria"] != $data["kd_kriteria"]) ?: ' selected="on"') ?>><?=$data["nama"]?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
									</div>
	                <div class="mb-4">
	                    <label class="form-label fw-semibold text-muted" for="bobot">Bobot <small class="text-primary">(Contoh: 0.45)</small></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-percent text-muted"></i></span>
                            <input type="text" name="bobot" class="form-control border-start-0 ps-0" <?= (!$update) ?: 'value="'.$row["bobot"].'"' ?> placeholder="0.00">
                        </div>
	                </div>
	                <button type="submit" class="btn btn-<?= ($update) ? "warning" : "primary" ?> w-100 mb-2 fw-bold shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Data
                    </button>
	                <?php if ($update): ?>
						<a href="?page=model" class="btn btn-secondary w-100 fw-bold shadow-sm"><i class="fa-solid fa-xmark me-2"></i> Batal</a>
					<?php endif; ?>
	            </form>
	        </div>
	    </div>
	</div>
	<div class="col-md-8">
	    <div class="card shadow-sm border-0">
	        <div class="card-header bg-white border-bottom pt-4 pb-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-scale-balanced text-primary me-2"></i> DAFTAR NILAI BOBOT</h5>
            </div>
	        <div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0">
						<thead class="table-light">
							<tr>
								<th class="ps-4">No</th>
								<th>Beasiswa</th>
								<th>Kriteria</th>
								<th>Nilai Bobot</th>
								<th class="text-center">Opsi</th>
							</tr>
						</thead>
						<tbody>
							<?php $no = 1; ?>
							<?php if ($query = $connection->query("SELECT c.nama AS nama_beasiswa, b.nama AS nama_kriteria, a.bobot, a.kd_model FROM model a JOIN kriteria b ON a.kd_kriteria=b.kd_kriteria JOIN beasiswa c ON a.kd_beasiswa=c.kd_beasiswa")): ?>
								<?php while($row = $query->fetch_assoc()): ?>
								<tr>
									<td class="ps-4"><?=$no++?></td>
									<td><span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill"><?=$row['nama_beasiswa']?></span></td>
									<td class="fw-semibold text-dark"><?=$row['nama_kriteria']?></td>
									<td>
                                        <div class="d-inline-flex align-items-center bg-light px-3 py-1 rounded border">
                                            <span class="fw-bold text-success fs-6"><?=$row['bobot']?></span>
                                        </div>
                                    </td>
									<td class="text-center">
										<div class="btn-group btn-group-sm shadow-sm">
											<a href="?page=model&action=update&key=<?=$row['kd_model']?>" class="btn btn-warning" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
											<a href="?page=model&action=delete&key=<?=$row['kd_model']?>" class="btn btn-danger" title="Hapus"><i class="fa-solid fa-trash"></i></a>
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

<script type="text/javascript">
$("#kriteria").chained("#beasiswa");
</script>
