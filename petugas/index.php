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

include '../koneksi.php';

// Jika pengguna belum melakukan login
if(!isset($_SESSION['username'])){
  header('location: ../form/login.php?pesan=belum_login');
  exit;
}

if (!petugas()) {
  header("location: /perpus/$roleFolder/?pesan=noaccess");
}

// Connection Database (OOP)
$totalPeminjaman = $adminClass->totalPeminjamanFisik();
$totalPembelianFisik = $adminClass->totalPembelianFisik();
$totalPembelianEbook = $adminClass->totalPembelianEbook();
$totalLayananPeminjaman = $petugasClass->totalLayananPeminjaman($id);
$totalLayananPembelian = $petugasClass->totalLayananPembelian($id);
$totalLayananTransaksi = $petugasClass->totalLayananTransaksi($id);

// Own Library
$nav = $lib->navbarPetugas($username);
$mode = $lib->toggleMode();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Icon Title -->
	<link rel="icon" href="../style/logo/logo.png" type="image/x-icon">
	<!-- Bootstrap Icons -->
	<link rel="stylesheet" href="../style/assets/bootstrap/bootstrap-icons.css">
	<link rel="stylesheet" href="../style/assets/bootstrap/bootstrap-icons.min.css">
	<!-- Style Bootstrap -->
	<link rel="stylesheet" href="../style/assets/bootstrap/bootstrap.min.css">
	<script src="../style/assets/bootstrap/bootstrap.bundle.min.js"></script>
	<!-- Style CSS -->
	<link rel="stylesheet" href="../style/style.css">
	<!-- SweetAlert2 -->
	<link rel="stylesheet" href="../style/assets/sweetalert/sweetalert2.min.css">
	<link rel="stylesheet" href="../style/assets/sweetalert/animate.min.css">
	<script src="../style/assets/sweetalert/sweetalert2.min.js"></script>
	<!-- Color Modes Bootstrap-->
	<script src="../script/color-modes.js"></script>
	<title>Dashboard - Petugas</title>
</head>
<body>
	<section id="dashboard">
		<div class="container">
			<div class="row text-center mb-3">
				<h2 class="text-center mb-3">Jumlah Pelayanan</h2>
				<div class="col-sm-4 mb-3">
					<!-- Jumlah Pengunjung -->
					<button class="btn btn-primary">Peminjaman : 
						<?php 
						foreach ($totalLayananPeminjaman as $d) {
							echo $d['jmlh'];
						}
						?></button>
					</div>
					<div class="col-sm-4 mb-3">
						<!-- Jumlah Pengunjung -->
						<button class="btn btn-primary">Pembelian : 
							<?php 
							foreach ($totalLayananPembelian as $d) {
								echo $d['jmlh'];
							}
							?></button>
						</div>
						<div class="col-sm-4 mb-3">
							<!-- Jumlah Pengunjung -->
							<button class="btn btn-primary">Transaksi : 
								<?php 
								foreach ($totalLayananTransaksi as $d) {
									echo $d['jmlh'];
								}
								?></button>
							</div>
						</div>
					</div>
				</div>
				<div class="container">
					<div class="col">
						<h2 class="text-center mb-3">Daftar Banyak Buku yang Dipinjam</h2>
						<!-- Tiap Buku, udah di pinjam berapa kali, -->
						<table border="1px" class="table table-bordered border-secondary">
							<thead class="table table-bordered table-danger border-secondary">
								<tr>
									<th>No</th>
									<th>Judul</th>
									<th>Format</th>
									<th>Total Dipinjam</th>
								</tr>
							</thead>
							<tbody class="table table-bordered border-secondary table-light">
								<?php
					// variable
								$no = 1;
								foreach ($totalPeminjaman as $d) {
									?>
									<tr>
										<td><?= $no++ ?></td>
										<td><?= $d['judul'] ?></td>
										<td><?= $d['format'] ?></td>
										<td><?= $d['total_peminjaman'] ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="container">
					<div class="col">
						<h2 class="text-center mb-3">Daftar Pembelian Buku Fisik</h2>
						<!-- Buku yang paling banyak yang dibeli -->
						<table border="1px" class="table table-bordered border-secondary">
							<thead class="table table-bordered table-danger border-secondary">
								<tr>
									<th>No</th>
									<th>Judul</th>
									<th>Total Dibeli</th>
								</tr>
							</thead>
							<tbody class="table table-bordered border-secondary table-light">
								<?php
					// variable
								$no = 1;
								foreach ($totalPembelianFisik as $d) {
									?>
									<tr>
										<td><?= $no++ ?></td>
										<td><?= $d['judul'] ?></td>
										<td><?= $d['total_pembelian'] ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="container">
					<div class="col">
						<h2 class="text-center mb-3">Daftar Pembelian E-Book</h2>
						<!-- Buku yang paling banyak yang dibeli -->
						<table border="1px" class="table table-bordered border-secondary">
							<thead class="table table-bordered table-danger border-secondary">
								<tr>
									<th>No</th>
									<th>Judul</th>
									<th>Total Dibeli</th>
								</tr>
							</thead>
							<tbody class="table table-bordered border-secondary table-light">
								<?php
					// variable
								$no = 1;
								foreach ($totalPembelianEbook as $d) {
									?>
									<tr>
										<td><?= $no++ ?></td>
										<td><?= $d['judul'] ?></td>
										<td><?= $d['total_pembelian'] ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</body>
		</html>