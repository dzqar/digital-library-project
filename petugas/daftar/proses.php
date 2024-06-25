<?php 
session_start();

// Tampungan dari proses login
$id = $_SESSION['id_user'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

$idPeminjaman = $_POST['id_peminjaman'];
$idTransaksi = $_POST['id_transaksi'];
$idBuku = $_POST['id_buku'];
$status = $_POST['status'];
$jumlahBeli = $_POST['jumlah_beli'];
$pelanggaran = $_POST['pelanggaran'];
$estimasi = $_POST['estimasi'];
include '../../koneksi.php';

if (isset($_POST['btn'])) {
	$btn = $_POST['btn'];
	$idPeminjam = $_POST['id_peminjam'];
	$harga = $_POST['harga'];
	if ($btn === 'UbahPeminjaman') {
		switch ($status) {
			// Status : Meminjam
			case 'Meminjam':
			$tglKembali = date('Y-m-d' ,strtotime($tgl.' +'. $estimasi .' week'));
			$totaltgl = date('Y-m-d', strtotime($tglKembali));
			$petugasClass->prosesUbahPeminjaman($idPeminjaman,$status,", id_petugas=$id, tgl_pinjam=NOW(), tgl_kembali='$totaltgl'");
			header('location:/perpus/petugas/daftar/peminjaman.php?pesan=berhasilUbahStatusPeminjaman');
			break;
			// Status : Dikembalikan | Batal
			case 'Dikembalikan':
			// case 'Batal':
			$petugasClass->prosesUbahPeminjaman($idPeminjaman,$status,'');
			header('location:/perpus/petugas/daftar/peminjaman.php?pesan=berhasilUbahStatusPeminjaman');
			break;
			// Status : Telat
			case 'Telat':
			$totalBiaya = ($harga * 30)/100;
			$petugasClass->tambahTransaksi($idPeminjam,$id,$idPeminjaman,$status,$totalBiaya);
			$petugasClass->prosesUbahPeminjaman($idPeminjaman,$status,'');
			header('location:/perpus/petugas/daftar/transaksi.php?pesan=berhasilUbahStatusPeminjaman');
			break;
			// Status : Hilang
			case 'Hilang':
			$totalBiaya = $harga;
			$petugasClass->tambahTransaksi($idPeminjam,$id,$idPeminjaman,$status,$totalBiaya);
			$petugasClass->prosesUbahPeminjaman($idPeminjaman,$status,'');
			header('location:/perpus/petugas/daftar/transaksi.php?pesan=berhasilUbahStatusPeminjaman');
			break;
			// Status : Rusak
			case 'Rusak':
			$totalBiaya = ($harga * 50)/100;
			$petugasClass->tambahTransaksi($idPeminjam,$id,$idPeminjaman,$status,$totalBiaya);
			$petugasClass->prosesUbahPeminjaman($idPeminjaman,$status,'');
			header('location:/perpus/petugas/daftar/transaksi.php?pesan=berhasilUbahStatusPeminjaman');
			break;
			// Status : Batal
			case 'Batal':
			$alasan = $_POST['alasan']." ~ $username ($role)";
			$petugasClass->prosesUbahPeminjaman($idPeminjaman,$status,", alasan='$alasan'"); // Tambahin update alasan
			header('location:/perpus/petugas/daftar/peminjaman.php?pesan=berhasilUbahStatusPeminjaman');
			break;
			// Default
			default:
			echo "Error?";
			break;
		}
	}elseif ($btn === 'UbahTransaksi') {
		switch ($status) {
			case 'Lunas':
			$petugasClass->prosesUbahTransaksi($idTransaksi,$status);
			header('location:transaksi.php?pesan=berhasilUbahTransaksi');
			break;
			case 'Batal':
			$alasan = $_POST['alasan']." ~ $username ($role)";
			$petugasClass->prosesUbahTransaksi($idTransaksi,$status);

			if ($pelanggaran !== 'Hilang') {
				mysqli_query($db->koneksi,"UPDATE buku SET stok = stok-1 WHERE id_buku=$idBuku");
			}
			header('location:transaksi.php?pesan=berhasilUbahTransaksi');
			break;
			
			default:
			echo 'Error?';
			break;
		}
	}elseif ($btn === 'lunasPembelian') {
		$idPembelian = $_POST['id_pembelian'];
		$petugasClass->prosesUbahPembelian($idPembelian,'2',",id_petugas='$id'");
		header('location:pembelian.php?pesan=berhasilLunasPembelian');
	}elseif ($btn === 'batalPembelian') {
		$idPembelian = $_POST['id_pembelian'];
		$alasan = $_POST['alasan']." ~ $username ($role)";
		/*if ($format === 'Fisik') {
			mysqli_query($petugasClass->koneksi,"UPDATE buku SET stok=stok+$jumlahBeli WHERE id_buku='$idBuku'");
		}*/
		$petugasClass->prosesUbahPembelian($idPembelian,'3',",alasan='$alasan'");
		header('location:pembelian.php?pesan=berhasilBatalPembelian');
	}elseif ($btn === 'batalLunasPembelian') {
		$idPembelian = $_POST['id_pembelian'];
		/*if ($format === 'Fisik') {
			mysqli_query($petugasClass->koneksi,"UPDATE buku SET stok=stok+$jumlahBeli WHERE id_buku='$idBuku'");
		}*/
		$petugasClass->prosesUbahPembelian($idPembelian,'1','');
		header('location:pembelian.php?pesan=berhasilBatalLunasPembelian');
	}
}
?>
