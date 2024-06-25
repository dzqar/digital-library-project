<?php
session_start();
error_reporting(0);
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
$idBuku = $_POST['id_buku'];

$data_buku = $globalicClass->tampilBuku("WHERE buku.id_buku='$idBuku' GROUP BY buku.id_buku");
$rataRating = $globalicClass->rataRating($idBuku);
// $koleksi = $peminjamClass->tampilKoleksi("");
$tampilRating= $globalicClass->tampilRating("INNER JOIN user ON ulasan.id_user = user.id_user WHERE id_buku='$idBuku' ORDER BY id_ulasan DESC ");
$tampilDaftarTransaksi = $globalicClass->tampilDaftarTransaksi("WHERE transaksi.id_peminjam='$id' ORDER BY transaksi.id_transaksi DESC LIMIT 1");
$tampilEditUlasan = $peminjamClass->tampilEditUlasan("WHERE id_user='$id' AND id_buku='$idBuku'");

if (isset($username)) {
  $nav = $lib->navbarPeminjam('hidden','hidden','',$username);
}else{
  $nav = $lib->navbarIndex('hidden');
}

foreach ($tampilDaftarTransaksi as $tdt) {
  $statusTransaksi = $tdt['statusTransaksi'];
}

$namaBuku = mysqli_query($db->koneksi,"SELECT judul FROM buku WHERE id_buku='$idBuku'");
$key = mysqli_fetch_assoc($namaBuku);

// Menghilangkan label di format buku tertentu
function tampilStok($format){
  switch ($format) {
    case 'E-Book':
    return " hidden ";
    break;
    case 'Fisik':
    return "";
    break;
    default:
    return "Error?";
    break;
  }
}

// Menonaktifkan tombol jika stok buku dengan format 'Fisik' itu habis (0)
function tombol($format){
  if ($format === 'Fisik') {
    return '';
  }elseif ($format === 'E-Book') {
    return ' hidden ';
  }
}

// Mengecek & menampilkan harga
function harga($harga){
  switch ($harga) {
    case '0':
    case 0:
    return 'Tambah ke koleksi';
    break;
    
    default:
    return 'Beli Rp. '.number_format($harga,'0','','.');
    break;
  }
}

// Mengecek & menampilkan stok berdasarkan sisa/habis stok
function jumlahStok($stok){
  switch ($stok) {
    case '0':
    case 0:
    return 'Stok Habis';
    break;
    
    default:
    return "Stok : $stok";
    break;
  }
}

// Menghilangkan input number untuk jumlah pembelian di Modal (Bootstrap)
function jumlahBeli($format){
  switch ($format) {
    case 'E-Book':
    return "visually-hidden ";
    break;

    case 'Fisik':    
    default:
    return '';
    break;
  }
}

// Menghilangkan metode pembayaran tertentu berdasarkan format
function metBayar($format) {
  switch ($format) {
    case 'Fisik':
    return 'hidden ';
    break;
    
    case 'E-Book':
    default:
    return '';
    break;
  }
}

// Menghilangkan option "Cash" pada format buku yang 'E-Book'
function cashOption($format){
  switch ($format) {
    case 'Fisik':
    return '';
    break;
    
    case 'E-Book':
    default:
    return 'hidden';
    break;
  }
}

// Langsung memilih metode pembayaran tertentu berdasarkan format buku yang ingin dibeli
function opsiBayar($format) {
  switch ($format) {
    case 'Fisik':
    return 'selected ';
    break;
    
    case 'E-Book':
    default:
    return '';
    break;
  }
}

// Menampilkan rata-rata rating dari user
function rating($id){
  global $globalicClass;
  $rataRating = $globalicClass->rataRating($id);
  if (empty($rataRating)) {
    return '0.0';
  }else{
    foreach ($rataRating as $key) { 
      $rataRating = $key['rataRating'];
    }
  }
  return $rataRating;
}

// Menampilkan jumlah pengguna yang melakukan rating tiap buku
function jumlahUser($id){
  global $globalicClass;
  $jumlahUser = $globalicClass->rataRating($id);
  if (empty($jumlahUser)) {
    return '0 pengguna';
  }else{
    foreach ($jumlahUser as $key) { 
      $jumlahUser = $key['jumlahUser'];
    }
  }
  return number_format($jumlahUser,'0','','.').' pengguna';
}

