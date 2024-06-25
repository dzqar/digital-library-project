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

$tampilKategori = $globalicClass->tampilKategori();
$tampilGenre = $globalicClass->tampilGenre();

// Own Library
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
	<!-- jQuery -->
	<script src="../../script/jquery-3.7.1.min.js"></script>
	<script src="../../script/jquery.mask.min.js"></script>
	<!-- Color Modes Bootstrap -->
	<script src="/perpus/script/color-modes.js"></script>
	<title>Tambah Buku - Admin</title>
</head>
<body>
	<!-- Pop Up Sweetalert Pesan -->
	<script>
		<?php include '../../script/pesan.js' ?>
	</script>
	<section>
		<div class="container-md border rounded">
			<form action="proses.php" enctype="multipart/form-data" class="form-data" method="POST">
				<div class="row ">
					<div class="col ">
						<a onclick="history.back()" class="link-primary d-inline">← Kembali</a>
					</div>
				</div>
				<div class="row p-2">
					<div class="col-md-5 text-center p-2">
						<img src="#" id="preview" class="img-fluid mx-auto mb-2" width="50%" height="70%">
						<div class="input-group justify-content-center">
							<label for="foto" class="input-group-text"><i class="bi bi-file-earmark-image me-2"></i> Upload</label>
							<input type="file" name="foto" id="foto" class="form-control visually-hidden" accept=".png,.jpg" onchange="pripiw()" required>
						</div>
					</div>
					<div class="col-md-7  p-2">
						<div class="form-floating mb-2">
							<input type="text" name="judul" id="judul" class="form-control" placeholder="" required>
							<label for="judul">Judul</label>
						</div>
						<div class="form-floating mb-2">
							<input type="text" name="penulis" id="penulis" class="form-control" placeholder="" required>
							<label for="penulis">Penulis</label>
						</div>
						<div class="form-floating mb-2">
							<input type="text" name="penerbit" id="penerbit" class="form-control" placeholder="" required>
							<label for="penerbit">Penerbit</label>
						</div>
						<div class="form-floating mb-2">
							<input type="number" name="tahun_terbit" id="tahun_terbit" class="form-control" placeholder="" min="1" required>
							<label for="tahun_terbit">Tahun Terbit</label>
						</div>
						<div class="form-floating mb-2">
							<input type="number" name="halaman" id="halaman" class="form-control" placeholder="" min="1" required>
							<label for="halaman">Halaman</label>
						</div>
						<div class="form-floating mb-2">
							<input type="text" name="harga" id="harga" class="form-control" placeholder="" required>
							<label for="harga">Harga</label>
						</div>
						<label for="format" class="form-label">Format</label>
						<div class="btn-group mb-2" role="group" aria-label="Basic radio toggle button group">
							<input type="radio" name="format" class="btn-check" id="formatFisik" value="1" required>
							<label class="btn btn-outline-primary" for="formatFisik">Fisik</label>
							<input type="radio" name="format" class="btn-check" id="formatEBook" value="2" required>
							<label class="btn btn-outline-primary" for="formatEBook">E-Book</label>
						</div>
						<div class="form-floating mb-2">
							<input type="number" name="stok" id="stok" value="1" min="1" class="form-control" placeholder="" required>
							<label for="stok" id="stokLabel" class="form-label">Stok</label>
						</div>
					</div>
				</div>
				<div class="row  p-2">
					<!-- Nav Tabs -->
					<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#deskripsi" type="button" role="tab" aria-controls="deskripsi" aria-selected="true">Deskripsi</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#kategori" type="button" role="tab" aria-controls="kategori" aria-selected="true">Pilih Kategori</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#genre" type="button" role="tab" aria-controls="genre" aria-selected="false">Pilih Genre</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="file_bukuLabel" data-bs-toggle="tab" data-bs-target="#inputFile" type="button" role="tab" aria-controls="inputFile" aria-selected="false">Input File</button>
						</li>
					</ul>
					<!-- Tab Content -->
					<div class="tab-content mt-4" id="myTabContent">
						<div class="tab-pane fade show active" id="deskripsi" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
							<div class="form-floating">
								<textarea class="form-control" placeholder="" placeholder="Leave a comment here" id="deskripsi" name="deskripsi"></textarea>
								<label for="deskripsi">Deskripsi</label>
							</div>
						</div>
						<div class="tab-pane fade" id="kategori" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
							<div class="btn-group mb-2" role="group" aria-label="Basic radio toggle button group">
								<?php
								foreach ($tampilKategori as $d) {
									?>
									<input type="radio" name="kategori" class="btn-check" id="kategori<?= $d['id_kategori'] ?>" value="<?= $d['id_kategori']?>" required>
									<label class="btn btn-outline-primary" for="kategori<?= $d['id_kategori'] ?>"><?= $d['nama_kategori']?></label>
									<?php
								}
								?>
							</div>
						</div>
						<div class="tab-pane fade" id="genre" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
							<div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
								<?php 
								foreach ($tampilGenre as $key) { 
									?>
									<input type="checkbox" name="genre[]" class="btn-check" id="genre<?= $key['id_genre'] ?>" value="<?= $key['id_genre']?>" >
									<label class="btn btn-outline-primary" for="genre<?= $key['id_genre'] ?>"><?= $key['nama_genre']?></label>
								<?php } ?>
							</div>
						</div>
						<div class="tab-pane fade" id="inputFile" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
								<input type="file" name="file_buku" id="file_buku" accept=".pdf" class="form-control" required>
						</div>
					</div>
				</div>
				<div class="row  p-2">
					<div class="col text-end">
						<input type="reset" value="Reset" name="btn" class="btn btn-danger fs-5">
						<input type="submit" value="Tambah" name="btn" class="btn btn-primary fs-5">
					</div>
				</div>
			</form>
		</div>
	</section>
	<script type="text/javascript">
	// Preview foto
	// Membuat function pripiw() untuk preview foto ketika mengupload file
	function pripiw(){
                  // Untuk menangkap tag dengan id="foto" dan return files nya itu index/utama (0) ketika sudah di upload
                  var foto = document.getElementById("foto").files[0];
                  // Menangkap tag dengan id="preview" untuk melakukan preview sebuah foto
                  var preview = document.getElementById("preview");

                  
                  // Tanpa pake File API
                  if (foto) {
                    // Menambahkan atribut src pada tag yang id="preview" dengan valuenya ditangkap dari variabel foto
                    preview.src = URL.createObjectURL(foto);
                    // Membuat style id="preview" display="block" untuk di tampilkan
                    preview.style.display = "block";
                }else{
                    // Jika tidak ada, maka src nya menjadi # dan display nya menjadi none, agar tidak di tampilkan
                    preview.src = "#";
                    preview.style.display= "none";
                }
            }
        </script>
        <script type="text/javascript">
        	$(document).ready(function() {
			  // Sembunyikan input file pada awalnya
			  $('#file_buku').hide();
			  $('#file_bukuLabel').hide();
			  $('#stokLabel').hide();
			  $('#stok').hide();

			  // kategori di ubah menjadi format
			  $('input[name="format"]').on('change', function() {
			  	if ($(this).val() === '2') {
			  	// Tampilkan input file dan hide stok ketika kategori "E-Book" dipilih
			  	$('#stok').hide();
			  	$('#stokLabel').hide();
			  	$('#file_buku').show();
			  	$('#file_bukuLabel').show();
			  	$('#file_buku').removeAttr('disabled');
			  } else {
			  	// Show stok dan hide & disable input file ketika memilih "Fisik"
			  	$('#stok').show();
			  	$('#stokLabel').show();
			  	$('#file_buku').hide();
			  	$('#file_bukuLabel').hide();
			  	$('#file_buku').attr('disabled', 'disabled');
			  }
			});
			});

        	$(document).ready(function(){

                // Format mata uang.
                $( '#harga' ).mask('000.000.000', {reverse: true});

            })
        </script>
    </body>
    </html>