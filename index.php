<?php
session_start();

include 'koneksi.php';

// $tampilKategori = $globalicClass->tampilKategori();

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

// Library
$mode = $lib->toggleMode();

// $hiddenReset= (isset($_GET['judul'])) ? '' : 'hidden';
// $valueInput= (isset($_GET['judul'])) ? $_GET['judul'] : '';
$nav = $lib->navbarIndex("");

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
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Icon Title -->
  <!-- <link rel="icon" href="style/logo/logo.png" type="image/x-icon"> -->
  <link rel="icon" href="style/logo/logo.png" type="image/x-icon">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="style/assets/bootstrap/bootstrap-icons.css">
  <link rel="stylesheet" href="style/assets/bootstrap/bootstrap-icons.min.css">
  <!-- Style Bootstrap -->
  <link rel="stylesheet" href="style/assets/bootstrap/bootstrap.min.css">
  <script src="style/assets/bootstrap/bootstrap.bundle.min.js"></script>
  <!-- Style CSS -->
  <link rel="stylesheet" href="style/style.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="style/assets/sweetalert/sweetalert2.min.css">
  <link rel="stylesheet" href="style/assets/sweetalert/animate.min.css">
  <script src="style/assets/sweetalert/sweetalert2.min.js"></script>
  <!-- Color Modes Bootstrap -->
  <script src="script/color-modes.js"></script>
  <title>Merdeka Membaca</title>
</head>
<body>

<!-- MAIN -->
<section class="main">
  <div class="container">
    <!-- JUDUL -->
    <div class="row text-center mb-3">
      <div class="col">
        <?php 
        if (isset($_GET['format']) && $_GET['format'] !== NULL) {
          $format = $_GET['format'];
          echo "<h2 class='d-inline'>Buku <q>$format</q> yang tersedia</h2>";
        }elseif (isset($_GET['judul']) && $_GET['judul'] !== NULL) {
          $judul = $_GET['judul'];
          echo "<h2 class='d-inline'>Hasil penelusuran dari <q>$judul</q></h2>";
        }else{
          echo "<h2 class='d-inline'>Buku yang Tersedia</h2>";
        }
        ?>
      </div>
    </div>

    <!-- DAFTAR BUKU -->
    <div class="row justify-content-center mb-3">
      <?php 
      if ($data_buku !== NULL) {
        foreach ($data_buku as $d) {
          $idBuku_buku = $d['id_buku'];
          $judulBuku = $d['judul'];
          ?>
          <div class="col-auto mx-1 mb-4">
            <!-- CARD -->
            <div class="card h-100" style="width: 18rem;">
              <!-- Sampul Buku -->
              <img src="/perpus/style/buku/sampul/<?= $d['sampul_buku']?>" class="card-img-top mx-auto" alt="..." style="width: 50%;height: 50%">
              <div class="card-body">
                <!-- Judul Buku -->
                <h5 class="card-title text-truncate" title="<?= $d['judul']?>"><?= $d['judul']?></h5>
                <!-- Penulis Buku -->
                <u class="card-text"><?= $d['penulis']?></u>
                <!-- Format Buku -->
                <div class="card-text"><?= $d['format']?>
                <!-- Total rating dari buku -->
                <?php
                  error_reporting(0); // Mencegah error jika belum ada rating yang masuk berdasarkan buku itu
                  $rataRating = $globalicClass->rataRating($idBuku_buku); // Mengambil function rataRating() dari "koneksi.php"
                  if ($rataRating === NULL) {
                    echo '0.0';
                  }else{
                    foreach ($rataRating as $key) { 
                      echo $key['rataRating'];
                    }
                  }?> <i class="bi bi-star-fill"></i>
                  <!-- Genre Buku -->
                  <p class="card-text"><?= $d['genreS']?></p>
                  <!-- Kategori Buku -->
                  <p class="card-text"><?= $d['nama_kategori']?></p>
                  <!-- Harga Buku -->
                  <div class="card-text"><?= harga($d['harga'])?></div>
                  <!-- Tombol Lihat Detail -->
                  <form action="peminjam/detail.php" method="POST" class="text-end">
                    <input type="hidden" name="id_buku" value="<?= $d['id_buku']?>">
                    <input type="submit" value="Lihat Detail" class="btn btn-primary">
                  </form>
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
              <li class="page-item
              <?php if (isset($_GET['page']) && $_GET['page'] === $angka) { 
                /*Tombol akan aktif ketika ada ?page di URL dan sesuai dengan angka*/ 
                echo 'active'; 
              } else if (!isset($_GET['page']) && $i === 1) {
               /*Tombol akan aktif ketika tidak ada ?page di URL*/
                echo 'active'; 
              } else {
               echo ''; 
             }?>">
             <a class="page-link" href="<?= $_SERVER['PHP_SELF'] ?>?<?= isset($_GET['format']) ? 'format=' . $_GET['format'] . '&' : '' ?><?= isset($_GET['kategori']) ? 'kategori=' . $_GET['kategori'] . '&' : '' ?>page=<?= $i ?>"><?= $i ?></a>
           </li>
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
<!-- AKHIR MAIN -->
<footer>
</footer>
</body>
</html>