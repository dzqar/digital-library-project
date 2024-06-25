<?php
session_start();

// Menghubungkan file proses dengan koneksi
include '../koneksi.php';

if (isset($_POST['btn'])) {
	$btnForm = $_POST['btn'];
	if ($btnForm === "Daftar") {
		// Menampung data dari form
		$nama = $_POST['nama'];
		$alamat = $_POST['alamat'];
		$email = $_POST['email'];
		$username = $_POST['username'];
		$password = $_POST['password'];

		// Kondisi jika username sudah/pernah terdaftar
		$check = mysqli_query($db->koneksi,"SELECT * FROM user WHERE username='$username'");
		if (mysqli_num_rows($check) > 0) {
			// Mengalihkan ke halaman daftar.php dan memberi pesan jika ada username yang terdaftar sebelumnya
			header('location:daftar.php?pesan=usernameAlready');
		}else{
			// Menginput data ke database tbl_user jika tidak ada data yang sama
			$globalicClass->daftar($nama, $alamat, $email, $username, $password);
		}
	}elseif ($btnForm === "Login") {
		// Menangkap data nilai dari form inputan terdapat di input.php
		$username = mysqli_real_escape_string($db->koneksi, $_POST['username']);
		$password = mysqli_real_escape_string($db->koneksi, $_POST['password']);
		// Login untuk memeriksa keberadaan user dalam tabel user
		$login = mysqli_query($db->koneksi,"SELECT * FROM user WHERE username = '$username' AND password = '$password'");
		$cek = mysqli_num_rows($login);

		// cek apakah username dan password di temukan pada database
		if($cek > 0){

			$data = mysqli_fetch_assoc($login);

  			//Mengecek apakah username dan passwordnya SAMA seperti yang ada di database
			if ($username === $data['username'] && $password === $data['password']) {
				// cek jika user login sebagai Administrator
				if($data['role']=="Administrator"){

					// buat session login dan username
					$_SESSION['id_user'] = $data['id_user'];
					$_SESSION['username'] = $data['username'];
					$_SESSION['role'] = "Administrator";
					$_SESSION['is_logged_in'] = true;
					// alihkan ke halaman dashboard Administrator
					header("location:../admin/");

				// cek jika user login sebagai Petugas
				}else if($data['role']=="Petugas"){
					// buat session login dan username
					$_SESSION['id_user'] = $data['id_user'];
					$_SESSION['username'] = $data['username'];
					$_SESSION['role'] = "Petugas";
					$_SESSION['is_logged_in'] = true;
					// alihkan ke halaman dashboard pegawai
					header("location:../petugas/");

				// cek jika user login sebagai Peminjam
				}else if($data['role']=="Peminjam"){
					// buat session login dan username
					$_SESSION['id_user'] = $data['id_user'];
					$_SESSION['username'] = $data['username'];
					$_SESSION['role'] = "Peminjam";
					$_SESSION['is_logged_in'] = true;
					// alihkan ke halaman dashboard pengurus
					header("location:../peminjam/");

				}else{
					// alihkan ke halaman login kembali
					header("location:login.php?pesan=gagal");
				}
			}else{
  				// Dialihkan kembali ke halaman login jika usernamenya tidak sesuai yang ada di database
				header("location:login.php?pesan=gagal");
			}


		}else{
			header("location:login.php?pesan=gagal");

				// Buat mencegah login berulang
			$_SESSION['auth'] = $_SESSION['auth'];
			$_SESSION['pass'] = NULL;
			if(isset($_SESSION['auth'])){
				$_SESSION['auth']++;
			}else{
				$_SESSION['auth'] = 1;
			}
		}
	}else{
		header("location: login.php?pesan=gagalDaftaratauLogin");
	}
}else{
	header("location: login.php?pesan=gagalNoIssetBtn");
}

?>