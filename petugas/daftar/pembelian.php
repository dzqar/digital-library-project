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
$tampilDaftarPembelian = $globalicClass->tampilDaftarPembelian("pembelian.*,buku.*,u1.username AS uPeminjam","INNER JOIN user AS u1 ON pembelian.id_peminjam=u1.id_user WHERE pembelian.id_petugas IN(0,$id) ORDER BY status DESC LIMIT $start,$limit");

// COUNT
$tampilDaftarPembelian1 = mysqli_query($globalicClass->koneksi,"SELECT COUNT(id_pembelian) AS id FROM pembelian WHERE id_petugas IN('0','$id')");

// PAGINATION
if (isset($tampilDaftarPembelian1)) {
  $d = mysqli_fetch_assoc($tampilDaftarPembelian1);
  $total = $d['id'];
  $pages = ceil($total / $limit);
}

$previous = $page - 1;
$next = $page + 1;

$nav = $lib->navbarPetugas($username);
$mode = $lib->toggleMode();

// Menghilangkan tombol batal dan memunculkan lihat alasan
function hiddenBatal($status,$idBeli,$idBuku,$jumlahBeli){
  switch ($status) {
    // Jika status belum bayar, menampilkan tombol modal batal pembelian + mengisi alasan
    case 'Belum Bayar':
    return "
    <form action='proses.php' method='POST'>
    <input type='number' name='id_pembelian' value='$idBeli' hidden>
    <input type='number' name='id_buku' value='$idBuku' hidden>
    <button type='submit' name='btn' value='lunasPembelian' class='btn btn-primary'>Lunas</button>
    <button type='button' class='btn btn-danger me-1' data-bs-toggle='modal' data-bs-target='#batalModal$idBeli'>Batal</button>
    ";
    break;

    // Jika status batal, menampilkann tombol untuk memunculkan modal alasan
    case 'Batal':
    return "<button type='button' class='btn btn-danger me-1' data-bs-toggle='modal' data-bs-target='#alasanModal$idBeli'>Lihat Alasan</button>";
    break;
    
    case 'Lunas':
    default:
    return '';
    break;
  }
}

// Menghilangkan tombol lihat struk kalo statusnya batal
function hiddenLihatStruk($status,$idBeli){
  switch ($status) {
    case 'Lunas':
    return "
    <form action='../downloadstruk.php' method='POST' target='_blank' class='d-inline'>
    <input type='text' name='id_pembelian' value='$idBeli' hidden>
    <input type='submit' class='btn btn-primary me-1 d-inline' value='Lihat Struk'>
    </form>";
    break;
    
    case 'Belum Bayar':
    case 'Batal':
    default:
    return '';
    break;
  }
}

// Untuk menghilangkan tombol tambah bukti pembayaran
function hiddenBukti($status,$idBeli,$metBayar){
  global $db;
  $data = mysqli_query($db->koneksi,"SELECT bukti_pembayaran AS bp FROM pembelian WHERE id_pembelian='$idBeli'");
  $cek = mysqli_fetch_assoc($data)['bp'];
  // echo isset($cek);
  switch ($status) {
    case 'Belum Bayar':
    if ($metBayar !== 'Cash') {
      if (isset($cek) && $cek > 0) {
        return "<button class='btn btn-success' type='button' data-bs-toggle='modal' data-bs-target='#modalBukti$idBeli'>Lihat Bukti</button>";    
      }
    }else{
      return '';
    }
    break;

    case 'Lunas':
    if (isset($cek) && $cek < 0) {
      return '';
    }else{
      return "<button class='btn btn-success' type='button' data-bs-toggle='modal' data-bs-target='#modalBukti$idBeli'>Lihat Bukti</button>";
    }
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
  <title>Daftar Pembelian - Petugas</title>
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
        if (isset($tampilDaftarPembelian)) {
         ?>
         <table border="1" class="table table-bordered border-secondary">
          <thead class="table table-bordered table-danger border-secondary">
            <tr>
              <th>No</th>
              <th>Username</th>
              <th>Judul Buku</th>
              <th>Jumlah Beli</th>
              <th>Total Biaya</th>
              <th>Metode Pembayaran</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody class="table table-bordered border-secondary table-light">
            <?php
            $no = 1;
            foreach($tampilDaftarPembelian as $d){
              ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= $d['uPeminjam'] ?></td>
                <td><?= $d['judul'] ?></td>
                <td><?= $d['jumlah_beli'] ?></td>
                <td>Rp. <?= number_format($d['total_biaya'],'0','','.') ?></td>
                <td><?= $d['metode_pembayaran'];?></td>
                <td><?= $d['status']?></td>
                <td>
                 <div class="d-inline">
                  <?= hiddenBatal($d['status'],$d['id_pembelian'],$d['id_buku'],$d['jumlah_beli']).hiddenLihatStruk($d['status'],$d['id_pembelian']).hiddenBukti($d['status'],$d['id_pembelian'],$d['metode_pembayaran'])?>
                </div>
              </td>
            </tr>
            <!-- Modal Batal -->
            <div class="modal fade" id="batalModal<?= $d['id_pembelian']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form action="proses.php" method="POST">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Alasan Batal</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <input type="number" name="id_pembelian" value="<?= $d['id_pembelian']?>" hidden>
                      <textarea name="alasan" id="" class="form-control" cols="10" rows="1"></textarea>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                      <button type="submit" name="btn" value="batalPembelian" class="btn btn-danger">Batal</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Modal Alasan -->
            <div class="modal fade" id="alasanModal<?= $d['id_pembelian']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Alasan Batal</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <textarea name="alasan" id="" class="form-control" cols="10" rows="1" readonly=""><?= $d['alasan']?></textarea>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal Bukti Bayar -->
            <div class="modal fade" id="modalBukti<?= $d['id_pembelian']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content modal-fullscreen-sm-down">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Bukti Pembayaran untuk <q><?= $d['judul']?></q></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <img src="/perpus/peminjam/daftar/bukti/<?= $d['bukti_pembayaran']?>" alt="Bukti Bayar" class="img-fluid d-flex mx-auto" style="width: 40%">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                  </div>
                </div>
              </div>
            </div>
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
            <th>Jumlah Beli</th>
            <th>Total Biaya</th>
            <th>Metode Pembayaran</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody class="table table-bordered border-secondary table-light text-center">
          <tr>
            <td colspan="10">
              <h2>Belum ada data pembelian</h2>
            </td>
          </tr>
        </tbody>
      </table>
      <?php
    }
    ?>
  </div>
</section>

</main>
<footer>

</footer>
</body>
</html>