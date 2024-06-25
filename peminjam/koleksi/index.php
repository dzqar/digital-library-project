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

if (!peminjam()) {
  header("location: /perpus/$roleFolder/?pesan=noaccess");
}

$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$nav = $lib->navbarPeminjam('','','hidden',$username);

// Filtering koleksi berdasarkan user dan hanya memunculkan kategori 'E-Book'
/*if (isset($_GET['judul']) && $_GET['judul'] !== NULL) {
  $judul = $_GET['judul'];
  $tampilKoleksi = $peminjamClass->tampilKoleksi("WHERE user.id_user='$id' AND format='E-Book' AND buku.judul LIKE '%$judul%' LIMIT $start,$limit");
}else{
  // LIMIT
  $tampilKoleksi = $peminjamClass->tampilKoleksi("WHERE user.id_user='$id' AND format='E-Book' LIMIT $start,$limit");
}*/

if (isset($_GET['kategori']) && $_GET['kategori'] !== NULL){
  // Jika ada kategori di URL, maka menampilkan buku berdasarkan kategori
  $kategori = $_GET['kategori'];
  $tampilKoleksi = $peminjamClass->tampilKoleksi("WHERE user.id_user='$id' AND format='E-Book' AND kategori_buku.nama_kategori='$kategori' LIMIT $start,$limit");
  $tampilKoleksi1 = mysqli_query($db->koneksi,"SELECT COUNT(koleksipribadi.id_koleksi) AS id FROM ((((koleksipribadi INNER JOIN buku ON koleksipribadi.id_buku=buku.id_buku) INNER JOIN user ON koleksipribadi.id_user=user.id_user) INNER JOIN kategori_buku_relasi ON kategori_buku_relasi.id_buku=buku.id_buku) INNER JOIN kategori_buku ON kategori_buku.id_kategori=kategori_buku_relasi.id_kategori) WHERE kategori_buku.nama_kategori='$kategori' AND koleksipribadi.id_user='$id' AND format='E-Book'");
}elseif (isset($_GET['judul']) && $_GET['judul'] !== NULL) {
  // Jika ada judul di URL, maka menampilkan buku berdasarkan judul
  $judul = mysqli_real_escape_string($db->koneksi,$_GET['judul']);
  $tampilKoleksi = $peminjamClass->tampilKoleksi("WHERE koleksipribadi.id_user='$id' AND format='E-Book' AND buku.judul LIKE '%$judul%' LIMIT $start,$limit");
  $tampilKoleksi1 = mysqli_query($db->koneksi,"SELECT COUNT(koleksipribadi.id_koleksi) AS id FROM koleksipribadi INNER JOIN buku ON koleksipribadi.id_buku=buku.id_buku WHERE buku.judul LIKE '%$judul%' AND koleksipribadi.id_user='$id' AND format='E-Book'");
}else{
  $tampilKoleksi = $peminjamClass->tampilKoleksi("WHERE koleksipribadi.id_user='$id' AND format='E-Book' LIMIT $start,$limit");
  $tampilKoleksi1 = mysqli_query($db->koneksi,"SELECT koleksipribadi.*,COUNT(id_koleksi) AS id,buku.judul,buku.sampul_buku,buku.harga FROM ((((koleksipribadi INNER JOIN buku ON koleksipribadi.id_buku=buku.id_buku) INNER JOIN user ON koleksipribadi.id_user=user.id_user) INNER JOIN kategori_buku_relasi ON kategori_buku_relasi.id_buku=buku.id_buku) INNER JOIN kategori_buku ON kategori_buku.id_kategori=kategori_buku_relasi.id_kategori) WHERE koleksipribadi.id_user='$id' AND format='E-Book'");
}

if (isset($tampilKoleksi1)) {
  $d = mysqli_fetch_assoc($tampilKoleksi1);
  $total = $d['id'];
  $pages = ceil($total / $limit);
}

$previous = $page - 1;
$next = $page + 1;

// Library
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
  <title>Koleksi Pribadi - Peminjam</title>
</head>
<body id="home">
  <!-- Pesan Sweetalert -->
  <script>
    <?php include '../../script/pesan.js' ?>
  </script>
  <main>
    <section class="main">
      <div class="container">
        <!-- JUDUL -->
        <div class="row text-center mb-3">
          <div class="col">
            <?php 
            if (isset($_GET['judul']) && $_GET['judul'] !== NULL) {
              echo "<h2>Hasil penelusuran dari <q>".$_GET['judul']."</q></h2>";
            }else{
              echo '<h2>Koleksi Anda</h2>';
            }
            ?>
          </div>
        </div>

        <!-- DAFTAR KOLEKSI -->
        <div class="row justify-content-center">
          <?php
          $no = 1;
          if ($tampilKoleksi !== NULL) {
            foreach($tampilKoleksi as $d){
              ?>
              <div class="col-sm-3 me-3 mb-4">
                <div class="card" style="width: 18rem;">
                  <img src="../../style/buku/sampul/<?= $d['sampul_buku']?>" class="card-img-top mx-auto" alt="..." style="width: 50%;height: 50%">
                  <div class="card-body">
                    <h5 class="card-title"><?= $d['judul']?></h5>
                    <div class="card-text"><del><?= 'Rp. '.number_format($d['harga'],'0','','.')?></del> <ins>Telah dibeli</ins></div>
                    <form action="book.php" method="POST" class="text-end d-inline" target="_blank">
                      <input type="text" name="file" value="<?= $d['file_buku']?>" hidden>
                      <input type="submit" value="Tampil PDF" class="btn btn-primary">
                    </form>
                    <form action="../detail.php" method="POST" class="d-inline text-end">
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

  </main>
  <footer>

  </footer>
</body>
</html>