// Menampilkan & menghilangkan tombol 'Tampil PDF' jika bukunya ada/tidak di koleksipribadi
function hiddenTampilPDF($id,$format){
 global $db;
 global $idBuku;
 $cek = mysqli_query($db->koneksi,"SELECT * FROM koleksipribadi WHERE id_user='$id' AND id_buku='$idBuku'");
 if ($format === 'E-Book') {
  if (mysqli_num_rows($cek) > 0) {
   return '';
 }else{
   return 'hidden ';
 }
}else{
  return 'hidden ';
}
}

// Menghilangkan tombol beli/tambah pada buku E-Book jika bukunya sudah ada di koleksipribadi
/*function cekKoleksi($id,$format){
 global $db;
 global $idBuku;
 $cek = mysqli_query($db->koneksi,"SELECT * FROM koleksipribadi WHERE id_user='$id' AND id_buku='$idBuku'");
 switch ($format) {
  case 'E-Book':
  switch (mysqli_num_rows($cek)) {
   case '0':
   case 0:
   return '';
   break;

   default:
   return 'hidden ';
   break;
 }
 break;
 
 case 'Fisik':
 default:
 return '';
 break;
}
}*/

// Menghilangkan tombol pinjam jika status transaksinya belum lunas
function statusTransaksi($status,$id,$target,$format,$stok,$harga){
  global $db;
  global $idBuku;
  if (!isset($id)) {
    if ($stok <= 0) {
      return ' disabled ';
    }else{
      // return " data-bs-toggle='modal' data-bs-target='#$target'";
      return 'onclick="return alertIsloggedIn('."'$target'".')"';
    }
  }else{
    $cek = mysqli_query($db->koneksi,"SELECT * FROM koleksipribadi WHERE id_user='$id' AND id_buku='$idBuku'");
    if ($format === 'Fisik') {
      switch ($status) {
        case 'Belum Lunas':
        // return " onclick=".'"return alertTransaksi()" ';
        return 'onclick="return alertTransaksi('."'$target'".')"';
        break;

        case 'Lunas':
        default:
        if ($stok <= 0) {
          return ' disabled ';
        }else{
          return " data-bs-toggle='modal' data-bs-target='#$target'";
        }
        break;
      }
    }elseif($format === 'E-Book'){
      switch ($status) {
        case 'Belum Lunas':
        return " onclick=".'"return alertTransaksi()" ';
        break;

        case 'Lunas':
        default:
        if (mysqli_num_rows($cek) > 0) {
          // Kalau belum ada di koleksi, maka akan menghide tombol beli
          return 'hidden';
        }else{
          if ($harga <= 0) {
            return " onclick=".'"window.location.href='."'proses.php?btn=tambahKoleksi&id_buku=$idBuku'".'"';
          }else{
            return " data-bs-toggle='modal' data-bs-target='#$target' ";
          }
        }
        break;
      }
    }
  }
}

function hideRating($id,$format){
  global $idBuku;
  global $db;
  $dataKoleksi = mysqli_query($db->koneksi,"SELECT * FROM koleksipribadi WHERE id_buku='$idBuku' AND id_user='$id'");
  $cekKoleksi = mysqli_num_rows($dataKoleksi);
  $k = ($cekKoleksi > 0) ? true : false;
  var_dump($k);

  $dataRating = mysqli_query($db->koneksi,"SELECT * FROM ulasan WHERE id_user='$id' AND id_buku='$idBuku'");
  $cekRating = mysqli_num_rows($dataRating);
  $r = ($cekRating > 0) ? true : false;
  var_dump($r);

  $dataPinjam = mysqli_query($db->koneksi,"SELECT * FROM peminjaman WHERE id_peminjam='$id' AND id_buku='$idBuku' AND status IN(3,4,5,6)");
  $cekPinjam = mysqli_num_rows($dataPinjam);
  $p = ($cekPinjam > 0) ? true : false;
  // var_dump($cekPinjam);

  $dataBeli = mysqli_query($db->koneksi,"SELECT * FROM pembelian WHERE id_peminjam='$id' AND id_buku='$idBuku' AND status='Lunas'");
  $cekBeli = mysqli_num_rows($dataBeli);
  $b = ($cekbeli > 0) ? true : false;
  // var_dump($cekBeli);

  if (!isset($id)) {
    // Jika user belum login
    return 'visually-hidden';
  }else{
    switch ($format) {
      /*
      - Cek Rating : Kalo udah pernah rating sebelumnya
      - Cek Koleksi : Kalo ada buku dikoleksinya
      - Cek Pinjam : Kalo sudah pernah meminjam sebelumnya
      */
      case 'E-Book':
      if ($cekKoleksi > 0) {
        if ($cekRating > 0) {
          return 'visually-hidden';
        }else{
          return '';
        }
      }else{
        return 'visually-hidden';
      } 
      break;
      
      case 'Fisik':
      default:
      if ($cekPinjam > 0 || $cekBeli > 0) { 
        if ($cekRating > 0) {
          return 'visually-hidden';
        }else{
          return '';
        }
      }else{
        return 'visually-hidden';
      }
      break;
    }
  }
}

