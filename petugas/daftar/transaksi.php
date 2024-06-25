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

if (!petugas()) {
  header("location: /perpus/$roleFolder/?pesan=noaccess");
}

$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// LIMIT
$tampilDaftarTransaksi = $globalicClass->tampilDaftarTransaksi("WHERE transaksi.id_petugas IN(0,$id) ORDER BY transaksi.status DESC LIMIT $start,$limit");

// COUNT
$tampilDaftarTransaksi1 = mysqli_query($db->koneksi,"SELECT COUNT(id_transaksi) AS id FROM transaksi WHERE transaksi.id_petugas IN(0,$id)");

// PAGINATION
if (isset($tampilDaftarTransaksi1)) {
  $d = mysqli_fetch_assoc($tampilDaftarTransaksi1);
  $total = $d['id'];
  $pages = ceil($total / $limit);
}

$previous = $page - 1;
$next = $page + 1;

$nav = $lib->navbarPetugas($username);
$mode = $lib->toggleMode();

function selectStatus($status){
  switch ($status) {
    case 'Lunas':
    case 'Batal':
    return 'disabled';
    break;
    
    case 'Belum Lunas':
    default:
    return '';
    break;
  }
}

// Untuk mendisable tombol liat struk kalo statusnya batal
function disableLihatStruk($status){
  switch ($status) {
    case 'Batal':
    return 'disabled';
    break;
    
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
  <!-- Color Modes Bootstrap-->
  <script src="../../script/color-modes.js"></script>
  <title>Daftar Peminjaman - Peminjam</title>
</head>
<body id="home">
  <!-- Pop Up Sweetalert Pesan -->
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
            <li class="page-item <?php if(isset($_GET['page']) && ($_GET['page'] === $angka) || ($total <= $limit)) { echo 'disabled'; } ?>">
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
              <th>Username</th>
              <th>Judul Buku</th>
              <th>Pelanggaran</th>
              <th>Total Biaya</th>
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
                <td><?= $d['uPeminjam'] ?></td>
                <td><?= $d['judul'] ?></td>
                <td><?= $d['pelanggaran'] ?></td>
                <td>Rp. <?= number_format($d['total_biaya'],'0','','.')?></td>
                <td><?= $d['tgl_transaksi'];?></td>
                <td>
                  <form action="proses.php" class="d-inline d-flex" method="POST">
                    <input type="number" name="id_buku" value="<?= $d['id_buku']?>" hidden>
                    <input type="number" name="id_transaksi" value="<?= $d['id_transaksi']?>" hidden>
                    <input type="number" name="id_peminjaman" value="<?= $d['id_peminjaman']?>" hidden>
                    <input type="text" name="pelanggaran" value="<?= $d['pelanggaran']?>" hidden>
                    <select name="status" class="form-select" <?= selectStatus($d['statusTransaksi'])?> required>
                      <option value disabled hidden selected><?= $d['statusTransaksi']?></option>
                      <option value="Lunas">Lunas</option>
                      <option value="Batal">Batal</option>
                    </select>
                    <button type="submit" value="UbahTransaksi" name="btn" class="btn btn-primary" <?= selectStatus($d['statusTransaksi'])?>>Ubah</button>
                  </form>
                </td>
                <td>
                  <form action="../downloadstruk.php" method="POST" target="_blank">
                    <input type="text" name="id_transaksi" value="<?= $d['id_transaksi']?>" hidden>
                    <input type="submit" class="btn btn-primary" value="Lihat Struk" <?= disableLihatStruk($d['statusTransaksi'])?>>
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
              <th>Username</th>
              <th>Judul Buku</th>
              <th>Pelanggaran</th>
              <th>Total Biaya</th>
              <th>Tanggal <br>Transaksi</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody class="table table-bordered border-secondary table-light">
            <tr>
              <td colspan="10">
                <h2>Belum ada data transaksi</h2>
              </td>
            </tr>
          </tbody>
        </table>
      <?php }
      ?>
    </div>
  </section>

</main>
<footer>

</footer>
</body>
</html>