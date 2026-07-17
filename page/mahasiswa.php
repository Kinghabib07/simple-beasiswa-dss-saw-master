<?php
$update = (isset($_GET['action']) AND $_GET['action'] == 'update') ? true : false;
if ($update) {
	$sql = $connection->query("SELECT * FROM mahasiswa WHERE nim='$_GET[key]'");
	$row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$validasi = false; $err = false;
	if ($update) {
		$sql = "UPDATE mahasiswa SET nim='$_POST[nim]', nama='$_POST[nama]', alamat='$_POST[alamat]', jenis_kelamin='$_POST[jenis_kelamin]', tahun_mengajukan='".date("Y")."' WHERE nim='$_GET[key]'";
	} else {
		$sql = "INSERT INTO mahasiswa VALUES ('$_POST[nim]', '$_POST[nama]', '$_POST[alamat]', '$_POST[jenis_kelamin]', '".date("Y")."')";
		$validasi = true;
	}

	if ($validasi) {
		$q = $connection->query("SELECT nim FROM mahasiswa WHERE nim=$_POST[nim]");
		if ($q->num_rows) {
			echo alert($_POST["nim"]." sudah terdaftar!", "?page=mahasiswa");
			$err = true;
		}
	}

  if (!$err AND $connection->query($sql)) {
    echo alert("Berhasil!", "?page=mahasiswa");
  } else {
		echo alert("Gagal!", "?page=mahasiswa");
  }
}

if (isset($_GET['action']) AND $_GET['action'] == 'delete') {
  $connection->query("DELETE FROM mahasiswa WHERE nim=$_GET[key]");
	echo alert("Berhasil!", "?page=mahasiswa");
}
?>
<div class="row">
	<div class="col-md-4">
	    <div class="card shadow-sm mb-4 border-0">
	        <div class="card-header bg-<?= ($update) ? "warning" : "primary text-white" ?> border-0 pt-4 pb-3">
                <h5 class="text-center mb-0 fw-bold"><i class="fa-solid <?= ($update) ? "fa-pen-to-square" : "fa-user-plus" ?> me-2"></i><?= ($update) ? "EDIT" : "TAMBAH" ?> MAHASISWA</h5>
            </div>
	        <div class="card-body bg-light">
	            <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
	                <div class="mb-3">
	                    <label class="form-label fw-semibold text-muted" for="nim">NIM</label>
	                    <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-id-card text-muted"></i></span>
                            <input type="text" name="nim" class="form-control border-start-0 ps-0" <?= (!$update) ?: 'value="'.$row["nim"].'"' ?> placeholder="Masukkan NIM...">
                        </div>
	                </div>
	                <div class="mb-3">
	                    <label class="form-label fw-semibold text-muted" for="nama">Nama Lengkap</label>
	                    <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                            <input type="text" name="nama" class="form-control border-start-0 ps-0" <?= (!$update) ?: 'value="'.$row["nama"].'"' ?> placeholder="Masukkan Nama Lengkap...">
                        </div>
	                </div>
	                <div class="mb-3">
	                    <label class="form-label fw-semibold text-muted" for="alamat">Alamat</label>
	                    <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-map-location-dot text-muted"></i></span>
                            <input type="text" name="alamat" class="form-control border-start-0 ps-0" <?= (!$update) ?: 'value="'.$row["alamat"].'"' ?> placeholder="Masukkan Alamat...">
                        </div>
	                </div>
					<div class="mb-4">
	                  <label class="form-label fw-semibold text-muted" for="jenis_kelamin">Jenis Kelamin</label>
                      <div class="input-group">
                          <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-venus-mars text-muted"></i></span>
                          <select class="form-select border-start-0 ps-0" name="jenis_kelamin">
                              <option>--- Pilih Jenis Kelamin ---</option>
                              <option value="Laki-laki" <?= (!$update) ?: (($row["jenis_kelamin"] != "Laki-laki") ?: 'selected="on"') ?>>Laki-laki</option>
                              <option value="Perempuan" <?= (!$update) ?: (($row["jenis_kelamin"] != "Perempuan") ?: 'selected="on"') ?>>Perempuan</option>
                          </select>
                      </div>
					</div>
	                <button type="submit" class="btn btn-<?= ($update) ? "warning" : "primary" ?> w-100 mb-2 fw-bold shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Data
                    </button>
	                <?php if ($update): ?>
						<a href="?page=mahasiswa" class="btn btn-secondary w-100 fw-bold shadow-sm"><i class="fa-solid fa-xmark me-2"></i> Batal</a>
					<?php endif; ?>
	            </form>
	        </div>
	    </div>
	</div>
	<div class="col-md-8">
	    <div class="card shadow-sm border-0">
	        <div class="card-header bg-white border-bottom pt-4 pb-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-users text-primary me-2"></i> DAFTAR MAHASISWA</h5>
            </div>
	        <div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0">
						<thead class="table-light">
							<tr>
								<th class="ps-4">No</th>
								<th>NIM</th>
								<th>Nama Lengkap</th>
								<th>Alamat</th>
								<th>Gender</th>
								<th>Tahun</th>
								<th class="text-center">Opsi</th>
							</tr>
						</thead>
						<tbody>
							<?php $no = 1; ?>
							<?php if ($query = $connection->query("SELECT * FROM mahasiswa")): ?>
								<?php while($row = $query->fetch_assoc()): ?>
								<tr>
									<td class="ps-4"><?=$no++?></td>
									<td><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill"><?=$row['nim']?></span></td>
									<td class="fw-semibold text-dark"><?=$row['nama']?></td>
									<td class="text-muted small"><?=$row['alamat']?></td>
									<td>
                                        <?php if($row['jenis_kelamin'] == 'Laki-laki'): ?>
                                            <span class="text-primary"><i class="fa-solid fa-mars me-1"></i> L</span>
                                        <?php else: ?>
                                            <span class="text-danger"><i class="fa-solid fa-venus me-1"></i> P</span>
                                        <?php endif; ?>
                                    </td>
									<td><span class="badge bg-info text-dark"><?=$row['tahun_mengajukan']?></span></td>
									<td class="text-center">
										<div class="btn-group btn-group-sm shadow-sm">
											<a href="?page=mahasiswa&action=update&key=<?=$row['nim']?>" class="btn btn-warning" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
											<a href="?page=mahasiswa&action=delete&key=<?=$row['nim']?>" class="btn btn-danger" title="Hapus"><i class="fa-solid fa-trash"></i></a>
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
