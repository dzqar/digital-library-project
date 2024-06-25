<?php 
include '../koneksi.php';
$nav = $lib->navbarIndex('hidden');
$mode = $lib->toggleMode();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Icon Title -->
  <link rel="icon" href="../style/logo/logo.jpeg" type="image/x-icon">
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
  <!-- jQuery -->
  <script src="../script/jquery-3.7.1.min.js"></script>
  <!-- Color Modes Bootstrap -->
  <script src="/perpus/script/color-modes.js"></script>
  <title>Daftar</title>
</head>
<body>
  <script>
    <?php include '../script/pesan.js' ?>
  </script>
  <script>
    <?php
    ?>
    // Function showPass yang baru
    $(document).ready(function() {
      // Ketika <a> yang didalam <div id="pw"> di click
      $("#pw a").on('click', function(event) {
        // Membatalkan tindakan onclick (kalo di nonaktifin, href="" nya bakal jalan)
        event.preventDefault();
        // Jika <input type="text">
        if($('#pw input').attr("type") == "text"){
          // Mengubahnya menjadi "password"
          $('#pw input').attr('type', 'password');
          // Menambah class di <i> atau icon mata nya
          $('#pw i').addClass( "bi-eye-slash text-secondary" ); //Icon mata dicoret
          // Menghapus class
          $('#pw i').removeClass( "bi-eye text-primary" ); //Icon mata biasa
          // Jika <input type="password">
        }else if($('#pw input').attr("type") == "password"){
          // Mengubahnya menjadi text
          $('#pw input').attr('type', 'text');
          // Menghapus class
          $('#pw i').removeClass( "bi-eye-slash text-secondary" );
          // Menambah class
          $('#pw i').addClass( "bi-eye text-primary" );
        }
      });
    });
  </script>
  <!-- Header -->
  <?php //include '../header.html' ?>
  <!-- Penutup header -->
  <main>
    <section>
      <div class="container d-flex justify-content-center"> 
       <form action="proses.php" method="POST">
        <div class="card text-center bordered border-secondary mb-5" style="width: 22rem;">
          <h3 class="card-header bg-danger text-white bordered border-secondary">
            Daftar
          </h3>
          <div class="card-body">
            <div class="row">
              
            <div class="col mb-3">
              <label for="exampleInputtext1" class="form-label">Nama Lengkap</label>
              <input type="text" autocomplete="off" class="form-control border border-1 border-secondary" name="nama" placeholder="Masukkan nama lengkap anda" required>
            </div>
            <div class="col mb-3">
              <label for="exampleInputtext1" class="form-label">E-Mail</label>
              <input type="text" autocomplete="off" class="form-control border border-1 border-secondary" name="email" placeholder="Masukkan email anda" required>
            </div>
            </div>
            <div class="mb-3">
              <label for="exampleInputtext1" class="form-label">Alamat</label>
              <textarea name="alamat" class="form-control border border-1 border-secondary" required placeholder="Masukkan alamat anda" style="resize:none;"></textarea>
            </div>
            <div class="mb-3">
              <label for="exampleInputtext1" class="form-label">Username</label>
              <input type="text" autocomplete="off" class="form-control border border-1 border-secondary" name="username" placeholder="Masukkan username anda" required>
            </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Password</label>
              <!-- Pake Icon Mata -->
              <div class="input-group" id="pw">
                <input type="password" autocomplete="off" name="password" class="form-control border border-1 border-secondary" placeholder="Masukkan password anda" required>
                <div class="input-group-text border-secondary">
                  <a href="" class="text-secondary"><i class="bi bi-eye-slash" aria-hidden="true"></i></a>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <a href="login.php">Sudah Punya Akun?</a>
            </div>
            <input type="submit" name="btn" value="Daftar" class="btn btn-primary">
          </div>
          <div class="card-footer bordered border-secondary">
            <a href="#" onclick="history.back()">Kembali</a>
          </div>
        </div>
      </form>   
    </div>    
  </section>
</main>

<?php //include '../s&k.php' ?>
</body>
</html>