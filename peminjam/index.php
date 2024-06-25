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

if (!peminjam()) {
  header("location: /perpus/$roleFolder/?pesan=noaccess");
}


$limit = 9;
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

$previous = $page - 1;
$next = $page + 1;

$nav = $lib->navbarPeminjam('','','',$username);

function harga($harga){
  if ($harga === '0' || $harga === 0) {
    return 'Gratis';
  }else{
    return 'Rp. '.number_format($harga,'0','','.');
  }
}

/*function hideReset($judul){

}*/
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
  <!-- Color Modes Bootstrap -->
  <script src="/perpus/script/color-modes.js"></script>
  <title>Dashboard - Peminjam</title>
</head>
<body>
  <script>
    <?php include '../script/pesan.js' ?>
  </script>
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
      <div class="row justify-content-center">
        <?php 
        if ($data_buku !== NULL) {
          foreach ($data_buku as $d) {
            $idBuku_buku = $d['id_buku'];
            $judulBuku = $d['judul'];
            ?>
            <div class="col-auto mx-1 mb-4">
              <div class="card h-100" style="width: 18rem;">
                <img src="/perpus/style/buku/sampul/<?= $d['sampul_buku']?>" class="card-img-top mx-auto" alt="..." style="width: 50%;height: 50%">
                <div class="card-body">
                  <h5 class="card-title text-truncate" title="<?= $d['judul']?>"><?= $d['judul']?></h5>
                  <u class="card-text"><?= $d['penulis']?></u>
                  <div class="card-text"><?= $d['format']?>
                  <?php
                  error_reporting(0);
                  $rataRating = $globalicClass->rataRating($idBuku_buku);
                  // var_dump($rataRating);
                  if ($rataRating === NULL) {
                    echo '0.0';
                  }else{
                    foreach ($rataRating as $key) { 
                      echo $key['rataRating'];
                    }
                  }?> <i class="bi bi-star-fill"></i>
                </div>
                <p class="card-text"><?= $d['genreS']?></p>
                <!-- Kategori Buku -->
                <p class="card-text"><?= $d['nama_kategori']?></p>
                <div class="card-text"><?= harga($d['harga'])?></div>
                <!-- <a href="detail.php" class="btn btn-primary">Go somewhere</a> -->
                <form action="detail.php" method="POST" class="text-end">
                  <input type="hidden" name="id_buku" value="<?= $d['id_buku']?>">
                  <input type="submit" value="Lihat Detail" class="btn btn-primary">
                </form>
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
<footer>
</footer>
</body>
</html>