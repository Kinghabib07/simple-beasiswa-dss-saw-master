<?php
$update = (isset($_GET['action']) AND $_GET['action'] == 'update') ? true : false;
if ($update) {
	$sql = $connection->query("SELECT * FROM beasiswa WHERE kd_beasiswa='$_GET[key]'");
	$row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$validasi = false; $err = false;
	if ($update) {
		$sql = "UPDATE beasiswa SET nama='$_POST[nama]' WHERE kd_beasiswa='$_GET[key]'";
	} else {
		$sql = "INSERT INTO beasiswa VALUES (NULL, '$_POST[nama]')";
		$validasi = true;
	}

	if ($validasi) {
		$q = $connection->query("SELECT kd_beasiswa FROM beasiswa WHERE nama LIKE '%$_POST[nama]%'");
		if ($q->num_rows) {
			echo alert("Beasiswa sudah ada!", "?page=beasiswa");
			$err = true;
		}
	}

  if (!$err AND $connection->query($sql)) {
    echo alert("Berhasil!", "?page=beasiswa");
  } else {
		echo alert("Gagal!", "?page=beasiswa");
  }
}

if (isset($_GET['action']) AND $_GET['action'] == 'delete') {
  $connection->query("DELETE FROM beasiswa WHERE kd_beasiswa='$_GET[key]'");
	echo alert("Berhasil!", "?page=beasiswa");
}
?>
<div class="row">
	<div class="col-md-4">
	    <div class="card shadow-sm mb-4">
	        <div class="card-header bg-<?= ($update) ? "warning" : "primary text-white" ?>">
	            <h5 class="text-center mb-0 fw-bold"><?= ($update) ? "EDIT" : "TAMBAH" ?> JENIS BEASISWA</h5>
	        </div>
	        <div class="card-body">
	            <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
	                <div class="mb-3">
	                    <label class="form-label fw-semibold" for="nama">Nama Beasiswa</label>
	                    <input type="text" name="nama" class="form-control" <?= (!$update) ?: 'value="'.$row["nama"].'"' ?> placeholder="Masukkan nama beasiswa">
	                </div>
	                <button type="submit" class="btn btn-<?= ($update) ? "warning" : "primary" ?> w-100 mb-2">
	                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
	                </button>
	                <?php if ($update): ?>
						<a href="?page=beasiswa" class="btn btn-secondary w-100"><i class="fa-solid fa-xmark me-1"></i> Batal</a>
					<?php endif; ?>
	            </form>
	        </div>
	    </div>
	</div>
	<div class="col-md-8">
	    <div class="card shadow-sm">
	        <div class="card-header bg-primary text-white">
	            <h5 class="text-center mb-0 fw-bold">DAFTAR BEASISWA</h5>
	        </div>
	        <div class="card-body p-0">
	            <div class="table-responsive">
	                <table class="table table-hover table-striped mb-0 align-middle">
	                    <thead>
	                        <tr>
	                            <th class="ps-3">No</th>
	                            <th>Nama Beasiswa</th>
	                            <th class="text-center">Opsi</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        <?php $no = 1; ?>
	                        <?php if ($query = $connection->query("SELECT * FROM beasiswa")): ?>
	                            <?php while($row = $query->fetch_assoc()): ?>
	                            <tr>
	                                <td class="ps-3"><?=$no++?></td>
	                                <td class="fw-semibold"><?=$row['nama']?></td>
	                                <td class="text-center">
	                                    <div class="btn-group btn-group-sm">
	                                        <a href="?page=beasiswa&action=update&key=<?=$row['kd_beasiswa']?>" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
	                                        <a href="?page=beasiswa&action=delete&key=<?=$row['kd_beasiswa']?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Hapus</a>
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
