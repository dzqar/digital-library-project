<?php 
session_start();

// Tampungan dari proses login
$id = $_SESSION['id_user'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];


include '../koneksi.php';

if (isset($_POST['btn']) || isset($_GET['btn'])) {
	$btn = $_POST['btn'];
	$idBuku = $_POST['id_buku'];
	$harga = $_POST['harga'];
	$estimasi = $_POST['estimasi'];
	$format = $_POST['format'];
	$tgl = date('Y-m-d');

	/*$dbbuku = $db->tampilBuku("WHERE kategori_buku_relasi.id_buku='$idBuku'");
	foreach ($dbbuku as $d) {
		$kategori = $d['nama_kategori'];
	}*/

		// var_dump($_POST['rate']);

	if ($btn === 'Pinjam') {
		// $tglKembali = date('Y-m-d' ,strtotime($tgl.' +'. $estimasi .' week'));
		// $totaltgl = date('Y-m-d', strtotime($tglKembali));
		$kode = rand(1111,9999).'_'.$id;

		$peminjamClass->prosesPinjamBuku($id,$idBuku,$kode,$estimasi);
		header('location:/perpus/peminjam/daftar/peminjaman.php?pesan=berhasilPinjamBuku');
	}elseif ($btn === 'Batal') {
		$idPeminjaman = $_POST['id_peminjaman'];
		$alasan = $_POST['alasan']." ~ $username ($role)";
		// mysqli_query($db->koneksi,"UPDATE peminjaman SET status='$status' WHERE id_peminjaman='$id_peminjaman'");
		$petugasClass->prosesUbahPeminjaman($idPeminjaman,7,",alasan='$alasan'");
		header('location:/perpus/peminjam/daftar/peminjaman.php?pesan=berhasilBatalPinjamBuku');
	}elseif ($btn === 'Beli') {
		$metBayar = $_POST['metode_pembayaran'];
		$jumlah = $_POST['jumlah'];
		$totalBiaya = $jumlah * $harga;
		if ($totalBiaya === 0 || $totalBiaya === '0') {
			// Status : Lunas
			$peminjamClass->prosesBeliBuku($id,$idBuku,$jumlah,$totalBiaya,'2',$metBayar);
		}else{
			/*if ($format === 'Fisik') {
				mysqli_query($db->koneksi,"UPDATE buku SET stok=stok-$jumlah WHERE id_buku='$idBuku'");
			}*/
			// Status : Belum Bayar
			$peminjamClass->prosesBeliBuku($id,$idBuku,$jumlah,$totalBiaya,'1',$metBayar);
		}
		header('location:/perpus/peminjam/daftar/pembelian.php?pesan=berhasilBeliBuku');
	}elseif ($btn === 'BatalBeli') {
		$idBeli = $_POST['id_pembelian'];
		// $status = $_POST['status'];
		$jumlah = $_POST['jumlah'];
		$alasan = $_POST['alasan']." ~ $username ($role)";
		/*if ($format === 'Fisik') {
			mysqli_query($db->koneksi,"UPDATE buku SET stok=stok+$jumlah WHERE id_buku='$idBuku'");
		}*/
		$petugasClass->prosesUbahPembelian($idBeli,'3',",alasan='$alasan'");
		header('location:/perpus/peminjam/daftar/pembelian.php?pesan=berhasilBatalBeliBuku');
	}elseif ($btn === 'kirimUlasan') {
		$ulasan = $_POST['ulasan'];
		$rating = $_POST['rating'];
		$peminjamClass->tambahUlasan($id,$idBuku,$ulasan,$rating);
		var_dump($rating);
		header('location:/perpus/peminjam/?pesan=berhasilRatingBuku');
	}elseif ($btn === 'tambahBuktiPembayaran') {
		$idPembelian = $_POST['id_pembelian'];
		$rand = rand();
		if (isset($_FILES['foto']['name'])) {
			// Foto
			$ekstensi = array('png','jpg','jpeg');
			$foto = $_FILES['foto']['name'];
			// var_dump($foto);
			$ukuran = $_FILES['foto']['size'];
			$ext = pathinfo($foto, PATHINFO_EXTENSION);

			if (!empty($_FILES['foto']['name'])) {
				if (!in_array($ext,$ekstensi)) {
					header('location:/perpus/peminjam/daftar/pembelian.php?pesan=gagalEkstensiFoto');
				}else{
					// Mengecek Ukuran
					if ($ukuran < 2044070) {
				// Jika ukurannya lebih kecil dari 2mb
						$xx = $rand.'_'.$foto;

					// Mengecek data di column 'foto' berdasakan id_user
						$bukti = mysqli_query($db->koneksi,"SELECT bukti_pembayaran FROM pembelian WHERE id_peminjam='$id'");
						$cs = mysqli_fetch_assoc($bukti)['bukti_pembayaran'];
						if ($cs) {
							unlink('daftar/bukti/'.$cs);
						}

					// Menambah file ke dalam folder 'gambar'
						move_uploaded_file($_FILES['foto']['tmp_name'],'daftar/bukti/'.$xx);

					// Query SQL
						mysqli_query($db->koneksi,"UPDATE pembelian SET bukti_pembayaran='$xx' WHERE id_pembelian=$idPembelian");

					// Mengalihkan ke halaman dari  masing-masing + pesan untuk Pop Up Sweetalert
						header('location:/perpus/peminjam/daftar/pembelian.php?pesan=berhasilTambahBukti');

					}else{
					// Jika ukurannya lebih besar dari sistem
						header('location:/perpus/peminjam/daftar/pembelian.php?pesan=gagalUkuran');
					}
				}
			}
		}else{
			header('location:/perpus/peminjam/daftar/pembelian.php?pesan=fotoTidakTerdeteksi');
		}
	}elseif (isset($_GET['btn']) && $_GET['btn'] === 'tambahKoleksi') {
		$idBuku = $_GET['id_buku'];
		$metBayar = "Cash";
		$jumlah = 1;
		$totalBiaya = $jumlah * 0;
		$peminjamClass->prosesBeliBuku($id,$idBuku,$jumlah,$totalBiaya,'2',$metBayar);
		header('location:/perpus/peminjam/koleksi/?pesan=berhasilBeliBuku');
	/*}elseif ($btn === 'EditUlasan') {
		$idUlasan = $_POST['id_ulasan'];
		$ulasan = $_POST['ulasan'];
		$rating = $_POST['rate'];
		var_dump($ulasan);
		var_dump($rating);
		$peminjamClass->prosesEditUlasan("ulasan='$ulasan',rating='$rating' WHERE id_ulasan='$idUlasan'");
		header('location:/perpus/peminjam/?pesan=BerhasilRatingBuku');*/
	}elseif (isset($_GET['btn']) && $_GET['btn'] === 'HapusUlasan') {
		$peminjamClass->hapusUlasan($_GET['id_user'],$_GET['id_buku']);
		header('location:/perpus/peminjam/?pesan=berhasilHapusUlasan');
	}else{
		header('location:/perpus/peminjam/?pesan=gagalNoBtn');
	}
/*}else{
	header('location:/perpus/peminjam/?pesan=gagalNoIssetBtn');*/
}
?>