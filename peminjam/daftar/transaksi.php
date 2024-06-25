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

// LIMIT
$tampilDaftarTransaksi = $globalicClass->tampilDaftarTransaksi("WHERE transaksi.id_peminjam='$id' ORDER BY transaksi.id_transaksi DESC LIMIT $start,$limit");

// COUNT
$tampilDaftarTransaksi1 = mysqli_query($db->koneksi,"SELECT COUNT(id_transaksi) AS id FROM transaksi WHERE transaksi.id_peminjam='$id'");
$d = mysqli_fetch_assoc($tampilDaftarTransaksi1);
$total = $d['id'];
$pages = ceil($total / $limit);

$previous = $page - 1;
$next = $page + 1;

$nav = $lib->navbarPeminjam('hidden','hidden','hidden',$username);
$mode = $lib->toggleMode();

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
  <script src="/perpus/script/color-modes.js"></script>
  <title>Daftar Peminjaman - Peminjam</title>
</head>
<body id="home">

  <!-- Pesan Sweetalert -->
  <script>
    <?php include '../../script/pesan.js' ?>
  </script>

  <main>
    <section class="main">
      <div class="container">
        <nav aria-label="Page navigation example">
          <ul class="pagination">

            <!-- Previous -->
            <li class="page-item <?php if($page === 1 || $_GET['page'] === '1'){/*Akan disabled ketika masih di awal page*/ echo 'disabled';} ?>">
              <a class="page-link" href="?page=<?= $previous ?>" aria-label="Previous" disabled>
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>

            <?php for ($i=1; $i <= $pages ; $i++) { 
              $angka = $i.""; ?>
              
              <!-- Link akan menjadi berwarna/active ketika sesuai dengan link -->
              <li class="page-item <?php if (isset($_GET['page']) && $_GET['page'] === $angka) { /*Tombol akan aktif ketika ada ?page di URL dan sesuai dengan angka*/ echo 'active'; } else if (!isset($_GET['page']) && $i === 1) { /*Tombol akan aktif ketika tidak ada ?page di URL*/ echo 'active'; } else { echo ''; } ?>"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
            <?php } 
            ?>

            <!-- Next -->
            <li class="page-item <?php if(isset($_GET['page']) && $_GET['page'] === $angka || $total <= $limit){ /*Akan disabled ketika sudah di akhir page*/ echo 'disabled';} ?>">
              <a class="page-link" href="?page=<?= $next ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          </ul>
        </nav>
        <?php 
        if (isset($tampilDaftarTransaksi)) {
         ?>
        <table border="1" class="table table-bordered border-secondary">
          <thead class="table table-bordered table-danger border-secondary">
            <tr>
              <th>No</th>
              <th>Judul Buku</th>
              <th>Pelanggaran</th>
              <th>Total Denda</th>
              <th>Tanggal <br>Transaksi</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody class="table table-bordered border-secondary table-light">
            <?php
            $no = 1;
            foreach($tampilDaftarTransaksi as $d){
              ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= $d['judul'] ?></td>
                <td><?php echo $d['pelanggaran'];?></td>
                <td>Rp. <?php echo number_format($d['total_biaya'],'0','','.');?></td>
                <td><?php echo $d['tgl_transaksi'];?></td>
                <td><?= $d['statusTransaksi']?></td>
                <td>
                  <form action="../downloadstruk.php" method="POST" target="_blank">
                    <input type="text" name="id_transaksi" value="<?= $d['id_transaksi']?>" hidden>
                    <input type="submit" class="btn btn-primary" value="Lihat Struk">
                  </form>
                </td>
            </tr>
            <?php
          }
          ?>
        </tbody>
      </table>
        <?php 
      }else{?>
        <table border="1" class="table table-bordered border-secondary">
          <thead class="table table-bordered table-danger border-secondary">
            <tr>
              <th>No</th>
              <th>Judul Buku</th>
              <th>Pelanggaran</th>
              <th>Total Denda</th>
              <th>Tanggal <br>Transaksi</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody class="table table-bordered border-secondary table-light text-center">
            <tr>
              <td colspan="10">
        <h2>Belum ada data transaksi</h2>
              </td>
            </tr>
          </tbody>
        </table>
        <?php
      }
      ?>
      </div>
    </div>
  </section>

</main>
<footer>

</footer>
</body>
</html>