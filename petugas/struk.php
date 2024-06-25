<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<style>
		main {
			/*top: 50%;*/
			left: 50%;
			position: absolute;
			transform: translate(-50%,0);
			font-size: 19px;
		}
		footer {
			position: fixed;  
			left: 10px;  
			bottom: 5px;  
			right: 10px;
			text-align: center;  
		}
	</style>
	<header style="background-color: red; color: white; padding: 5px 5px">
		<table border="0" width="100%">
			<tr>
				<th align="left" style="font-size: 26px;">Merdeka Membaca</th>
				<td align="right">Jalan Pakai Kaki, Jawa Barat, Indonesia</td>
			</tr>
		</table>
	</header>
	<hr>
	
	<?php
	session_start();
		// Koneksi
	include '../koneksi.php';

		// Variable
	if (isset($_POST['id_pembelian'])) {
		$id = $_POST['id_pembelian'];
		$data = $globalicClass->tampilDaftarPembelian('*',"INNER JOIN user AS u1 ON pembelian.id_peminjam=u1.id_user WHERE id_pembelian='$id'");
		foreach ($data as $d) {
			?>

			<!-- LOOP PEMBELIAN -->
			<center>
				<h1>Data Pembelian</h1>
			</center>
			<main>
				<table style="width: 100%;">
					<tr>
						<td>Nama Lengkap</td>
						<td>:</td>
						<td><?= $d['nama_lengkap']?></td>
					</tr>
					<tr>
						<td>Username</td>
						<td>:</td>
						<td><?= $d['username']?></td>
					</tr>
					<tr>
						<td>Judul</td>
						<td>:</td>
						<td><?= $d['judul']?></td>
					</tr>
					<tr>
						<td>Format</td>
						<td>:</td>
						<td><?= $d['format']?></td>
					</tr>
					<tr>
						<td>Jumlah Beli</td>
						<td>:</td>
						<td><?= $d['jumlah_beli']?></td>
					</tr>
					<tr>
						<td>Harga Buku</td>
						<td>:</td>
						<td>Rp. <?= number_format($d['harga'],'0','','.')?></td>
					</tr>
					<tr>
						<td>Total Biaya</td>
						<td>:</td>
						<td>Rp. <?= number_format($d['total_biaya'],'0','','.')?></td>
					</tr>
					<tr>
						<td>Metode Pembayaran</td>
						<td>:</td>
						<td><?= $d['metode_pembayaran']?></td>
					</tr>
				</table>
				<!-- <p>Nama Lengkap : <?= $d['nama_lengkap']?></p>
				<p>Username : <?= $d['username']?></p>
				<p>Judul : <?= $d['judul']?></p>
				<p>Format : <?= $d['format']?></p>
				<p>Jumlah Beli : <?= $d['jumlah_beli']?></p>
				<p>Harga Buku : Rp. <?= number_format($d['harga'],'0','','.')?></p>
				<p>Total Biaya : Rp. <?= number_format($d['total_biaya'],'0','','.')?></p>
				<p>Metode Pembayaran : <?= $d['metode_pembayaran']?></p> -->
				<!-- <p>Tanggal Pembelian : <?= $d['tgl_beli']?></p> -->
				<!-- <p><?= $d['']?></p> -->
			</main>
			<?php
		}
	}elseif(isset($_POST['id_peminjaman'])){ 
		$id = $_POST['id_peminjaman'];
		$data = $globalicClass->tampilDaftarPeminjaman('*',"INNER JOIN user AS u1 ON peminjaman.id_peminjam=u1.id_user WHERE id_peminjaman='$id'");
		foreach ($data as $d) {
			?>

			<!-- LOOP Peminjaman -->
			<center>
				<h1>Data Peminjaman</h1>
			</center>
			<main>
				<table>
					<tr>
						<td>Nama Lengkap</td>
						<td>:</td>
						<td><?= $d['nama_lengkap']?></td>
					</tr>
					<tr>
						<td>Username</td>
						<td>:</td>
						<td><?= $d['username']?></td>
					</tr>
					<tr>
						<td>Judul</td>
						<td>:</td>
						<td><?= $d['judul']?></td>
					</tr>
					<tr>
						<td>Tanggal Pinjam</td>
						<td>:</td>
						<td><?= $d['tgl_pinjam']?></td>
					</tr>
					<tr>
						<td>Tanggal Kembali</td>
						<td>:</td>
						<td><?= $d['tgl_kembali']?></td>
					</tr>
					<tr>
						<td>Estimasi</td>
						<td>:</td>
						<td><?= $d['estimasi']?> Minggu</td>
					</tr>
				</table>
				<center>
					<h2>Kode : <?= $d['kode']?></h2> 
				</center>
			</main>

			<?php
		}
	}elseif (isset($_POST['id_transaksi'])) { 
		$id = $_POST['id_transaksi'];
		$data = $globalicClass->tampilDaftarTransaksi("WHERE id_transaksi='$id'");
		foreach ($data as $d) {
			?>

			<!-- LOOP Transaksi -->
			<center>
				<h1>Data Transaksi</h1>
			</center>
			<main>
				<table>
					<tr>
						<td>Nama Lengkap</td>
						<td>:</td>
						<td><?= $d['nama_lengkap']?></td>
					</tr>
					<tr>
						<td>Username</td>
						<td>:</td>
						<td><?= $d['uPeminjam']?></td>
					</tr>
					<tr>
						<td>Judul</td>
						<td>:</td>
						<td><?= $d['judul']?></td>
					</tr>
					<tr>
						<td>Pelanggaran</td>
						<td>:</td>
						<td><span style="color: red"><?= $d['pelanggaran']?></span></td>
					</tr>
					<tr>
						<td>Total Denda</td>
						<td>:</td>
						<td>Rp. <?= number_format($d['total_biaya'],'0','','.')?></td>
					</tr>
					<tr>
						<td>Tanggal Pinjam</td>
						<td>:</td>
						<td><?= $d['tgl_pinjam']?></td>
					</tr>
					<tr>
						<td>Tanggal Kembali</td>
						<td>:</td>
						<td><?= $d['tgl_kembali']?></td>
					</tr>
				</table>
				<center>
					<h2>Kode : <?= $d['kode']?></h2> 
				</center>
			</main>

			<?php
		}
	}else{
		echo 'no more $_POST';
	}
	?>
	
	<footer>
		<hr>
		&copy; Copyright since 2023
	</footer>
</body>
</html>