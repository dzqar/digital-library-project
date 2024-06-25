<?php
session_start();
include '../koneksi.php';

// Jika Pengguna sudah melakukan login, maka akan dialihkan ke halaman index.php sesuai role
if (isset($_SESSION['username']) && isset($_SESSION['role'])) {
  if($_SESSION['role'] == "Peminjam"){
      // Customer
    header('location: /perpus/peminjam/?pesan=pernah_login');
    exit;
  }elseif($_SESSION['role'] == "Petugas") {
      // Kasir
    header('location:/perpus/petugas/?pesan=pernah_login');
    exit;
  }elseif($_SESSION['role'] == "Administrator") {
      // Admin
    header('location:/perpus/admin/?pesan=pernah_login');
    exit;
  }
}
$nav = $lib->navbarIndex('hidden');

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
  <!-- jQuery -->
  <script src="../script/jquery-3.7.1.min.js"></script>
  <!-- Color Modes Bootstrap -->
  <script src="/perpus/script/color-modes.js"></script>
  <title>LOGIN</title>
</head>
<body>

  <!-- Script untuk menampilkan sweetalert -->
  <script>
    <?php     
    include '../script/pesan.js';
    // Jika sudah 3x percobaan, maka akan memunculkan pesan error
    if (isset($_SESSION['auth'])) {
      if ($_SESSION['auth'] >= 3) { ?>
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: 'Anda sudah gagal untuk yang ke-3 kalinya!',
          allowOutsideClick: false,
          allowEscapeKey: false,
          confirmButtonText: 'Ok'
        })
        <?php
              // Kalo mau terus"an di disabled form nya, matiin session_destroy() kecuali kalo lu mau aktifin tiap refresh/bolak-balik halaman
        session_destroy();
              // exit();
      }
    } 

    ?>

  </script>

  <!-- Show Password -->
  <script>
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
  <div class="bg">
    <main>
      <section>
        <div class="container d-flex justify-content-center">
          <form action="proses.php" method="POST">
            <div class="card text-center bordered border-secondary mb-5" style="width: 22rem;">
              <h3 class="card-header bg-danger text-white bordered border-secondary">
                Login
              </h3>
              <div class="card-body">
                <?php
                if (isset($_SESSION['auth']) && $_SESSION['auth'] >= 3) { ?>
                  <!-- Jika sudah lebih dari 3x percobaan, maka form akan di disabled -->
                  <div class="mb-3">
                    <label for="exampleInputtext1" class="form-label">Username</label>
                    <input type="text" class="form-control border border-1 border-secondary" name="username" placeholder="Anda sudah tidak bisa lagi login!" disabled >
                  </div>
                  <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control border border-1 border-secondary" placeholder="Anda sudah tidak bisa lagi login!" disabled >
                  </div>
                  <input type="submit" value="Login" class="btn btn-primary" disabled>
                  <?php
                }else{ ?>
                  <!-- Jika tidak ada auth/masih ada kesempatan buat login -->
                  <div class="mb-3">
                    <label for="exampleInputtext1" class="form-label">Username</label>
                    <input type="text" class="form-control border border-1 border-secondary" name="username" placeholder="Masukkan username anda" >
                  </div>
                  <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <!-- Pake Icon Mata -->
                    <div class="input-group" id="pw">
                      <input type="password" name="password" class="form-control border border-1 border-secondary" placeholder="Masukkan password anda" >
                      <div class="input-group-text border border-1 border-secondary">
                        <a href="" class="text-secondary"><i class="bi bi-eye-slash" aria-hidden="true"></i></a>
                      </div>
                    </div>
                  </div>
                  <div class="mb-3">
                    <a href="daftar.php">Belum Punya Akun?</a>
                  </div>
                  <input type="submit" name="btn" value="Login" class="btn btn-primary">
                <?php } ?>
              </div>
              <div class="card-footer bordered border-secondary">
                <a onclick="history.back()">Kembali</a>
              </div>
            </div>    
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>