/*function isLoggedIn($id,$other,$harga){
  // global $id;
  if (!isset($id)) {
    return 'onclick="return alertIsloggedIn('."'$other'".')"';
  }else{
    if ($harga <= 0) {
      global $idBuku;
      return " href='proses.php?btn=tambahKoleksi&id_buku=$idBuku'";
    }
    return " data-bs-toggle='modal' data-bs-target='#$other'";
  }
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
  <!-- FontAwesome -->
  <link rel="stylesheet" href="../style/assets/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="styleRating/style.css">
  <script src="styleRating/script.js" defer></script>
  <!-- jQuery -->
  <script src="../script/jquery-3.7.1.min.js"></script>
  <!-- Color Modes Bootstrap -->
  <script src="../script/color-modes.js"></script>
  <title>Buku <?= $key['judul'] ?> - Peminjam</title>
</head>
<body>
  <script type="text/javascript">
    function alertTransaksi(target) {
      if (target == 'pinjamModal') {
        Swal.fire({
          icon: "error",
          title: "Tidak Bisa Meminjam Buku",
          text: "Anda harus melunaskan denda di transaksi!"
        // footer: '<a href="#">Why do I have this issue?</a>'
      });
      }else{
        Swal.fire({
          icon: "error",
          title: "Tidak Bisa Membeli Buku",
          text: "Anda harus melunaskan denda di transaksi!"
        // footer: '<a href="#">Why do I have this issue?</a>'
      });
      }
    }

    function alertIsloggedIn(other){
      if (other == 'beliModal') {
        Swal.fire({
          title: "Tidak Bisa Membeli Buku",
          text: "Anda harus login terlebih dahulu",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Login",
          cancelButtonText: "Batal"
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = '../form/login.php';
          }
        });
      }else{
        Swal.fire({
          title: "Tidak Bisa Meminjam Buku",
          text: "Anda harus login terlebih dahulu",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Login",
          cancelButtonText: "Batal"
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = '../form/login.php';
          }
        });
      }
    }
  </script>
  <?php foreach ($data_buku as $d) {?>
    <section class="main">
      <div class="container-md border rounded">
        <div class="row p-2">
          <div class="col"><a onclick="history.back()" class="link-primary d-inline fs-4">Kembali</a></div>
        </div>
        <div class="row p-2">
          <!-- Sampul -->
          <div class="col-md-5  text-center p-2">
            <img src="../style/buku/sampul/<?= $d['sampul_buku']?>" alt="sampul" class="img-fluid " width="40%" height="40%" />
          </div>
          <!-- Detail Buku -->
          <div class="col-md-6 ">
            <h2 class="row "><?= $d['judul']?> </h2>
            <div class="row  fs-4"><?= $d['penulis']?></div>
            <div class="row  fs-5"><?= $d['penerbit']?></div>
            <div class="row  fs-5">Diterbitkan tahun <?= $d['tahun_terbit']?></div>
            <div class="row  fs-5 <?= ($d['stok'] <= 3) ? 'fw-bold text-danger' : ''?>" <?= tampilStok($d['format'])?>><?= jumlahStok($d['stok'])?></div>
            <div class="row row-cols-4  text-center">
              <div class="col-4 p-2">
                <div><?= rating($idBuku)?> <i class="bi bi-star-fill ms-1"></i></div>
                <?= jumlahUser($idBuku)?>
              </div>
              <div class="col-4 p-2 border-start border-end">
                <div><i class="bi bi-book-half"></i></div>
                <?= $d['format']?>
              </div>
              <div class="col-4 p-2">
                <div><i class="bi bi-file-earmark-fill"></i></div>
                <div><?= $d['halaman']?> Halaman</div>
              </div>
            </div>
            <div class="row row-cols-3 text-center">
              <div class="col  p-2">
                <button type="button" class="btn btn-primary" <?= statusTransaksi($statusTransaksi,$id,'beliModal',$d['format'],$d['stok'],$d['harga'])?>><?= harga($d['harga'])?></button>
              </div>
              <div class="col  p-2">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" <?= statusTransaksi($statusTransaksi,$id,'pinjamModal',$d['format'],$d['stok'],$d['harga']).tombol($d['format'])?>>Pinjam</button>
              </div>
              <div class="col  p-2">
                <form action="koleksi/book.php" method="POST" class="text-end d-inline" target="_blank">
                  <input type="text" name="file" value="<?= $d['file_buku']?>" hidden>
                  <input type="submit" value="Tampil PDF" class="btn btn-primary" <?= hiddenTampilPDF($id,$d['format'])?>>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="row  p-2 pb-4">
          <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#deskripsi" type="button" role="tab" aria-controls="deskripsi" aria-selected="true">Deskripsi</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#ulasan" type="button" role="tab" aria-controls="ulasan" aria-selected="false">Ulasan</button>
            </li>
          </ul>
          <div class="tab-content mt-4" id="myTabContent">
            <div class="tab-pane fade show active" id="deskripsi" role="tabpanel" aria-labelledby="deskripsi-tab" tabindex="0">
              <textarea cols="auto" rows="10" class="form-control" readonly>
                <?= $d['deskripsi']?>
              </textarea>
            </div>
            <div class="tab-pane fade" id="ulasan" role="tabpanel" aria-labelledby="ulasan-tab" tabindex="0">
              <!-- Input Rating -->
              <div class="row <?= hideRating($id,$d['format'])?>" >
                <form action="proses.php" method="POST">
                  <div class="card" style="width: 22rem;">
                    <h3 class="card-header">Beri Ulasan Anda</h3>
                    <div class="card-body">
                      <input type="number" name="id_user" value="<?= $id?>" hidden>
                      <input type="number" name="id_buku" value="<?= $idBuku?>" hidden>
                      <div class="mb-3">
                        <label class="form-label">Ulasan</label>
                        <textarea name="ulasan" cols="10" rows="3" class="form-control" style="border: 1px solid #FF7D2D" placeholder="Masukkan Ulasan Anda Disini" required></textarea>
                      </div>
                      <div class="mb-3">
                        <div class="stars">
                          <input type="radio" id="star5" name="rating" value="1" style="display: none;" />
                          <label for="star5" class="star ms-3">
                            <i class="fa-solid fa-star"></i>
                          </label>
                          <input type="radio" id="star4" name="rating" value="2" style="display: none;" />
                          <label for="star4" class="star ms-3">
                            <i class="fa-solid fa-star"></i>
                          </label>
                          <input type="radio" id="star3" name="rating" value="3" style="display: none;" />
                          <label for="star3" class="star ms-3">
                            <i class="fa-solid fa-star"></i>
                          </label>
                          <input type="radio" id="star2" name="rating" value="4" style="display: none;" />
                          <label for="star2" class="star ms-3">
                            <i class="fa-solid fa-star"></i>
                          </label>
                          <input type="radio" id="star1" name="rating" value="5" style="display: none;" />
                          <label for="star1" class="star ms-3">
                            <i class="fa-solid fa-star"></i>
                          </label>
                        </div>
                        <div class="text-center mt-3">
                          <!-- <input type="submit" value="Kirim" class="btn btn-primary"> -->
                          <button type="submit" name="btn" value="kirimUlasan" class="btn btn-primary">Kirim</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <!-- Ulasan -->
              <?php 
              if (isset($tampilRating)){
                foreach ($tampilRating as $key) { ?>
                  <div class="row">
                    <div class="col mb-2">
                      <div class="card">
                        <div class="card-header bg-danger text-white">
                          <div class="row">
                            <div class="col">
                              <span><?= $key['rating']?> <i class="bi bi-star-fill text-warning my-auto"></i></span>
                            </div>
                            <div class="col text-end">
                              <!-- <a class="bi bi-pencil-square me-3" id="mauEdit"></a> -->
                              <a href="proses.php?btn=HapusUlasan&id_user=<?= $id?>&id_buku=<?= $idBuku?>" class="bi bi-trash link-light mt-2 <?= ($key['id_user'] === $id) ? '' : 'd-none' ?>"></a>
                            </div>
                          </div>
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
              }else{
                echo "<h4>Belum ada ulasan dari siapapun</h4>";
              }
              ?>

              <!-- Edit Rating -->
                  <!-- <div class="row" id="editRating">
                    <form action="proses.php" method="POST">
                      <div class="card" style="width: 22rem;">
                        <h3 class="card-header">Edit Ulasan Anda</h3>
                        <div class="card-body">
                          <input type="number" name="id_ulasan" value="<?= $key['id_ulasan']?>" >
                          <div class="mb-3">
                            <label class="form-label">Ulasan</label>
                            <textarea name="ulasan" cols="10" rows="3" class="form-control" style="border: 1px solid #FF7D2D" placeholder="Masukkan Ulasan Anda Disini" required><?= $key['ulasan']?></textarea>
                          </div>
                          <div class="mb-3">
                            <div class="stars">
                              <input type="radio" id="star5" name="rate" value="1" style="display: none;" />
                              <label for="star5" class="star ms-3">
                                <i class="fa-solid fa-star"></i>
                              </label>
                              <input type="radio" id="star4" name="rate" value="2" style="display: none;" />
                              <label for="star4" class="star ms-3">
                                <i class="fa-solid fa-star"></i>
                              </label>
                              <input type="radio" id="star3" name="rate" value="3" style="display: none;" />
                              <label for="star3" class="star ms-3">
                                <i class="fa-solid fa-star"></i>
                              </label>
                              <input type="radio" id="star2" name="rate" value="4" style="display: none;" />
                              <label for="star2" class="star ms-3">
                                <i class="fa-solid fa-star"></i>
                              </label>
                              <input type="radio" id="star1" name="rate" value="5" style="display: none;" />
                              <label for="star1" class="star ms-3">
                                <i class="fa-solid fa-star"></i>
                              </label>
                            </div>
                            <div class="text-center mt-3">
                              <button type="submit" name="btn" value="EditUlasan" class="btn btn-primary">Edit</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div> -->
                  
                </div>
              <!-- <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">...</div>
                <div class="tab-pane fade" id="disabled-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">...</div> -->
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- Modal Pinjam -->
      <div class="modal fade" id="pinjamModal" tabindex="-1" aria-labelledby="pinjamModal" aria-hidden="true">
        <div class="modal-dialog">
          <form action="proses.php" method="POST">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="pinjamModal">Pinjam Buku "<?= $d['judul']?>"</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="id_buku" value="<?= $d['id_buku']?>">
                <label for="estimasi">Waktu meminjam</label>
                <select name="estimasi" class="form-select" required>
                  <option value selected hidden disabled>Pilih Waktu Meminjam</option>
                  <option value="1">1 Minggu</option>
                  <option value="2">2 Minggu</option>
                  <option value="3">3 Minggu</option>
                  <option value="4">4 Minggu</option>
                </select>
              </div>
              <div class="modal-footer">
                <input type="submit" name="btn" value="Pinjam" class="btn btn-primary" <?= tampilStok($d['format'])?>>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- Modal Beli -->
      <div class="modal fade" id="beliModal" tabindex="-1" aria-labelledby="beliModal" aria-hidden="true">
        <div class="modal-dialog">
          <form action="proses.php" method="POST">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="beliModal">Beli Buku "<?= $d['judul']?>"</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="id_buku" value="<?= $d['id_buku']?>">
                <input type="hidden" name="harga" value="<?= $d['harga']?>">
                <input type="hidden" name="format" value="<?= $d['format']?>">
                <div class="form-floating mb-2 <?= jumlahBeli($d['format'])?>">
                  <input type="number" class="form-control" name="jumlah" value="1" min="1" max="<?= $d['stok']?>" placeholder="jumlah">
                  <label for="jumlah" class="form-label">Jumlah Beli</label>
                </div>
                <select name="metode_pembayaran" id="metbayar" class="form-select">
                  <option value selected hidden disabled>Silahkan Pilih Metode Pembayaran</option>
                  <option value="Cash" <?= opsiBayar($d['format']).cashOption($d['format'])?>>Cash</option>
                  <option value="Dana (088808580061)" <?= metBayar($d['format'])?>>Dana (088808580061)</option>
                </select>
              </div>
              <div class="modal-footer">
                <button type="submit" name="btn" value="Beli" class="btn btn-primary">Beli</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    <?php } ?>

    <script type="text/javascript">
      /*$(document).ready(function() {
        $("#editRating").hide();
        $("#mauEdit").click(function() {
    // Toggle the visibility of the form container
    $("#editRating").toggle();
  });
});*/

      // function mauEdit(){
      //   var edit_rating = document.getElementById('editRating').style.display='';
      // }
    </script>

  </body>
  </html>