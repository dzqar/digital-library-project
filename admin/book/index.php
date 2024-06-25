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
  header('location: /perpus//form/login.php?pesan=belum_login');
  exit;
}

if (!admin()) {
	header("location: /perpus/$roleFolder/?pesan=noaccess");
}

$nav = $lib->navbarAdmin('','','hidden',$username);
$mode = $lib->toggleMode();

// var_dump($stats);


// PAGINATION
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

if (isset($_GET['format']) && isset($_GET['kategori'])) {
  //  Jika ada 2 format & kategori di URL, maka akan menampilkan buku sesuai dengan format & kategori
	$format = $_GET['format'];
	$kategori = $_GET['kategori'];
	$data_buku = $globalicClass->tampilBuku("WHERE buku.format='$format' AND kategori_buku.nama_kategori='$kategori' GROUP BY buku.id_buku LIMIT $start,$limit");
	$data_buku1 = mysqli_query($db->koneksi,"SELECT COUNT(buku.id_buku) AS id FROM ((((buku INNER JOIN kategori_buku_relasi ON buku.id_buku = kategori_buku_relasi.id_buku) INNER JOIN kategori_buku ON kategori_buku_relasi.id_kategori = kategori_buku.id_kategori) INNER JOIN genre_buku_relasi ON buku.id_buku=genre_buku_relasi.id_buku) INNER JOIN genre_buku ON genre_buku.id_genre=genre_buku_relasi.id_genre) WHERE buku.format='$format' AND kategori_buku.nama_kategori='$kategori'");
}elseif (isset($_GET['format']) && $_GET['format'] !== NULL) {
  // Jika ada format di URL, maka menampilkan buku berdasarkan format
	$format = $_GET['format'];
	$data_buku = $globalicClass->tampilBuku("WHERE buku.format='$format' GROUP BY buku.id_buku LIMIT $start,$limit");
	$data_buku1 = mysqli_query($db->koneksi,"SELECT COUNT(buku.id_buku) AS id FROM buku WHERE buku.format='$format'");
}elseif (isset($_GET['kategori']) && $_GET['kategori'] !== NULL){
  // Jika ada kategori di URL, maka menampilkan buku berdasarkan kategori
	$kategori = $_GET['kategori'];
	$data_buku = $globalicClass->tampilBuku("WHERE kategori_buku.nama_kategori='$kategori' GROUP BY buku.id_buku LIMIT $start,$limit");
	$data_buku1 = mysqli_query($db->koneksi,"SELECT COUNT(buku.id_buku) AS id FROM buku INNER JOIN kategori_buku_relasi ON buku.id_buku = kategori_buku_relasi.id_buku INNER JOIN kategori_buku ON kategori_buku_relasi.id_kategori = kategori_buku.id_kategori WHERE kategori_buku.nama_kategori='$kategori'");
}elseif (isset($_GET['judul']) && $_GET['judul'] !== NULL) {
  // Jika ada judul di URL, maka menampilkan buku berdasarkan judul
	$judul = mysqli_real_escape_string($db->koneksi,$_GET['judul']);
	$data_buku = $globalicClass->tampilBuku("WHERE buku.judul LIKE '%$judul%' GROUP BY buku.id_buku LIMIT $start,$limit");
	$data_buku1 = mysqli_query($db->koneksi,"SELECT COUNT(buku.id_buku) AS id FROM buku WHERE buku.judul LIKE '%$judul%'");
}else{
	$data_buku = $globalicClass->tampilBuku(" GROUP BY buku.id_buku LIMIT $start,$limit");
	$data_buku1 = mysqli_query($db->koneksi,"SELECT COUNT(buku.id_buku) AS id FROM buku");
}

// PAGINATION
if (isset($data_buku1)) {
	$d = mysqli_fetch_assoc($data_buku1);
	$total = $d['id'];
	$pages = ceil($total / $limit);
}

// NEXT & PREVIOUS PAGINATION SYSTEM
$previous = $page - 1;
$next = $page + 1;

// Ngecek harga
function harga($harga){
	if ($harga === '0' || $harga === 0) {
    // Jika harganya 0, maka akan digantikan dengan tulisan "Gratis"
		return 'Gratis';
	}else{
    // Jika harganya lebih dari 0, tetap ditulis dengan harganya
		return 'Rp. '.number_format($harga,'0','','.');
	}
}

// Ngecek Status Peminjaman sebelum ngehapus buku
function hapusBuku($idBuku,$stats){
	if ($stats >= 1) {
		return 'onclick="return hapusBuku()"';
	}else{
		return "href='proses.php?id_buku=$idBuku&btn=Hapus'";
	}
}
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
	<script src="../../script/color-modes.js"></script>
	<title>Tampil Buku - Admin</title>
