<?php
$update = (isset($_GET['action']) AND $_GET['action'] == 'update') ? true : false;
if ($update) {
	$sql = $connection->query("SELECT * FROM kriteria WHERE kd_kriteria='$_GET[key]'");
	$row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$validasi = false; $err = false;
	if ($update) {
		$sql = "UPDATE kriteria SET kd_beasiswa=$_POST[kd_beasiswa], nama='$_POST[nama]', sifat='$_POST[sifat]' WHERE kd_kriteria='$_GET[key]'";
	} else {
		$sql = "INSERT INTO kriteria VALUES (NULL, $_POST[kd_beasiswa], '$_POST[nama]', '$_POST[sifat]')";
		$validasi = true;
	}

	if ($validasi) {
		$q = $connection->query("SELECT kd_kriteria FROM kriteria WHERE kd_beasiswa=$_POST[kd_beasiswa] AND nama LIKE '%$_POST[nama]%'");
		if ($q->num_rows) {
			echo alert("Kriteri sudah ada!", "?page=kriteria");
			$err = true;
		}
	}

  if (!$err AND $connection->query($sql)) {
		echo alert("Berhasil!", "?page=kriteria");
	} else {
		echo alert("Gagal!", "?page=kriteria");
	}
}

if (isset($_GET['action']) AND $_GET['action'] == 'delete') {
  $connection->query("DELETE FROM kriteria WHERE kd_kriteria='$_GET[key]'");
	echo alert("Berhasil!", "?page=kriteria");
}
?>
<div class="row">
	<div class="col-md-4">
	    <div class="card shadow-sm mb-4">
	        <div class="card-header bg-<?= ($update) ? "warning" : "primary text-white" ?>">
	            <h5 class="text-center mb-0 fw-bold"><?= ($update) ? "EDIT" : "TAMBAH" ?> KRITERIA</h5>
	        </div>
	        <div class="card-body">
	            <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
					<div class="mb-3">
						<label class="form-label fw-semibold" for="kd_beasiswa">Beasiswa</label>
						<select class="form-select" name="kd_beasiswa">
							<option>---</option>
							<?php $query = $connection->query("SELECT * FROM beasiswa"); while ($data = $query->fetch_assoc()): ?>
								<option value="<?=$data["kd_beasiswa"]?>" <?= (!$update) ?: (($row["kd_beasiswa"] != $data["kd_beasiswa"]) ?: 'selected="on"') ?>><?=$data["nama"]?></option>
							<?php endwhile; ?>
						</select>
					</div>
	                <div class="mb-3">
	                    <label class="form-label fw-semibold" for="nama">Nama Kriteria</label>
	                    <input type="text" name="nama" class="form-control" <?= (!$update) ?: 'value="'.$row["nama"].'"' ?> placeholder="Misal: IPK, Semester">
	                </div>
					<div class="mb-4">
	                  <label class="form-label fw-semibold" for="sifat">Sifat (Cost/Benefit)</label>
						<select class="form-select" name="sifat">
							<option>---</option>
							<option value="min" <?= (!$update) ?: (($row["sifat"] != "min") ?: 'selected="on"') ?>>Min (Semakin kecil semakin baik)</option>
							<option value="max" <?= (!$update) ?: (($row["sifat"] != "max") ?: 'selected="on"') ?>>Max (Semakin besar semakin baik)</option>
						</select>
					</div>
	                <button type="submit" class="btn btn-<?= ($update) ? "warning" : "primary" ?> w-100 mb-2">
	                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
	                </button>
	                <?php if ($update): ?>
						<a href="?page=kriteria" class="btn btn-secondary w-100"><i class="fa-solid fa-xmark me-1"></i> Batal</a>
					<?php endif; ?>
	            </form>
	        </div>
	    </div>
	</div>
	<div class="col-md-8">
	    <div class="card shadow-sm">
	        <div class="card-header bg-primary text-white">
	            <h5 class="text-center mb-0 fw-bold">DAFTAR KRITERIA</h5>
	        </div>
	        <div class="card-body p-0">
	            <div class="table-responsive">
	                <table class="table table-hover table-striped mb-0 align-middle">
	                    <thead>
	                        <tr>
	                            <th class="ps-3">No</th>
	                            <th>Beasiswa</th>
	                            <th>Kriteria</th>
	                            <th>Sifat</th>
	                            <th class="text-center">Opsi</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        <?php $no = 1; ?>
	                        <?php if ($query = $connection->query("SELECT a.nama AS kriteria, b.nama AS beasiswa, a.kd_kriteria, a.sifat FROM kriteria a JOIN beasiswa b USING(kd_beasiswa)")): ?>
	                            <?php while($row = $query->fetch_assoc()): ?>
	                            <tr>
	                                <td class="ps-3"><?=$no++?></td>
	                                <td><span class="badge bg-info text-dark"><?=$row['beasiswa']?></span></td>
	                                <td class="fw-semibold"><?=$row['kriteria']?></td>
	                                <td>
	                                    <?php if($row['sifat'] == 'max'): ?>
	                                        <span class="badge bg-success">Max</span>
	                                    <?php else: ?>
	                                        <span class="badge bg-danger">Min</span>
	                                    <?php endif; ?>
	                                </td>
	                                <td class="text-center">
	                                    <div class="btn-group btn-group-sm">
	                                        <a href="?page=kriteria&action=update&key=<?=$row['kd_kriteria']?>" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
	                                        <a href="?page=kriteria&action=delete&key=<?=$row['kd_kriteria']?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Hapus</a>
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
