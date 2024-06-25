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
$idBayar = $_POST['id_pembelian'];


if (!peminjam()) {
  header("location: /perpus/$roleFolder/?pesan=noaccess");
}
// LIMIT
$tampilDaftarPembelian = $globalicClass->tampilDaftarPembelian("*","WHERE pembelian.id_pembelian='$idBayar'");

$nav = $lib->navbarPeminjam('hidden','hidden','',$username);
$mode = $lib->toggleMode();

function disableBatal($status){
  switch ($status) {
    case 'Batal':
    case 'Lunas':
    return 'disabled hidden';
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
  <link rel="icon" href="/perpus/style/logo/logo.png" type="image/x-icon">
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
  <title>Bukti Pembayaran - Peminjam</title>
</head>
<body id="home">
  <main>
    <section class="main">
      <div class="container pt-3">
        <?php
        $no = 1;
        foreach($tampilDaftarPembelian as $d){
          ?>

          <div class="row text-center mb-2">
            <h1>Bukti Pembayaran</h1>
          </div>

          <div class="row justify-content-center">
            <div class="card" style="width: 18rem;">
              <img src="#" id="preview" alt="bukti pembayaran" class="img-fluid mx-auto mb-2" style="width: 65%">
              <div class="card-body">
                <form action="../proses.php" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Apa anda yakin ingin mengirim foto buktinya? Anda tidak bisa mengeditnya lagi setelah ini!')">
                  <input type="number" name="id_pembelian" value="<?= $d['id_pembelian']?>" hidden>
                  <div class="input-group justify-content-center mb-2">
                    <label for="foto" class="input-group-text"><i class="bi bi-file-earmark-image me-2"></i> Unggah</label>
                    <input type="file" name="foto" id="foto" class="form-control visually-hidden" accept=".png,.jpg,.jpeg" onchange="pripiw()" required>
                  </div>
                  <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                  <div class="text-end">
                    <a class="btn btn-danger text-decoration-none" onclick="history.back()">Kembali</a>
                    <button type="submit" class="btn btn-primary" name="btn" value="tambahBuktiPembayaran">Kirim</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <?php
        }
        ?>
      </div>
    </section>
    <script type="text/javascript">
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
                    preview.src = "#";
                    preview.style.display= "none";
                  }
                }
              </script>
            </body>
            </html>