</head>
<body>
	<!-- Pop Up Sweetalert Pesan -->
	<script>
		<?php include '../../script/pesan.js' ?>
		function hapusBuku() {
			Swal.fire({
				icon: "error",
				title: "Tidak bisa menghapus buku",
				text: "Masih ada beberapa peminjam yang masih meminjam buku ini"
			});
		}
	</script>
	<section class="main">
		<div class="container">
			<div class="row text-center mb-3">
				<div class="col">
					<?php 
					if (isset($_GET['judul']) && $_GET['judul'] !== NULL) {
						$judul = $_GET['judul'];
						echo "<h2 class='d-inline'>Hasil penelusuran dari <q>$judul</q></h2>";
					}else{
						echo "<h2 class='d-inline'>Buku yang Tersedia</h2>";
					}
					?> <a href="add.php"><i class="bi bi-plus-square-fill fs-3 ms-2"></i></a>
				</div>
			</div>
			<div class="row justify-content-center">
				<?php 
				if ($data_buku !== NULL) {
					foreach ($data_buku as $d) {
						$idBuku = $d['id_buku'];
						$tampilStatusPeminjaman = $globalicClass->tampilDaftarPeminjaman("COUNT(status) AS stats","WHERE status=2 AND peminjaman.id_buku=$idBuku");
						foreach ($tampilStatusPeminjaman as $r) {
							$stats = $r['stats'];
						}
						?>
						<div class="col-auto col-sm-auto mx-1 mb-4">
							<div class="card h-100" style="width: 18rem;">
								<img src="../../style/buku/sampul/<?= $d['sampul_buku']?>" class="card-img-top mx-auto" alt="..." style="width: 50%">
								<div class="card-body">
									<div class="row mb-2">
										<h5 class="card-title text-truncate" title="<?= $d['judul']?>"><?= $d['judul']?></h5>
									</div>
									<div class="row mb-2">
										<div class="col text-truncate">
											<div class="card-text text-truncate" title="<?= $d['penulis']?>"><?= $d['penulis']?></div>
										</div>
										<div class="col">
											<div class="card-text"><?= $d['format']?>
											<?php
											error_reporting(0);
											$rataRating = $globalicClass->rataRating($idBuku_buku);
											if ($rataRating === NULL) {
												echo '0.0';
											}else{
												foreach ($rataRating as $key) { 
													echo $key['rataRating'];
												}
											}?> <i class="bi bi-star-fill"></i>
										</div>
									</div>
								</div>
								<div class="row mb-2">
									<div class="col text-truncate">
										<div class="card-text text-truncate" title="<?= $d['nama_kategori']?>"><?= $d['nama_kategori']?></div>
									</div>
									<div class="col text-truncate">
										<div class="card-text text-truncate" title="<?= $d['genreS']?>"><?= $d['genreS']?></div>
									</div>
								</div>
								<div class="row mb-2">
									<div class="col">
										<div class="card-text"><?= harga($d['harga'])?></div>
									</div>
									<div class="col" <?= ($d['format'] === 'E-Book') ? 'hidden' : ''?>>
										<div class="card-text <?= ($d['stok'] <= 1) ? 'fw-bold text-danger' : ''?>">Stok : <?= $d['stok']?></div>
									</div>
								</div>
								<div class="row text-end">
									<div class="col">
										<form action="edit.php" enctype="multipart/form-data" method="POST">
											<a <?= hapusBuku($idBuku,$stats)?> class="btn btn-danger">Hapus</a>
											<input type="hidden" name="id_buku" id="id_buku" value="<?= $d['id_buku']?>">
											<input type="submit" name="btn" value="Edit" class="btn btn-primary">
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php 
				}
			}else{
				echo '<div class="text-center">Tidak ada buku</div>';
			} 
			?>
		</div>
		<!-- PAGINATION SYSTEM -->
		<div class="row">
			<div class="col">
				<nav aria-label="Page navigation example">
					<ul class="pagination justify-content-center">

						<!-- Previous -->
						<li class="page-item <?php if($page === 1 || $_GET['page'] === '1'){/*Akan disabled ketika masih di awal page*/ echo 'disabled';} ?>">
							<a class="page-link" href="?<?= isset($_GET['format']) ? 'format=' . $_GET['format'] . '&' : '' ?><?= isset($_GET['kategori']) ? 'kategori=' . $_GET['kategori'] . '&' : '' ?>page=<?= $previous ?>" aria-label="Previous">
								<span aria-hidden="true">&laquo;</span>
							</a>
						</li>

						<?php for ($i=1; $i <= $pages ; $i++) { 
							$angka = $i.""; ?>

							<!-- Link akan menjadi berwarna/active ketika sesuai dengan link -->
							<li class="page-item <?php if (isset($_GET['page']) && $_GET['page'] === $angka) { /*Tombol akan aktif ketika ada ?page di URL dan sesuai dengan angka*/ echo 'active'; } else if (!isset($_GET['page']) && $i === 1) { /*Tombol akan aktif ketika tidak ada ?page di URL*/ echo 'active'; } else { echo ''; } ?>"><a class="page-link" href="<?= $_SERVER['PHP_SELF'] ?>?<?= isset($_GET['format']) ? 'format=' . $_GET['format'] . '&' : '' ?><?= isset($_GET['kategori']) ? 'kategori=' . $_GET['kategori'] . '&' : '' ?>page=<?= $i ?>"><?= $i ?></a></li>
						<?php } 
						?>

						<!-- Next -->
						<li class="page-item <?php if(isset($_GET['page']) && ($_GET['page'] === $angka) || ($total <= $limit)) { echo 'disabled'; } ?>">
							<a class="page-link" href="?<?= isset($_GET['format']) ? 'format=' . $_GET['format'] . '&' : '' ?><?= isset($_GET['kategori']) ? 'kategori=' . $_GET['kategori'] . '&' : '' ?>page=<?= $next ?>">
								<span aria-hidden="true">&raquo;</span>
							</a>
						</li>

					</ul>
				</nav>
			</div>
		</div>
		<!-- AKHIR PAGINATION SYSTEM -->
	</div>
</section>
</body>
</html>