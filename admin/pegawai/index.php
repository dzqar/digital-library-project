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

// Connection Database (OOP)
if (isset($_GET['username']) && $_GET['username'] !== NULL) {
	$uPetugas = $_GET['username'];
	$tampilUser = mysqli_query($db->koneksi,"SELECT * FROM user WHERE role < 3 AND id_user != '$id' AND username LIKE '%$uPetugas%'");
}else{
$tampilUser = $adminClass->tampilUser($id);
}

$nav = $lib->navbarAdmin('hidden','hidden','',$username);
$mode = $lib->toggleMode();

?>
<!DOCTYPE html>
<html lang="en"  data-bs-theme="auto">
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
	<script src="../../script/color-modes.js"></script>
	<title>Tampil Genre - Admin</title>
</head>
<body>
	<!-- Pop Up Sweetalert Pesan -->
	<script>
		<?php include '../../script/pesan.js' ?>
	</script>
	<section class="main">
		<div class="container">
			<div class="row text-center mb-3">
				<div class="col">
					<?php 
					if (isset($_GET['username']) && $_GET['username'] !== NULL) {
						$username = $_GET['username'];
						echo "<h2 class='d-inline'>Hasil penelusuran dari <q>$username</q></h2>";
					}else{
						echo "<h2 class='d-inline'>Daftar Akun Petugas</h2>";
					}
					?> <a href="add.php"><i class="bi bi-plus-square-fill fs-3 ms-2"></i></a>
				</div>
			</div>
			<div class="row justify-content-center">
				<?php 
				if ($tampilUser !== NULL) {
				foreach ($tampilUser as $d) {
					?>
					<div class="col-sm-3 me-3 mb-4">
						<div class="card" style="width: 18rem;">
							<div class="card-body">
								<h5 class="card-title"><?= $d['username']?></h5>
								<div class="card-text">E-Mail : <?= $d['email']?></div>
								<div class="card-text">Nama Lengkap : <?= $d['nama_lengkap']?></div>
								<div class="card-text">Alamat : <?= $d['alamat']?></div>
								<div class="card-text">Role : <?= $d['role']?></div>
								<div class="text-end">
									<form action="edit.php" method="POST">
										<input type="hidden" name="id_user" value="<?= $d['id_user']?>">
										<a href="proses.php?id_user=<?= $d['id_user']?>&btn=Hapus" class="btn btn-danger">Hapus</a>
										<input type="submit" value="Edit" class="btn btn-primary">
									</form>
								</div>
							</div>
						</div>
					</div>
				<?php 
			} 
		}else{
			echo '<div class="text-center">Tidak ada username : <q>'.$_GET['username'].'</q></div>';
		}
			?>
			</div>
		</div>
	</section>
</body>
</html>