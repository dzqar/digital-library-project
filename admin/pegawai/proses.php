<?php 
	// mulai session
session_start();

	// Include koneksi
include '../../koneksi.php';

if (isset($_POST['btn']) || isset($_GET['btn'])) {
	$btn = $_POST['btn'];

	$idUser = $_POST['id_user'];
	$role = $_POST['role'];
	$username = $_POST['username'];
	$pw = $_POST['pw'];
	$email = $_POST['email'];
	$namaLengkap = $_POST['nama_lengkap'];
	$alamat = $_POST['alamat'];

	$data = mysqli_query($db->koneksi,"SELECT * FROM user WHERE username='$username'");
	$cek = mysqli_num_rows($data);

	if ($cek > 0) {
		header('location:/perpus/admin/pegawai/?pesan=usernameAlreadyUser');
	}else{

		if ($btn ==='Tambah') {
			$adminClass->tambahUser($role,$username,$pw,$email,$namaLengkap,$alamat);
			header('location:/perpus/admin/pegawai/?pesan=berhasilTambahUser');
		}elseif ($btn === 'Edit') {
			$adminClass->editUser($idUser,$role,$username,$pw,$email,$namaLengkap,$alamat);
			header('location:/perpus/admin/pegawai/?pesan=berhasilEditUser');
		}elseif ($_GET['btn'] === 'Hapus'){
			$adminClass->hapusUser($_GET['id_user']);
			header('location:/perpus/admin/pegawai/?pesan=berhasilHapusUser');
		}else{
			header('location:/perpus/admin/pegawai/?pesan=gagalNoBtn');
		}
	}
}else{
	header('location:/perpus/admin/pegawai/?pesan=gagalNoIssetBtn');
}

?>