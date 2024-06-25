<?php 
session_start();

// Tampungan dari proses login
$id = $_SESSION['id_user'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];


include '../../koneksi.php';

$idUser = $_POST['id_user'];

$tampilEditUser = $adminClass->tampilEditUser($idUser);

// Own Library
$lib = new lib();
$nav = $lib->navbarAdmin('hidden','hidden','hidden',$username);
$mode = $lib->toggleMode();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Icon Title -->
	<link rel="icon" href="../../style/logo/logo.png" type="image/x-icon">
	<!-- Bootstrap Icons -->
	<link rel="stylesheet" href="../../style/assets/bootstrap/bootstrap-icons.css">
	<link rel="stylesheet" href="../../style/assets/bootstrap/bootstrap-icons.min.css">
	<!-- Style Bootstrap -->
	<link rel="stylesheet" href="../../style/assets/bootstrap/bootstrap.min.css">
	<script src="../../style/assets/bootstrap/bootstrap.bundle.min.js"></script>
	<!-- Style CSS -->
	<link rel="stylesheet" href="../../style/style.css">
	<!-- SweetAlert2 -->
	<link rel="stylesheet" href="../../style/assets/sweetalert/sweetalert2.min.css">
	<link rel="stylesheet" href="../../style/assets/sweetalert/animate.min.css">
	<script src="../../style/assets/sweetalert/sweetalert2.min.js"></script>
	<!-- Color Modes Bootstrap -->
	<script src="/perpus/script/color-modes.js"></script>
	<title>Edit Genre - Admin</title>
</head>
<body>
	<?php
	foreach ($tampilEditUser as $d) {
		?>
		<!-- <section>
			<form action="proses.php" class="form-data" method="POST">
				<input type="text" hidden name="id_user" id="id_user" value="<?= $d['id_user']?>">
				<label for="namaLengkap">Nama Lengkap</label>
				<input type="text" name="nama_lengkap" id="namaLengkap" value="<?= $d['nama_lengkap']?>"><br>
				<label for="username">Username</label>
				<input type="text" name="username" id="username" value="<?= $d['username']?>"><br>
				<label for="pw">Password</label>
				<input type="password" name="pw" id="pw" value="<?= $d['password']?>"><br>
				<label for="email">E - Mail</label>
				<input type="text" name="email" id="email" value="<?= $d['email']?>"><br>
				<label for="alamat">Alamat</label>
				<textarea name="alamat" id="alamat" cols="10" rows="3"><?= $d['alamat']?></textarea><br>
				<label for="role">Role</label>
				<select name="role" id="role">
					<option value selected disabled hidden><?= $d['role']?></option>
					<option value="1">Administrator</option>
					<option value="2">Petugas</option>
				</select><br>
				<input type="submit" name="btn" value="Edit" class="btn btn-primary">
			</form>
		</section> -->
		<section>
			<div class="container  p-4">
				<form action="proses.php" class="form-data" method="POST">
					<input type="text" hidden name="id_user" id="id_user" value="<?= $d['id_user']?>">
					<div class="row mb-3">
						<div class="col text-center">
							<h2 class="d-inline ">Tambah Akun Pegawai</h2>
							<a href="./" class="bi bi-box-arrow-left fs-2 ms-2"></a>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col">
							<div class="form-floating">
								<input type="text" name="nama_lengkap" id="namaLengkap" placeholder="" class="form-control" value="<?= $d['nama_lengkap']?>" required>
								<label for="namaLengkap">Nama Lengkap</label>
							</div>
						</div>
						<div class="col">
							<div class="form-floating">
								<input type="text" name="email" id="email" placeholder="" class="form-control" value="<?= $d['email']?>" required>
								<label for="email">E - Mail</label>
							</div>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col">
							<div class="form-floating">
								<input type="text" name="username" id="username" placeholder="" class="form-control" value="<?= $d['username']?>" required>
								<label for="username">Username</label>
							</div>
						</div>
						<div class="col">
							<div class="form-floating">
								<input type="password" name="pw" id="pw" placeholder="" class="form-control" value="<?= $d['password']?>" required>
								<label for="pw">Password</label>
							</div>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col">
							<div class="form-floating">
								<textarea name="alamat" id="alamat" cols="10" rows="3" class="form-control" placeholder=""> <?= $d['alamat']?></textarea>
								<label for="alamat">Alamat</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col"></div>
						<div class="col">
							<select name="role" id="role" class="form-select">
								<option value selected disabled hidden><?= $d['role']?></option>
								<option value="1">Administrator</option>
								<option value="2">Petugas</option>
							</select>
						</div>
						<div class="col"></div>
					</div>
					<div class="row">
						<div class="col text-end">
							<input type="reset" name="btn" value="Reset" class="btn btn-danger">
							<input type="submit" name="btn" value="Edit" class="btn btn-primary">
						</div>
					</div>
				</form>
			</div>
		</section>
		<?php
	}
	?>
</body>
</html>