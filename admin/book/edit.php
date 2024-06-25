<?php 
session_start();
error_reporting(0);

// Tampungan dari proses login
$id = $_SESSION['id_user'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];


include '../../koneksi.php';

$idBuku = $_POST['id_buku'];

$tampilEditBuku = $adminClass->tampilEditBuku($idBuku);
$tampilKategori = $globalicClass->tampilKategori();
$tampilGenre = $globalicClass->tampilGenre();
$tampilRating= $globalicClass->tampilRating("INNER JOIN user ON ulasan.id_user=user.id_user WHERE id_buku='$idBuku' ORDER BY id_ulasan DESC ");

// Own Library
$lib = new lib();
$nav = $lib->navbarAdmin('hidden','hidden','hidden',$username);
$mode = $lib->toggleMode();

function onOffFile($format){
	switch ($format) {
		case 'Fisik':
		return 'hidden';
		break;

		case 'E-Book':
		default:
		return '';
		break;
	}
}

function noStokEBook($format){
	switch ($format) {
		case 'E-Book':
		return 'hidden';
		break;
		
		case 'Fisik':
		default:
		return '';
		break;
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
  <!-- jQuery -->
	<script src="../../script/jquery-3.7.1.min.js"></script>
	<script src="../../script/jquery.mask.min.js"></script>
  <!-- Color Modes Bootstrap -->
  <script src="/perpus/script/color-modes.js"></script>
	<title>Edit Buku - Admin</title>
</head>
<body>

	<?php
	foreach ($tampilEditBuku as $d) {
        // echo $d['format'];
		// var_dump($d['genreS']);
		$genres = explode(', ', $d['genreS']); // Diubah menjadi array
		// var_dump($genres);
		foreach ($genres as $key) {
			$genreBukuIds[] = $key; // Loop
		// var_dump($genreBukuIds);
		}
			// var_dump(json_encode($genreBukuIds));
		?>
		<script type="text/javascript">
			/* Preview sampul buku */
			// Membuat function pripiw() untuk preview sampul buku ketika mengupload file
			function pripiw(){
                  // Untuk menangkap tag dengan id="foto" dan return files nya itu index/utama (0) ketika sudah di upload
                  var foto = document.getElementById("foto").files[0];
                  // Menangkap tag dengan id="preview" untuk melakukan preview sebuah sampul buku
                  var preview = document.getElementById("preview");

                  
                  // Tanpa pake File API
                  if (foto) {
                    // Menambahkan atribut src pada tag yang id="preview" dengan valuenya ditangkap dari variabel sampul
                    preview.src = URL.createObjectURL(foto);
                    // Membuat style id="preview" display="block" untuk di tampilkan
                    preview.style.display = "block";
                }else{
                    // Jika tidak ada, maka src nya menjadi sampul sebelumnya dan display nya menjadi none, agar tidak di tampilkan
                    preview.src = "../../style/buku/sampul/<?= $d['sampul_buku']?>";
                    preview.style.display= "none";
                }
            }

            /* AUTO CHECKED CHECKBOX GENRE */
            $(document).ready(function() {
            	var genreBukuIds = <?php echo json_encode($genreBukuIds);  // Ambil data genre dari PHP | Contoh Output : ["2","4"]?>;

			    // Loop 
			    $.each(genreBukuIds, function(index, genreId) {
			    	$("#genre" + genreId).attr("checked","checked");
			    });
			});

            /* FORMAT UANG */
            $(document).ready(function(){

                // Format mata uang.
                $( '#harga' ).mask('000.000.000', {reverse: true});

            })
        </script>

        <section>
        	<div class="container-md border rounded">
        		<form action="proses.php" enctype="multipart/form-data" class="form-data" method="POST">
        			<input type="number" name="id_buku" id="id_buku" value="<?= $idBuku ?>" hidden required>
        			<div class="row ">
        				<div class="col ">
        					<a onclick="history.back()" class="link-primary d-inline">← Kembali</a>
        				</div>
        			</div>
        			<div class="row  p-2">
        				<!-- Sampul -->
        				<div class="col-md-5  text-center p-2">
        					<img src="../../style/buku/sampul/<?= $d['sampul_buku']?>" alt="sampul" class="img-fluid mb-2 mx-auto" width="40%" height="40%" id="preview" />
        					<div class="input-group justify-content-center">
        						<label for="foto" class="input-group-text"><i class="bi bi-file-earmark-image me-2"></i> Upload</label>
        						<input type="file" name="foto" id="foto" class="form-control visually-hidden" accept=".png,.jpg" onchange="pripiw()">
        					</div>
        				</div>
        				<div class="col-md-7  p-2">
        					<div class="form-floating mb-2">
        						<input type="text" name="judul" id="judul" value="<?= $d['judul']?>" class="form-control" placeholder="" required>
        						<label for="judul">Judul</label>
        					</div>
        					<div class="form-floating mb-2">
        						<input type="text" name="penulis" id="penulis" value="<?= $d['penulis']?>" class="form-control" placeholder="" required>
        						<label for="penulis">Penulis</label>
        					</div>
        					<div class="form-floating mb-2">
        						<input type="text" name="penerbit" id="penerbit" class="form-control" placeholder="" value="<?= $d['penerbit']?>" required>
        						<label for="penerbit">Penerbit</label>
        					</div>
        					<div class="form-floating mb-2">
        						<input type="number" name="tahun_terbit" id="tahun_terbit" class="form-control" placeholder="" value="<?= $d['tahun_terbit']?>" min="1" required>
        						<label for="tahun_terbit">Tahun Terbit</label>
        					</div>
        					<div class="form-floating mb-2">
        						<input type="number" name="halaman" id="halaman" class="form-control" placeholder="" value="<?= $d['halaman']?>" min="1" required>
        						<label for="halaman">Halaman</label>
        					</div>
        					<div class="form-floating mb-2">
        						<input type="text" name="harga" id="harga" class="form-control" placeholder="" value="<?= $d['harga']?>" required>
        						<label for="harga">Harga</label>
        					</div>
        					<div class="form-floating mb-2" <?= noStokEBook($d['format'])?>>
        						<input type="number" name="stok" id="stok" class="form-control" placeholder="" placeholder="" value="<?= $d['stok']?>" min="0" required>
        						<label for="stok">Stok</label>
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
                              <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#inputFile" type="button" role="tab" aria-controls="inputFile" aria-selected="false" <?= onOffFile($d['format'])?>>Input File</button>
                          </li>
                          <li class="nav-item" role="presentation">
                              <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#ulasan" type="button" role="tab" aria-controls="ulasan" aria-selected="false">Ulasan</button>
                          </li>
                      </ul>
                      <!-- Tab Content -->
                      <div class="tab-content mt-4" id="myTabContent">
                       <div class="tab-pane fade show active" id="deskripsi" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                          <div class="form-floating">
                             <textarea class="form-control" placeholder="" placeholder="Leave a comment here" id="deskripsi" name="deskripsi"><?= $d['deskripsi']?></textarea>
                             <label for="deskripsi">Deskripsi</label>
                         </div>
                     </div>
                     <div class="tab-pane fade" id="kategori" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                        <div class="btn-group mb-2" role="group" aria-label="Basic radio toggle button group">
                            <?php
                            foreach ($tampilKategori as $r) {
                                ?>
                                <input type="radio" name="kategori" class="btn-check" id="kategori<?= $r['id_kategori'] ?>" value="<?= $r['id_kategori']?>" <?php if($r['id_kategori'] === $d['id_kategori']) { echo 'checked'; }else{ echo ''; }?> required>
                                <label class="btn btn-outline-primary" for="kategori<?= $r['id_kategori'] ?>"><?= $r['nama_kategori']?></label>
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
                            <input type="checkbox" name="genre[]" class="btn-check" id="genre<?= $key['id_genre'] ?>" value="<?= $key['id_genre']?>">
                            <label class="btn btn-outline-primary" for="genre<?= $key['id_genre'] ?>"><?= $key['nama_genre']?></label>
                        <?php } ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="inputFile" role="tabpanel" aria-labelledby="profile-tab" tabindex="0" <?= onOffFile($d['format'])?>>
                  <!-- <div class="input-group justify-content-center">
                     <label for="file_buku" class="input-group-text"><i class="bi bi-file-earmark me-2"></i> Upload</label>
                 </div> -->
                     <input type="file" name="file_buku" id="file_buku" accept=".pdf" class="form-control">
             </div>
             <div class="tab-pane fade" id="ulasan" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
              <?php 
              if (isset($tampilRating)) {
              foreach ($tampilRating as $key) { ?>
                 <div class="row">
                    <div class="col mb-2">
                       <div class="card">
                          <div class="card-header">
                             <?= $key['rating']?> <i class="bi bi-star-fill"></i>
                         </div>
                         <div class="card-body">
                             <blockquote class="blockquote mb-0">
                                <p><?= $key['ulasan']?></p>
                                <footer class="blockquote-footer"><?= $key['username']?></footer>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
      } 
    } else { ?>
      <h4>Belum ada ulasan dari siapapun</h4>
      <?php
    }
    ?>
    </div>
</div>
</div>
<div class="row p-2">
    <div class="col text-end">
       <input type="reset" value="Reset" name="btn" class="btn btn-danger fs-5">
       <input type="submit" value="Edit" name="btn" class="btn btn-primary fs-5">
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