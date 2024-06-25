<?php
session_start();

// Tampungan dari proses login
$id = $_SESSION['id_user'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

if ($role === 'Administrator') {
  $roleFolder = "admin";
}elseif ($role === 'Petugas') {
  $roleFolder = "petugas";
}else{
  $roleFolder = "peminjam";
}

include '../../koneksi.php';

// Jika pengguna belum melakukan login
if(!isset($_SESSION['username'])){
  header('location: /perpus/form/login.php?pesan=belum_login');
  exit;
}


if (!admin()) {
  header("location: /perpus/$roleFolder/?pesan=noaccess");
}

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
	<title>Tambah Akun - Admin</title>
</head>
<body>
	<!-- Pop Up Sweetalert Pesan -->
	<script>
		<?php include '../../script/pesan.js' ?>
	</script>
	<section>
		<div class="container  p-4">
			<form action="proses.php" class="form-data" method="POST">
				<div class="row mb-3">
					<div class="col text-center">
						<h2 class="d-inline ">Tambah Akun Pegawai</h2>
						<a href="./" class="bi bi-box-arrow-left fs-2 ms-2"></a>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col">
						<div class="form-floating">
							<input type="text" name="nama_lengkap" id="namaLengkap" placeholder="" class="form-control" required>
							<label for="namaLengkap">Nama Lengkap</label>
						</div>
					</div>
					<div class="col">
						<div class="form-floating">
							<input type="text" name="email" id="email" placeholder="" class="form-control" required>
							<label for="email">E - Mail</label>
						</div>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col">
						<div class="form-floating">
							<input type="text" name="username" id="username" placeholder="" class="form-control" required>
							<label for="username">Username</label>
						</div>
					</div>
					<div class="col">
						<div class="form-floating">
							<input type="password" name="pw" id="pw" placeholder="" class="form-control" required>
							<label for="pw">Password</label>
						</div>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col">
						<div class="form-floating">
							<textarea name="alamat" id="alamat" cols="10" rows="3" class="form-control" placeholder="" required></textarea>
							<label for="alamat">Alamat</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col"></div>
					<div class="col">
						<select name="role" id="role" class="form-select" required>
							<option value selected disabled hidden>Pilih Role</option>
							<option value="1">Administrator</option>
							<option value="2">Petugas</option>
						</select>
					</div>
					<div class="col"></div>
				</div>
				<div class="row">
					<div class="col text-end">
						<input type="reset" name="btn" value="Reset" class="btn btn-danger">
						<input type="submit" name="btn" value="Tambah" class="btn btn-primary">
					</div>
				</div>
			</form>
		</div>
	</section>
</body>
</html>