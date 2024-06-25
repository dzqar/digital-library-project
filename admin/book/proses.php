<?php 
	// mulai session
session_start();

	// Include koneksi
include '../../koneksi.php';

if (isset($_POST['btn']) || isset($_GET['btn'])) {
	// Nangkep $_POST['']; dan ditampung di variabel
	$idBuku = $_POST['id_buku'];
	$judul = $_POST['judul'];
	$penulis = $_POST['penulis'];
	$penerbit = $_POST['penerbit'];
	$tahun_terbit = $_POST['tahun_terbit'];
	$deskripsi = $_POST['deskripsi'];
	$halaman = $_POST['halaman'];
	$kategori = $_POST['kategori'];
	$stok = $_POST['stok'];
	$harga = str_replace(".", "", $_POST['harga']);
	$genres = $_POST['genre'];
	$format = $_POST['format'];
	// $genre = implode(", ",$_POST['genre']); TAMBAH 1 YAITU FORMAT

	$rand = rand();

	/* PROSES EDIT */
	if ($_POST['btn'] === 'Edit') {

		// Mengecek, jika input type='file' ada isinya/file, maka akan menjalankan IF condition
		if (isset($_FILES['foto']['name']) || isset($_FILES['file_buku']['name'])) {
			$genre = implode(", ",$_POST['genre']);
			// Foto
			$ekstensi = array('png','jpg','jpeg');
			$fileSampul = $_FILES['foto']['name'];
			// var_dump($fileSampul);
			$ukuran = $_FILES['foto']['size'];
			$ext = pathinfo($fileSampul, PATHINFO_EXTENSION);

			// File PDF
			$ekstend = array('pdf');
			$fileBuku = $_FILES['file_buku']['name'];
			// var_dump($fileBuku);
			$ukuranfileBuku = $_FILES['file_buku']['size'];
			$extBuku = pathinfo($fileBuku, PATHINFO_EXTENSION);

			// Jika input sampul dan file pdf, maka akan update kedua nya
			if (!empty($_FILES['foto']['name']) && !empty($_FILES['file_buku']['name'])) {
			// Mengecek Ekstensi
				if (!in_array($ext,$ekstensi) && !in_array($extBuku,$ekstend)) {
					header('location: ../book/?pesan=gagalEkstensiBuku');
				}else{
				// Mengecek Ukuran
					if ($ukuran < 2044070 && $ukuranfileBuku < 104407000) {
				// Jika ukurannya lebih kecil dari 2mb
						$sampul = $rand.'_'.$fileSampul;
						$filePDF = $rand.'_'.$fileBuku;

						/*Sampul*/
					// Mengecek data di column 'foto' berdasakan id_user
						$cekSampul = mysqli_query($db->koneksi,"SELECT sampul_buku FROM buku WHERE id_buku='$idBuku'");
						$cs = mysqli_fetch_assoc($cekSampul)['sampul_buku'];
						if ($cs) {
							unlink('../../style/buku/sampul/'.$cs);
						}

					// Menambah file ke dalam folder 'gambar'
						move_uploaded_file($_FILES['foto']['tmp_name'],'../../style/buku/sampul/'.$sampul);

						/*File PDF*/
					// Mengecek data di column 'file_buku' berdasakan id_user
						$cekFile = mysqli_query($db->koneksi,"SELECT file_buku FROM buku WHERE id_buku='$idBuku'");
						$cf = mysqli_fetch_assoc($cekFile)['file_buku'];
						if ($cf) {
							unlink('../../style/buku/file/'.$cf);
						}

					// Menambah file ke dalam folder 'gambar'
						move_uploaded_file($_FILES['file_buku']['tmp_name'],'../../style/buku/file/'.$filePDF);

					// Query SQL
						$adminClass->prosesEditBuku($idBuku,$judul,$penulis,$penerbit,$tahun_terbit,$deskripsi,$sampul,$filePDF,$halaman,$stok,$harga,$kategori);
						mysqli_query($db->koneksi,"DELETE FROM genre_buku_relasi WHERE id_buku='$idBuku'");
							// Menyiapkan query INSERT
						$query = "INSERT INTO genre_buku_relasi (id_buku, id_genre) VALUES ";

							// Menambahkan nilai ke query INSERT
						foreach ($genres as $genre) {
							$query .= "($idBuku, $genre), ";
						}

							// Menghapus koma terakhir dari query INSERT
						$query = rtrim($query, ", ");

						mysqli_query($db->koneksi, $query);

					// Mengalihkan ke halaman dari  masing-masing + pesan untuk Pop Up Sweetalert
						header('location:../book/?pesan=berhasilUpSemua');

					}else{
					// Jika ukurannya lebih besar dari sistem
						header('location:../book/?pesan=gagalUkuran');
					}
				}
			}elseif (!empty($_FILES['foto']['name']) && empty($_FILES['file_buku']['name'])) {
			// Mengecek Ekstensi
				if (!in_array($ext,$ekstensi)) {
					header('location: ../book/?pesan=gagalEkstensiBuku');
				}else{
				// Mengecek Ukuran
					if ($ukuran < 2044070) {
				// Jika ukurannya lebih kecil dari 2mb
						$sampul = $rand.'_'.$fileSampul;

					// Mengecek data di column 'foto' berdasakan id_user
						$cekSampul = mysqli_query($db->koneksi,"SELECT sampul_buku FROM buku WHERE id_buku='$idBuku'");
						$cs = mysqli_fetch_assoc($cekSampul)['sampul_buku'];
						if ($cs) {
							unlink('../../style/buku/sampul/'.$cs);
						}

					// Menambah file ke dalam folder 'gambar'
						move_uploaded_file($_FILES['foto']['tmp_name'],'../../style/buku/sampul/'.$sampul);

					// Query SQL
						$adminClass->prosesEditBukuSampul($idBuku,$judul,$penulis,$penerbit,$tahun_terbit,$deskripsi,$sampul,$halaman,$stok,$harga,$kategori);
						mysqli_query($db->koneksi,"DELETE FROM genre_buku_relasi WHERE id_buku='$idBuku'");
							// Menyiapkan query INSERT
						$query = "INSERT INTO genre_buku_relasi (id_buku, id_genre) VALUES ";

							// Menambahkan nilai ke query INSERT
						foreach ($genres as $genre) {
							$query .= "($idBuku, $genre), ";
						}

							// Menghapus koma terakhir dari query INSERT
						$query = rtrim($query, ", ");

						mysqli_query($db->koneksi, $query);

					// Mengalihkan ke halaman dari  masing-masing + pesan untuk Pop Up Sweetalert
						header('location:../book/?pesan=berhasilUpSampul');

					}else{
					// Jika ukurannya lebih besar dari sistem
						header('location:../book/?pesan=gagalUkuran');
					}
				}
			}elseif (empty($_FILES['foto']['name']) && !empty($_FILES['file_buku']['name'])) {
			// Mengecek Ekstensi
				if (!in_array($extBuku,$ekstend)) {
					header('location: ../book/?pesan=gagalEkstensiBuku');
				}else{
				// Mengecek Ukuran
					if ($ukuranfileBuku < 104407000) {
				// Jika ukurannya lebih kecil dari 2mb
						$sampul = $rand.'_'.$fileBuku;
						$cekFile = mysqli_query($db->koneksi,"SELECT file_buku FROM buku WHERE id_buku='$idBuku'");
						$cf = mysqli_fetch_assoc($cekFile)['file_buku'];
						if ($cf) {
							unlink('../../style/buku/file/'.$cf);
						}

					// Menambah file ke dalam folder 'gambar'
						move_uploaded_file($_FILES['file_buku']['tmp_name'],'../../style/buku/file/'.$sampul);

					// Query SQL
						$adminClass->prosesEditBukuFileBuku($idBuku,$judul,$penulis,$penerbit,$tahun_terbit,$deskripsi,$sampul,$halaman,$stok,$harga,$kategori);
						mysqli_query($db->koneksi,"DELETE FROM genre_buku_relasi WHERE id_buku='$idBuku'");
							// Menyiapkan query INSERT
						$query = "INSERT INTO genre_buku_relasi (id_buku, id_genre) VALUES ";

							// Menambahkan nilai ke query INSERT
						foreach ($genres as $genre) {
							$query .= "($idBuku, $genre), ";
						}

							// Menghapus koma terakhir dari query INSERT
						$query = rtrim($query, ", ");

						mysqli_query($db->koneksi, $query);

					// Mengalihkan ke halaman dari level masing-masing + pesan untuk Pop Up Sweetalert
						header('location:../book/?pesan=berhasilUpFile');

					}else{
					// Jika ukurannya lebih besar dari sistem
						header('location:../book/?pesan=gagalUkuran');
					}
				}
			}elseif (empty($_FILES['foto']['name']) && empty($_FILES['file_buku']['name'])) {
				$adminClass->prosesEditBukuNoTwo($idBuku,$judul,$penulis,$penerbit,$tahun_terbit,$deskripsi,$halaman,$stok,$harga,$kategori);
				mysqli_query($db->koneksi,"DELETE FROM genre_buku_relasi WHERE id_buku='$idBuku'");
							// Menyiapkan query INSERT
				$query = "INSERT INTO genre_buku_relasi (id_buku, id_genre) VALUES ";

							// Menambahkan nilai ke query INSERT
				foreach ($genres as $genre) {
					$query .= "($idBuku, $genre), ";
				}

							// Menghapus koma terakhir dari query INSERT
				$query = rtrim($query, ", ");

				mysqli_query($db->koneksi, $query);
				header('location:../book/?pesan=berhasilUpWithoutFiles');
			}else{
				header('location:?pesan=ifNgecekEmptyError');
			}
		}else{
			header('location:?pesan=noIssetFiles');
		}
	}elseif ($_GET['btn'] === 'Hapus') {
		/* PROSES HAPUS */
		$idBuku = $_GET['id_buku'];
		/*Sampul*/
		// Mengecek data di column 'foto' berdasakan id_user
		$cekSampul = mysqli_query($db->koneksi,"SELECT sampul_buku FROM buku WHERE id_buku='$idBuku'");
		$cs = mysqli_fetch_assoc($cekSampul)['sampul_buku'];
		if ($cs) {
			unlink('../../style/buku/sampul/'.$cs);
		}
		/*File PDF*/
		// Mengecek data di column 'file_buku' berdasakan id_user
		$cekFile = mysqli_query($db->koneksi,"SELECT file_buku FROM buku WHERE id_buku='$idBuku'");
		$cf = mysqli_fetch_assoc($cekFile)['file_buku'];
		if ($cf) {
			unlink('../../style/buku/file/'.$cf);
		}
		$adminClass->hapusBuku($idBuku);
		header('location:../book/?pesan=berhasilHapusBuku');
	}elseif ($_POST['btn'] === 'Tambah') {
		/* PROSES TAMBAH BUKU */

		// Tidak Bisa Upload File PDF
		if ($format === '1') {
			/* FORMAT : FISIK */

			if (isset($_FILES['foto']['name'])) {
				// Foto
				$ekstensi = array('png','jpg','jpeg');
				$fileSampul = $_FILES['foto']['name'];
				// var_dump($fileSampul);
				$ukuran = $_FILES['foto']['size'];
				$ext = pathinfo($fileSampul, PATHINFO_EXTENSION);

				/* JIKA INPUT FILE FOTO TIDAK KOSONG */
				if (!empty($_FILES['foto']['name'])) {
				// Mengecek Ekstensi
					if (!in_array($ext,$ekstensi)) {
						header('location: ../book/?pesan=gagalEkstensiBuku');
					}else{
				// Mengecek Ukuran
						if ($ukuran < 2044070) {
				// Jika ukurannya lebih kecil dari 2mb
							$sampul = $rand.'_'.$fileSampul;

					// Menambah file ke dalam folder 'gambar'
							move_uploaded_file($_FILES['foto']['tmp_name'],'../../style/buku/sampul/'.$sampul);

					// Query SQL
							$adminClass->tambahBukuFisik($judul,$penulis,$penerbit,$tahun_terbit,$deskripsi,$sampul,$kategori,$halaman,$stok,$harga,$format);
							$data = mysqli_query($db->koneksi,"SELECT MAX(id_buku) AS id FROM buku");
							$d = mysqli_fetch_array($data);
							$key = $d['id'];
							// Menyiapkan query INSERT
							$query = "INSERT INTO genre_buku_relasi (id_buku, id_genre) VALUES ";

							// Menambahkan nilai ke query INSERT
							foreach ($genres as $genre) {
								$query .= "($key, $genre), ";
							}

							// Menghapus koma terakhir dari query INSERT
							$query = rtrim($query, ", ");

							mysqli_query($db->koneksi, $query);

					// Mengalihkan ke halaman dari  masing-masing + pesan untuk Pop Up Sweetalert
							header('location:../book/?pesan=berhasilTambahBukuFisik');

						}else{
					// Jika ukurannya lebih besar dari sistem
							header('location:../book/?pesan=gagalUkuran');
						}
					}
				}else{
					/* JIKA KOSONG */
					header('location:../book/?pesan=inputFileKosong');
				}
			}else{
				header('location:../book/?pesan=issetFotoKosong');
			}
		}elseif($format === '2'){
			/* FORMAT : E-BOOK */

			if (isset($_FILES['foto']['name']) && isset($_FILES['file_buku']['name'])) {
				// Foto
				$ekstensi = array('png','jpg','jpeg');
				$fileSampul = $_FILES['foto']['name'];
				// var_dump($fileSampul);
				$ukuran = $_FILES['foto']['size'];
				$ext = pathinfo($fileSampul, PATHINFO_EXTENSION);
				// File PDF
				$ekstend = array('pdf');
				$fileBuku = $_FILES['file_buku']['name'];
				// var_dump($fileBuku);
				$ukuranfileBuku = $_FILES['file_buku']['size'];
				$extBuku = pathinfo($fileBuku, PATHINFO_EXTENSION);
				// Jika input sampul dan file pdf, maka akan update kedua nya
				if (!empty($_FILES['foto']['name']) && !empty($_FILES['file_buku']['name'])) {
					// Mengecek Ekstensi
					if (!in_array($ext,$ekstensi) && !in_array($extBuku,$ekstend)) {
						header('location: ../book/?pesan=gagalEkstensiBuku');
					}else{
					// Mengecek Ukuran
						if ($ukuran < 2044070 && $ukuranfileBuku < 104407000) {
					// Jika ukurannya lebih kecil dari 2mb
							$sampul = $rand.'_'.$fileSampul;
							$filePDF = $rand.'_'.$fileBuku;

							/*Sampul*/
						// Menambah file ke dalam folder 'gambar'
							move_uploaded_file($_FILES['foto']['tmp_name'],'../../style/buku/sampul/'.$sampul);

							/*File PDF*/
						// Menambah file ke dalam folder 'gambar'
							move_uploaded_file($_FILES['file_buku']['tmp_name'],'../../style/buku/file/'.$filePDF);

						// Query SQL
							$adminClass->tambahBukuEBook($judul,$penulis,$penerbit,$tahun_terbit,$deskripsi,$sampul,$filePDF,$kategori,$halaman,$stok,$harga,$format);
							$data = mysqli_query($db->koneksi,"SELECT MAX(id_buku) AS id FROM buku");
							$d = mysqli_fetch_array($data);
							$key = $d['id'];
							// Menyiapkan query INSERT
							$query = "INSERT INTO genre_buku_relasi (id_buku, id_genre) VALUES ";

							// Menambahkan nilai ke query INSERT
							foreach ($genres as $genre) {
								$query .= "($key, $genre), ";
							}

							// Menghapus koma terakhir dari query INSERT
							$query = rtrim($query, ", ");

							mysqli_query($db->koneksi, $query);

						// Mengalihkan ke halaman dari  masing-masing + pesan untuk Pop Up Sweetalert
							header('location:../book/?pesan=berhasilTambahEbook');

						}else{
						// Jika ukurannya lebih besar dari sistem
							header('location:../book/?pesan=gagalUkuran');
						}
					}
				}
			}
		}else{
			header('location:../book/?pesan=noMoreFormat');
		}
	}else{
		header('location:../book/?pesan=gagalNoBtn');
	}
}else{
	header('location ../book/?pesan=gagalNoIssetBtn');
}
?>