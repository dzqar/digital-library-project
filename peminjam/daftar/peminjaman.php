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
$tampilDaftarPeminjaman = $globalicClass->tampilDaftarPeminjaman("peminjaman.*,buku.*,u1.username AS uPeminjam","INNER JOIN user AS u1 ON peminjaman.id_peminjam=u1.id_user WHERE id_peminjam=$id GROUP BY id_peminjaman ORDER BY id_peminjaman DESC LIMIT $start,$limit");

// COUNT
$tampilDaftarPeminjaman1 = mysqli_query($db->koneksi,"SELECT COUNT(id_peminjaman) AS id FROM peminjaman WHERE id_peminjam=$id ");
$d = mysqli_fetch_assoc($tampilDaftarPeminjaman1);
$total = $d['id'];
$pages = ceil($total / $limit);

$previous = $page - 1;
$next = $page + 1;

$nav = $lib->navbarPeminjam('hidden','hidden','',$username);
$mode = $lib->toggleMode();

/*function batalPinjam($status){
  switch ($status) {
    case 'Meminjam':
    case 'Dikembalikan':
    case 'Telat':
    case 'Hilang':
    case 'Rusak':
    case 'Batal':
    return 'hidden';
    break;
    case 'Belum Diambil':
    return '';
    default:
    return 'Error!?';
    break;
  }
}*/
function hiddenBatal($status,$idPinjam){
  switch ($status) {
    // Jika status belum bayar, menampilkan tombol modal batal pembelian + mengisi alasan
    case 'Belum Diambil':
    return "<button type='button' class='btn btn-danger me-1' data-bs-toggle='modal' data-bs-target='#batalModal$idPinjam'>Batal</button>";
    break;

    // Jika status batal, menampilkann tombol untuk memunculkan modal alasan
    case 'Batal':
    return "<button type='button' class='btn btn-danger me-1' data-bs-toggle='modal' data-bs-target='#alasanModal$idPinjam'>Lihat Alasan</button>";
    break;
    
    default:
    return '';
    break;
  }
}

// Memunculkan tombol lihat struk kalau status 'Belum Diambil' & pelanggaran
function hiddenLihatStruk($status,$idPinjam){
  switch ($status) {
    case 'Belum Diambil':
    case 'Meminjam':
    case 'Dikembalikan':
    case 'Rusak':
    case 'Hilang':
    case 'Telat':
    return "
    <form action='../downloadstruk.php' method='POST' target='_blank' class='d-inline'>
    <input type='text' name='id_peminjaman' value='$idPinjam' hidden>
    <input type='submit' class='btn btn-primary me-1 d-inline' value='Lihat Struk'>
    </form>";
    break;
    
    case 'Batal':
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
        if (isset($tampilDaftarPeminjaman)) {
         ?>
         <table border="1" class="table table-bordered border-secondary">
          <thead class="table table-bordered table-danger border-secondary">
            <tr>
              <th>No</th>
              <th>Judul Buku</th>
              <th>Tanggal <br>Pinjam</th>
              <th>Tanggal <br>Kembali</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody class="table table-bordered border-secondary table-light">
            <?php
            $no = 1;
            foreach($tampilDaftarPeminjaman as $d){
              ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= $d['judul'] ?></td>
                <td><?php echo $d['tgl_pinjam'];?></td>
                <td><?php echo $d['tgl_kembali'];?></td>
                <td><?= $d['status']?></td>
                <td>
                  <div class="d-inline">
                    <?= hiddenBatal($d['status'],$d['id_peminjaman']).hiddenLihatStruk($d['status'],$d['id_peminjaman'])?>
                  </div>
                </td>
              </tr>
              <!-- Modal Batal -->
              <div class="modal fade" id="batalModal<?= $d['id_peminjaman']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="../proses.php" method="POST">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Alasan Batal</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <input type="number" name="id_peminjaman" value="<?= $d['id_peminjaman']?>" hidden>
                        <textarea name="alasan" id="" class="form-control" cols="10" rows="1"></textarea>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <input type="submit" value="Batal" name="btn" class="btn btn-danger">
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- Modal Alasan -->
              <div class="modal fade" id="alasanModal<?= $d['id_peminjaman']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
              <th>Tanggal <br>Pinjam</th>
              <th>Tanggal <br>Kembali</th>
              <th>Status</th>
              <th>Alasan</th>
              <th>Struk</th>
            </tr>
          </thead>
          <tbody class="table table-bordered border-secondary table-light text-center">
            <tr>
              <td colspan="10">
                <h2>Belum ada data peminjaman</h2>
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