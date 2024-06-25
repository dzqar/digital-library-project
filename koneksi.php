<?php
/**
 * MAIN CLASS FOR CONNECTION
 */
class database {
	// Membuat variabel seperti pada file koneksi.php pada umumnya
	var $host = "localhost";
	var $username = "root";
	var $password = "";
	var $database = "perpus";

	// Fungsi untuk membuat koneksi ke MySQL untuk menyambungkan database
	function __construct(){
		$this->koneksi = mysqli_connect($this->host,$this->username,$this->password,$this->database);
		if (mysqli_connect_errno()) {
			echo "Koneksi database gagal : ".mysqli_connect_error();
		}
	}
}
$db = new database();

/**
 * GLOBAL CLASS
 */
class globalic extends database{
	/*AWAL GLOBAL FUNCTION*/
		// Mendaftarkan akun calon customer
		function daftar($nama, $alamat, $email, $username, $password) {
			mysqli_query($this->koneksi,"INSERT INTO user values('',3,'$username','$password','$email','$nama','$alamat')");
				// Mengalihkan halaman ke index.php
			header('location:login.php');
		}

		// Menampilkan profile lebih detail
		function profil($id) {
			$data = mysqli_query($this->koneksi,"SELECT
				foto as gambar,
				nama,
				alamat as lokasi,
				email as tlp,
				username as un,
				password as pw
				FROM tbl_user WHERE id_user = $id");

			while ($d = mysqli_fetch_array($data)) {
				$hasil[] = $d;
			}
			return $hasil;
		}

		// Menampilkan Data Buku
		function tampilBuku($kondisi) {
			$data = mysqli_query($this->koneksi,"SELECT buku.id_buku,buku.sampul_buku,buku.judul,buku.deskripsi,buku.harga,buku.penulis,buku.penerbit,buku.tahun_terbit,buku.halaman,buku.format,buku.stok,buku.file_buku,REPLACE(GROUP_CONCAT(genre_buku.nama_genre),',',', ') AS genreS,kategori_buku.nama_kategori FROM ((((buku INNER JOIN kategori_buku_relasi ON buku.id_buku = kategori_buku_relasi.id_buku) INNER JOIN kategori_buku ON kategori_buku_relasi.id_kategori = kategori_buku.id_kategori) INNER JOIN genre_buku_relasi ON buku.id_buku=genre_buku_relasi.id_buku) INNER JOIN genre_buku ON genre_buku.id_genre=genre_buku_relasi.id_genre) $kondisi");
			while ($d = mysqli_fetch_array($data)) {
				$hasil[] = $d;
			}
			return $hasil;
		}

		// Menampilkan Kategori Buku
		function tampilKategori() {
			$data = mysqli_query($this->koneksi,"SELECT * FROM kategori_buku");
			while ($d = mysqli_fetch_array($data)) {
				$hasil[] = $d;
			}
			return $hasil;
		}

		// Menampilkan Genre Buku
		function tampilGenre() {
			$data = mysqli_query($this->koneksi,"SELECT * FROM genre_buku");
			while ($d = mysqli_fetch_array($data)) {
				$hasil[] = $d;
			}
			return $hasil;
		}

		// Menampilkan rata-rata rating
		function rataRating($id) {
			$data = mysqli_query($this->koneksi,"SELECT id_buku,ROUND(AVG(rating),1) AS rataRating, COUNT(id_user) AS jumlahUser FROM ulasan WHERE id_buku='$id' GROUP BY id_buku");
			while ($d = mysqli_fetch_array($data)) {
				$hasil[] = $d;
			}
			return $hasil;
		}

		//  Tampil semua ulasan berdasarkan id_buku
		function tampilRating($kondisi){
			$data = mysqli_query($this->koneksi,"SELECT * FROM ulasan $kondisi");
			while ($d = mysqli_fetch_array($data)) {
				$hasil[] = $d;
			}
			return $hasil;
		}

		// Tampil Daftar Peminjaman
		function tampilDaftarPeminjaman($select,$kondisi) {
			$data = mysqli_query($this->koneksi,"SELECT $select FROM peminjaman INNER JOIN buku ON peminjaman.id_buku=buku.id_buku $kondisi");
			while ($d = mysqli_fetch_assoc($data)) {
				$hasil[] = $d;
			}
			return $hasil;
		}

		// Tampil Daftar Pembelian
		function tampilDaftarPembelian($select,$kondisi) {
			$data = mysqli_query($this->koneksi,"SELECT $select FROM pembelian INNER JOIN buku ON pembelian.id_buku=buku.id_buku $kondisi");
			while ($d = mysqli_fetch_array($data)) {
				$hasil[] = $d;
			}
			return $hasil;
		}

		// Tampil Daftar Transaksi
		function tampilDaftarTransaksi($kondisi) {
			$data = mysqli_query($this->koneksi,"SELECT 
			transaksi.id_transaksi,transaksi.id_peminjam,transaksi.id_petugas,transaksi.id_peminjaman,transaksi.pelanggaran,transaksi.total_biaya,transaksi.status AS statusTransaksi,transaksi.tgl_transaksi,
			u1.username AS uPeminjam, u1.nama_lengkap,
			u2.username AS uPetugas,
			peminjaman.id_peminjaman,peminjaman.tgl_pinjam,peminjaman.tgl_kembali,peminjaman.status AS statusPeminjaman, peminjaman.kode,
			kategori_buku_relasi.*,
			kategori_buku.*,
			buku.id_buku,buku.judul,buku.harga
			FROM ((((((transaksi INNER JOIN user AS u1 ON transaksi.id_peminjam=u1.id_user) INNER JOIN user AS u2 ON transaksi.id_petugas=u2.id_user) INNER JOIN peminjaman ON transaksi.id_peminjaman=peminjaman.id_peminjaman) INNER JOIN kategori_buku_relasi ON peminjaman.id_buku = kategori_buku_relasi.id_buku) INNER JOIN buku ON peminjaman.id_buku=buku.id_buku) INNER JOIN kategori_buku ON kategori_buku_relasi.id_kategori = kategori_buku.id_kategori) $kondisi");
			while ($d = mysqli_fetch_array($data)) {
				$hasil[] = $d;
			}
			return $hasil;
		}
	/*AKHIR GLOBAL FUNCTION*/
}
$globalicClass = new globalic();

/**
 * PEMINJAM
 */
class peminjam extends database{
	/*AWAL PEMINJAM*/
		// Tampil Koleksi Pribadi
		function tampilKoleksi($kondisi){
			$data = mysqli_query($this->koneksi,"SELECT	koleksipribadi.*,buku.* FROM ((((koleksipribadi INNER JOIN buku ON koleksipribadi.id_buku=buku.id_buku) INNER JOIN user ON koleksipribadi.id_user=user.id_user) INNER JOIN kategori_buku_relasi ON kategori_buku_relasi.id_buku=buku.id_buku) INNER JOIN kategori_buku ON kategori_buku.id_kategori=kategori_buku_relasi.id_kategori) $kondisi");
			while ($d = mysqli_fetch_array($data)) {
				$hasil[] = $d;
			}
			return $hasil;
		}

		// Proses Pinjam Buku Fisik
		function prosesPinjamBuku($idUser,$idBuku,$kode,$estimasi){
			mysqli_query($this->koneksi,"INSERT INTO peminjaman VALUES(NULL,'$idUser','0','$idBuku','','','1','','$kode','$estimasi')");
		}

		// Proses Beli Buku
		function prosesBeliBuku($idUser,$idBuku,$jumlah,$totalBiaya,$status,$metodePembayaran){
			mysqli_query($this->koneksi,"INSERT INTO pembelian VALUES(NULL,'$idUser','0','$idBuku','$jumlah','$totalBiaya','$status','$metodePembayaran','','')");
		}

		// Proses Ubah Beli Buku
		function prosesUbahBeliBuku($id,$status) {
			mysqli_query($this->koneksi,"UPDATE pembelian SET status='$status' WHERE id_pembelian='$id'");
		}

		// Proses Input Ulasan
		function tambahUlasan($id,$idBuku,$ulasan,$rating){
			mysqli_query($this->koneksi,"INSERT INTO ulasan VALUES(NULL,'$id','$idBuku','$ulasan','$rating')");
		}

		// Tampil Ngedit Ulasan
		function tampilEditUlasan($kondisi){
			$data = mysqli_query($this->koneksi,"SELECT * FROM ulasan $kondisi");
			while($d = mysqli_fetch_array($data)){
				$hasil[] = $d;
			}
			return $hasil;
		}

		function prosesEditUlasan($kondisi){
			mysqli_query($this->koneksi,"UPDATE ulasan SET $kondisi");
		}

		function hapusUlasan($idUser,$idBuku){
			mysqli_query($this->koneksi,"DELETE FROM ulasan WHERE id_user='$idUser' AND id_buku='$idBuku'");
		}

	/*AKHIR PEMINJAM*/
}
$peminjamClass = new peminjam();

/**
 * PETUGAS
 */
class petugas extends database{
	/*AWAL PETUGAS*/
		// Proses Mengubah Status Peminjaman
		function prosesUbahPeminjaman($id,$status,$more){
			mysqli_query($this->koneksi,"UPDATE peminjaman SET status='$status'$more WHERE id_peminjaman='$id'");
		}

		// Proses Mengubah Status Pembelian
		function prosesUbahPembelian($id,$status,$more){
			mysqli_query($this->koneksi,"UPDATE pembelian SET status='$status'$more WHERE id_pembelian='$id'");
		}

		// Proses Input ke table "transaksi"
		function tambahTransaksi($idPeminjam,$idPetugas,$idPeminjaman,$pelanggaran,$totalBiaya) {
			mysqli_query($this->koneksi,"INSERT INTO transaksi VALUES(NULL,'$idPeminjam','$idPetugas','$idPeminjaman','$pelanggaran','$totalBiaya','1',NOW())");
		}

		// Proses Edit Status Transaksi
		function prosesUbahTransaksi($id,$status){
			mysqli_query($this->koneksi,"UPDATE transaksi SET status='$status' WHERE id_transaksi='$id'");
		}

		// Total Layanan Peminjaman
		function totalLayananPeminjaman($id){
			$data = mysqli_query($this->koneksi,"SELECT COUNT(*) AS jmlh FROM peminjaman WHERE id_petugas='$id'");
			while($d = mysqli_fetch_array($data)){
				$hasil[] = $d;
			}
			return $hasil;
		}

		// Total Layanan Pembelian
		function totalLayananPembelian($id){
			$data = mysqli_query($this->koneksi,"SELECT COUNT(*) AS jmlh FROM pembelian WHERE id_petugas='$id';");
			while($d = mysqli_fetch_array($data)){
				$hasil[] = $d;
			}
			return $hasil;
		}

		// Total Layanan Transaksi
		function totalLayananTransaksi($id){
			$data = mysqli_query($this->koneksi,"SELECT COUNT(*) AS jmlh FROM transaksi WHERE id_petugas='$id';");
			while($d = mysqli_fetch_array($data)){
				$hasil[] = $d;
			}
			return $hasil;
		}
	/*AKHIR PETUGAS*/
}
$petugasClass = new petugas();

/**
 * ADMINISTRATOR
 */
class admin extends database{
	/*AWAL ADMIN*/
		// Total Pengguna Peminjam
		function totalPengguna() {
			$data = mysqli_query($this->koneksi,"SELECT COUNT(id_user) AS jmlh FROM user WHERE role=3");
			while($d = mysqli_fetch_array($data)){
				$hasil[] = $d;
			}
			return $hasil;
		}

		// Total Petugas Peminjam
		function totalPetugas() {
			$data = mysqli_query($this->koneksi,"SELECT COUNT(id_user) AS jmlh FROM user WHERE role=2");
			while($d = mysqli_fetch_array($data)){
				$hasil[] = $d;
			}

			return $hasil;
		}
		
		// Tampil semua akun peminjam
		function tampilAkunPeminjam(){
			$data = mysqli_query($this->koneksi,"SELECT * FROM user WHERE role=3");
			while($d = mysqli_fetch_array($data)){
				$hasil[] = $d;
			}

			return $hasil;
		}

		/*BUKU*/
			/*Edit Buku*/
					// Proses Edit Buku (All)
			function prosesEditBuku($id,$judul,$penulis,$penerbit,$tahunTerbit,$deskripsi,$sampulBuku,$fileBuku,$halaman,$stok,$harga,$kategori){
				mysqli_query($this->koneksi,"UPDATE buku SET judul='$judul',penulis='$penulis',penerbit='$penerbit',tahun_terbit='$tahunTerbit',deskripsi='$deskripsi',sampul_buku='$sampulBuku',file_buku='$fileBuku',halaman='$halaman',harga='$harga',stok='$stok' WHERE id_buku='$id'");
				mysqli_query($this->koneksi,"UPDATE kategori_buku_relasi SET id_kategori='$kategori' WHERE id_buku='$id'");
			}

					// Proses Edit Buku (Ga dua"nya)
			function prosesEditBukuNoTwo($id,$judul,$penulis,$penerbit,$tahunTerbit,$deskripsi,$halaman,$stok,$harga,$kategori){
				mysqli_query($this->koneksi,"UPDATE buku SET judul='$judul',penulis='$penulis',penerbit='$penerbit',tahun_terbit='$tahunTerbit',deskripsi='$deskripsi',halaman='$halaman',harga='$harga',stok='$stok' WHERE id_buku='$id'");
				mysqli_query($this->koneksi,"UPDATE kategori_buku_relasi SET id_kategori='$kategori' WHERE id_buku='$id'");
			}

					// Proses Edit Buku (Sampul)
			function prosesEditBukuSampul($id,$judul,$penulis,$penerbit,$tahunTerbit,$deskripsi,$sampulBuku,$halaman,$stok,$harga,$kategori){
				mysqli_query($this->koneksi,"UPDATE buku SET judul='$judul',penulis='$penulis',penerbit='$penerbit',tahun_terbit='$tahunTerbit',deskripsi='$deskripsi',sampul_buku='$sampulBuku',halaman='$halaman',harga='$harga',stok='$stok' WHERE id_buku='$id'");
				mysqli_query($this->koneksi,"UPDATE kategori_buku_relasi SET id_kategori='$kategori' WHERE id_buku='$id'");
			}

					// Proses Edit Buku (File Buku)
			function prosesEditBukuFileBuku($id,$judul,$penulis,$penerbit,$tahunTerbit,$deskripsi,$fileBuku,$halaman,$stok,$harga,$kategori){
				mysqli_query($this->koneksi,"UPDATE buku SET judul='$judul',penulis='$penulis',penerbit='$penerbit',tahun_terbit='$tahunTerbit',deskripsi='$deskripsi',file_buku='$fileBuku',halaman='$halaman',harga='$harga',stok='$stok' WHERE id_buku='$id'");
				mysqli_query($this->koneksi,"UPDATE kategori_buku_relasi SET id_kategori='$kategori' WHERE id_buku='$id'");
			}
			// Tampil Edit Buku
			function tampilEditBuku($id){
				$data = mysqli_query($this->koneksi,"SELECT	buku.*,genre_buku_relasi.*,kategori_buku.*,kategori_buku_relasi.*,genre_buku.nama_genre,REPLACE(GROUP_CONCAT(genre_buku.id_genre),',',', ') AS genreS FROM ((((buku INNER JOIN kategori_buku_relasi ON buku.id_buku = kategori_buku_relasi.id_buku) INNER JOIN kategori_buku ON kategori_buku_relasi.id_kategori = kategori_buku.id_kategori) INNER JOIN genre_buku_relasi ON buku.id_buku=genre_buku_relasi.id_buku) INNER JOIN genre_buku ON genre_buku.id_genre=genre_buku_relasi.id_genre) WHERE genre_buku_relasi.id_buku=$id");
				while($d = mysqli_fetch_array($data)){
					$hasil[] = $d;
				}

				return $hasil;
			}

			/*TAMBAH BUKU*/
					// Tambah Buku (File)
			function tambahBukuEBook($judul,$penulis,$penerbit,$tahunTerbit,$deskripsi,$sampulBuku,$fileBuku,$kategori,$halaman,$stok,$harga,$format){
				mysqli_query($this->koneksi,"INSERT INTO buku VALUES(NULL,'$judul','$penulis','$penerbit','$tahunTerbit','$deskripsi','$sampulBuku','$fileBuku','$halaman','$harga','$format','$stok')");
				$data = mysqli_query($this->koneksi,"SELECT MAX(id_buku) AS id FROM buku");
				$d = mysqli_fetch_array($data);
				$key = $d['id'];
				mysqli_query($this->koneksi,"INSERT INTO kategori_buku_relasi VALUES(NULL,'$key','$kategori')");
			}

					// Tambah Buku (No File)
			function tambahBukuFisik($judul,$penulis,$penerbit,$tahunTerbit,$deskripsi,$sampulBuku,$kategori,$halaman,$stok,$harga,$format){
				mysqli_query($this->koneksi,"INSERT INTO buku VALUES(NULL,'$judul','$penulis','$penerbit','$tahunTerbit','$deskripsi','$sampulBuku','','$halaman','$harga','$format','$stok')");
				$data = mysqli_query($this->koneksi,"SELECT MAX(id_buku) AS id FROM buku");
				$d = mysqli_fetch_array($data);
				$key = $d['id'];
				mysqli_query($this->koneksi,"INSERT INTO kategori_buku_relasi VALUES(NULL,'$key','$kategori')");
			}

			/*HAPUS BUKU*/

					// Hapus Buku
			function hapusBuku($id){
				mysqli_query($this->koneksi,"DELETE FROM buku WHERE id_buku='$id'");
				mysqli_query($this->koneksi,"DELETE FROM kategori_buku_relasi WHERE id_buku='$id'");
				mysqli_query($this->koneksi,"DELETE FROM genre_buku_relasi WHERE id_buku='$id'");
				mysqli_query($this->koneksi,"DELETE FROM koleksipribadi WHERE id_buku='$id'");
			}
		
		// Menghitung Buku Fisik yang paling banyak dipinjam
			function totalPeminjamanFisik(){
				$data = mysqli_query($this->koneksi,"SELECT b.id_buku,b.judul,b.format,kb.nama_kategori,COUNT(p.id_peminjaman) AS total_peminjaman FROM (((buku AS b LEFT JOIN peminjaman AS p ON p.id_buku = b.id_buku) RIGHT JOIN kategori_buku_relasi AS kbr ON b.id_buku = kbr.id_buku) INNER JOIN kategori_buku AS kb ON kbr.id_kategori=kb.id_kategori) WHERE b.format='Fisik' GROUP BY b.id_buku ORDER BY total_peminjaman DESC");
				while ($d = mysqli_fetch_array($data)) {
					$hasil[] = $d;
				}
				return $hasil;
			}
		
		// Menghitung Buku fisik yang paling banyak dibeli
			function totalPembelianFisik(){
				$data = mysqli_query($this->koneksi,"SELECT b.id_buku, b.judul, COUNT(p.id_pembelian) AS total_pembelian FROM ((buku AS b LEFT JOIN pembelian AS p ON p.id_buku = b.id_buku) RIGHT JOIN kategori_buku_relasi AS kbr ON b.id_buku = kbr.id_buku) WHERE kbr.id_kategori = 1 GROUP BY b.id_buku ORDER BY total_pembelian DESC");
				while ($d = mysqli_fetch_array($data)) {
					$hasil[] = $d;
				}
				return $hasil;
			}
		
		// Menghitung Buku E-Book yang paling banyak dibeli
			function totalPembelianEbook(){
				$data = mysqli_query($this->koneksi,"SELECT b.id_buku, b.judul, COUNT(p.id_pembelian) AS total_pembelian FROM ((buku AS b LEFT JOIN pembelian AS p ON p.id_buku = b.id_buku) RIGHT JOIN kategori_buku_relasi AS kbr ON b.id_buku = kbr.id_buku) WHERE kbr.id_kategori = 2 GROUP BY b.id_buku ORDER BY total_pembelian DESC");
				while ($d = mysqli_fetch_array($data)) {
					$hasil[] = $d;
				}
				return $hasil;
			}

		/*PEGAWAI*/
			// Tambah akun pegawai
			function tambahUser($role,$username,$pw,$email,$namaLengkap,$alamat){
				mysqli_query($this->koneksi,"INSERT INTO user VALUES(NULL,'$role','$username','$pw','$email','$namaLengkap','$alamat')");
			}
			// Hapus akun pegawai
			function hapusUser($id){
				mysqli_query($this->koneksi,"DELETE FROM user WHERE id_user='$id'");
			}
			// Edit akun pegawai
			function editUser($id,$role,$username,$pw,$email,$namaLengkap,$alamat){
				mysqli_query($this->koneksi,"UPDATE user SET role='$role', username='$username', password='$pw', email='$email', nama_lengkap='$namaLengkap', alamat='$alamat' WHERE id_user='$id'");
			}
			// Tampil akun pegawai yang ingin di edit
			function tampilEditUser($id){
				$data = mysqli_query($this->koneksi,"SELECT * FROM user WHERE id_user='$id'");
				while ($d = mysqli_fetch_array($data)) {
					$hasil[] = $d;
				}
				return $hasil;
			}

			// Menampilkan akun hanya role petugas dan tidak menampilkan profile sendiri
			function tampilUser($id) {
				$data = mysqli_query($this->koneksi,"SELECT * FROM user WHERE role < 3 AND id_user != '$id'");
				while ($d = mysqli_fetch_array($data)) {
					$hasil[] = $d;
				}
				return $hasil;
			}
	/*AKHIR ADMIN*/
}
$adminClass = new admin();

/**
 * MAIN CLASS FOR LIBRARY
 */
class lib {
	// INDEX
		function navbarIndex($hide){
			global $globalicClass;
			$tampilKategori = $globalicClass->tampilKategori();
			?>
			<!-- AWAL NAVBAR -->
			  <nav class='navbar navbar-expand-lg bd-navbar fixed-top bg-danger'>
			    <div class='container-fluid'>
			      <!-- COLLAPSE BUTTON NAVBAR -->
			      <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
			        <span class='navbar-toggler-icon'></span>
			      </button>
			      <!-- NAVBAR COLLAPSE -->
			      <div class='collapse navbar-collapse px-3' id='navbarSupportedContent'>
			        <div class='col'>
			          <a class='navbar-brand link-light text-decoration-none' href='/perpus/'>Merdeka Membaca</a>
			        </div>
			        <!-- SEARCH FORM DENGAN FILTER FORMAT -->
			        <div class='col' <?= $hide?>>
			          <form class='d-flex my-auto' role='search'>
			            <div class='input-group me-2'>
			              <i class='bi bi-filter fs-4 btn btn-outline-warning rounded-start' data-bs-toggle='dropdown' type='button' aria-expanded='false'></i>
			              <ul class='dropdown-menu dropdown-menu-start w-100'>
			                <li><span class='dropdown-item-text'>Format</span></li>
			                <li>
			                  <hr class='dropdown-divider'>
			                </li>
			                <li><a class='dropdown-item' href='./'>Semua</a></li>
			                <li><a class='dropdown-item' href='?<?= isset($_GET['kategori']) ? 'kategori=' . $_GET['kategori'] . '&' : '' ?>format=Fisik'>Fisik</a></li>
			                <li><a class='dropdown-item' href='?<?= isset($_GET['kategori']) ? 'kategori=' . $_GET['kategori'] . '&' : '' ?>format=E-Book'>E-Book</a></li>
			              </ul>
			              <input class='form-control w-auto' type='search' name='judul' placeholder='Search' autocomplete='off' value='<?= (isset($_GET['judul'])) ? $_GET['judul'] : '';?>'>
			              <a href='./' class='btn btn-secondary rounded-end' aria-expanded='false' <?= $value= (isset($_GET['judul'])) ? '' : 'hidden' ?>><i class='bi bi-x fs-4'></i></a>
			            </div>
			          </form>
			        </div>
			        
			        <!-- KATEGORI FILTER & DAFTAR / MASUK DROPDOWN LINK -->
			        <div class='col'>
			          <ul class='navbar-nav justify-content-end align-items-start mb-2 mb-lg-0 '>
			            <!-- KATEGORI FILTER -->
			            <li class='nav-item dropdown' <?= $hide?>>
			              <button class='nav-link text-decoration-none dropdown-toggle mt-1 link-light' href='#' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
			                Kategori
			              </button>
			              <ul class='dropdown-menu dropdown-menu-end'>
			              	<li><a href="./" class="dropdown-item link-danger" <?= (isset($_GET['kategori'])) ? '' : 'hidden' ?>>Reset</a></li>
			                <?php foreach ($tampilKategori as $d) { ?>
			                  <li><a class='dropdown-item' href='?<?= isset($_GET['format']) ? 'format=' . $_GET['format'] . '&' : '' ?>kategori=<?= $d['nama_kategori'] ?>'><?= $d['nama_kategori'] ?></a></li>
			                <?php } ?>
			              </ul>
			            </li>
			            <!-- DAFTAR / MASUK DROPDOWN LINK -->
			            <li class='nav-item dropdown'>
			              <button class='nav-link text-decoration-none dropdown-toggle mt-1 link-light' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
			                Daftar / Masuk
			              </button>
			              <ul class='dropdown-menu dropdown-menu-end'>
			                <li><a class='dropdown-item' href='/perpus/form/daftar.php'>Daftar</a></li>
			                <li><a class='dropdown-item' href='/perpus/form/login.php'>Masuk</a></li>
			              </ul>
			            </li>
			            <!-- TOGGLE MODE -->
			            <li class='nav-item dropdown bd-mode-toggle mt-1'>
			              <button class='nav-link btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center link-light'
			              id='bd-theme'
			              type='button'
			              aria-expanded='false'
			              data-bs-toggle='dropdown'
			              aria-label='Toggle theme (auto)'>
			              <svg class='bi my-1 theme-icon-active' width='1em' height='1em'><use href='#circle-half'></use></svg>
			              <span class='visually-hidden' id='bd-theme-text'>Toggle theme</span>
			            </button>
			            <ul class='dropdown-menu dropdown-menu-end shadow' aria-labelledby='bd-theme-text'>
			              <li>
			                <button type='button' class='dropdown-item d-flex align-items-center' data-bs-theme-value='light' aria-pressed='false'>
			                  <svg class='bi me-2 opacity-50 theme-icon' width='1em' height='1em'><use href='#sun-fill'></use></svg>
			                  Light
			                  <svg class='bi ms-auto d-none' width='1em' height='1em'><use href='#check2'></use></svg>
			                </button>
			              </li>
			              <li>
			                <button type='button' class='dropdown-item d-flex align-items-center' data-bs-theme-value='dark' aria-pressed='false'>
			                  <svg class='bi me-2 opacity-50 theme-icon' width='1em' height='1em'><use href='#moon-stars-fill'></use></svg>
			                  Dark
			                  <svg class='bi ms-auto d-none' width='1em' height='1em'><use href='#check2'></use></svg>
			                </button>
			              </li>
			              <li>
			                <button type='button' class='dropdown-item d-flex align-items-center active' data-bs-theme-value='auto' aria-pressed='true'>
			                  <svg class='bi me-2 opacity-50 theme-icon' width='1em' height='1em'><use href='#circle-half'></use></svg>
			                  Auto
			                  <svg class='bi ms-auto d-none' width='1em' height='1em'><use href='#check2'></use></svg>
			                </button>
			              </li>
			            </ul>
			          </li>
			        </ul>
			      </div>
			    </div>
			  </div>
			</nav>
			<!-- AKHIR NAVBAR -->
			<?php
		}
	// Peminjam
		function navbarPeminjam($hideKategori,$hideSearch,$hideFormat,$username) {
			
			global $globalicClass;
			$tampilKategori = $globalicClass->tampilKategori();
			?>
			<nav class='navbar navbar-expand-lg bd-navbar fixed-top bg-danger'>
			    <div class='container-fluid'>
			      <!-- COLLAPSE BUTTON NAVBAR -->
			      <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
			        <span class='navbar-toggler-icon'></span>
			      </button>
			      <!-- NAVBAR COLLAPSE -->
			      <div class='collapse navbar-collapse px-3' id='navbarSupportedContent'>
			        <div class='col'>
			          <a class='navbar-brand link-light' href='/perpus/peminjam'>Merdeka Membaca</a>
			        </div>
			        <!-- SEARCH FORM DENGAN FILTER FORMAT -->
			        <div class='col' <?= $hideSearch?>>
			          <form class='d-flex my-auto' role='search'>
			            <div class='input-group me-2'>
			              <i class='bi bi-filter fs-4 btn btn-outline-warning rounded-start' data-bs-toggle='dropdown' type='button' aria-expanded='false' <?= $hideFormat?>></i>
			              <ul class='dropdown-menu dropdown-menu-start w-100'>
			                <li><span class='dropdown-item-text'>Format</span></li>
			                <li>
			                  <hr class='dropdown-divider'>
			                </li>
			                <li><a class='dropdown-item' href='./'>Semua</a></li>
			                <li><a class='dropdown-item' href='?<?= isset($_GET['kategori']) ? 'kategori=' . $_GET['kategori'] . '&' : '' ?>format=Fisik'>Fisik</a></li>
			                <li><a class='dropdown-item' href='?<?= isset($_GET['kategori']) ? 'kategori=' . $_GET['kategori'] . '&' : '' ?>format=E-Book'>E-Book</a></li>
			              </ul>
			              <input class='form-control w-auto' type='search' name='judul' placeholder='Search' autocomplete='off' value='<?= (isset($_GET['judul'])) ? $_GET['judul'] : '';?>'>
			              <a href='./' class='btn btn-secondary rounded-end' aria-expanded='false' <?= $value= (isset($_GET['judul'])) ? '' : 'hidden' ?>><i class='bi bi-x fs-4'></i></a>
			            </div>
			          </form>
			        </div>
			        
			        <!-- KATEGORI FILTER & DAFTAR / MASUK DROPDOWN LINK -->
			        <div class='col'>
			          <ul class='navbar-nav justify-content-end align-items-start mb-2 mb-lg-0 '>
			            <!-- KATEGORI FILTER -->
			            <li class='nav-item dropdown' <?= $hideKategori?>>
			              <button class='nav-link text-decoration-none dropdown-toggle mt-1 link-light' href='#' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
			                Kategori
			              </button>
			              <ul class='dropdown-menu dropdown-menu-end'>
			              	<li><a href="./" class="dropdown-item link-danger" <?= (isset($_GET['kategori'])) ? '' : 'hidden' ?>>Reset</a></li>
			                <?php foreach ($tampilKategori as $d) { ?>
			                  <li><a class='dropdown-item' href='?<?= isset($_GET['format']) ? 'format=' . $_GET['format'] . '&' : '' ?>kategori=<?= $d['nama_kategori'] ?>'><?= $d['nama_kategori'] ?></a></li>
			                <?php } ?>
			              </ul>
			            </li>
			            <!-- PROFILE -->
			            <li class='nav-item dropdown'>
			              <button class='nav-link text-decoration-none dropdown-toggle mt-1 link-light' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
			                <img src='https://github.com/mdo.png' alt='' width='32' height='32' class='rounded-circle me-2' />
			              </button>
			              <ul class='dropdown-menu text-small shadow dropdown-menu-end'>
							<li><div class='dropdown-header fw-bold'><?= $username?></div></li>
							<li><a href='/perpus/peminjam/' class='dropdown-item'>Beranda</a></li>
							<li><a href='/perpus/peminjam/daftar/peminjaman.php' class='dropdown-item'>Daftar Pinjam</a></li>
							<li><a href='/perpus/peminjam/daftar/pembelian.php' class='dropdown-item'>Daftar Pembelian</a></li>
							<li><a href='/perpus/peminjam/daftar/transaksi.php' class='dropdown-item'>Daftar Transaksi</a></li>
							<li><a href='/perpus/peminjam/koleksi/' class='dropdown-item'>Koleksi Anda</a></li>
							<li><hr class='dropdown-divider' /></li>
							<li><a class='dropdown-item' href='/perpus/logout.php'>Keluar</a></li>
						</ul>
			            </li>
			            <!-- TOGGLE MODE -->
			            <li class='nav-item dropdown bd-mode-toggle mt-1'>
			              <button class='nav-link btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center link-light'
			              id='bd-theme'
			              type='button'
			              aria-expanded='false'
			              data-bs-toggle='dropdown'
			              aria-label='Toggle theme (auto)'>
			              <svg class='bi my-1 theme-icon-active' width='1em' height='1em'><use href='#circle-half'></use></svg>
			              <span class='visually-hidden' id='bd-theme-text'>Toggle theme</span>
			            </button>
			            <ul class='dropdown-menu dropdown-menu-end shadow' aria-labelledby='bd-theme-text'>
			              <li>
			                <button type='button' class='dropdown-item d-flex align-items-center' data-bs-theme-value='light' aria-pressed='false'>
			                  <svg class='bi me-2 opacity-50 theme-icon' width='1em' height='1em'><use href='#sun-fill'></use></svg>
			                  Light
			                  <svg class='bi ms-auto d-none' width='1em' height='1em'><use href='#check2'></use></svg>
			                </button>
			              </li>
			              <li>
			                <button type='button' class='dropdown-item d-flex align-items-center' data-bs-theme-value='dark' aria-pressed='false'>
			                  <svg class='bi me-2 opacity-50 theme-icon' width='1em' height='1em'><use href='#moon-stars-fill'></use></svg>
			                  Dark
			                  <svg class='bi ms-auto d-none' width='1em' height='1em'><use href='#check2'></use></svg>
			                </button>
			              </li>
			              <li>
			                <button type='button' class='dropdown-item d-flex align-items-center active' data-bs-theme-value='auto' aria-pressed='true'>
			                  <svg class='bi me-2 opacity-50 theme-icon' width='1em' height='1em'><use href='#circle-half'></use></svg>
			                  Auto
			                  <svg class='bi ms-auto d-none' width='1em' height='1em'><use href='#check2'></use></svg>
			                </button>
			              </li>
			            </ul>
			          </li>
			        </ul>
			      </div>
			    </div>
			  </div>
			</nav>
			<?php
		}
	// Petugas
		function navbarPetugas($username) {
			?>
			<nav class='navbar navbar-expand-lg bd-navbar fixed-top bg-danger'>
			    <div class='container-fluid'>
			      <!-- COLLAPSE BUTTON NAVBAR -->
			      <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
			        <span class='navbar-toggler-icon'></span>
			      </button>
			      <!-- NAVBAR COLLAPSE -->
			      <div class='collapse navbar-collapse px-3' id='navbarSupportedContent'>
			        <div class='col'>
			          <a class='navbar-brand link-light' href='/perpus/petugas'>Merdeka Membaca</a>
			        </div>
			        
			        <!-- PROFILE DROPDOWN -->
			        <div class='col'>
			          <ul class='navbar-nav justify-content-end align-items-start mb-2 mb-lg-0 '>
			            <!-- PROFILE -->
			            <li class='nav-item dropdown'>
			              <button class='nav-link text-decoration-none dropdown-toggle mt-1 link-light' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
			                <img src='https://github.com/mdo.png' alt='' width='32' height='32' class='rounded-circle me-2' />
			              </button>
			              <ul class='dropdown-menu text-small shadow dropdown-menu-end'>
							<li><div class='dropdown-header fw-bold'><?= $username?></div></li>
							<li><a href='/perpus/petugas/' class='dropdown-item'>Beranda</a></li>
							<li><a href='/perpus/petugas/daftar/peminjaman.php' class='dropdown-item'>Daftar Pinjam</a></li>
							<li><a href='/perpus/petugas/daftar/pembelian.php' class='dropdown-item'>Daftar Pembelian</a></li>
							<li><a href='/perpus/petugas/daftar/transaksi.php' class='dropdown-item'>Daftar Transaksi</a></li>
							<li><hr class='dropdown-divider' /></li>
							<li><a class='dropdown-item' href='/perpus/logout.php'>Keluar</a></li>
						</ul>
			            </li>
			            <!-- TOGGLE MODE -->
			            <li class='nav-item dropdown bd-mode-toggle mt-1'>
			              <button class='nav-link btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center link-light'
			              id='bd-theme'
			              type='button'
			              aria-expanded='false'
			              data-bs-toggle='dropdown'
			              aria-label='Toggle theme (auto)'>
			              <svg class='bi my-1 theme-icon-active' width='1em' height='1em'><use href='#circle-half'></use></svg>
			              <span class='visually-hidden' id='bd-theme-text'>Toggle theme</span>
			            </button>
			            <ul class='dropdown-menu dropdown-menu-end shadow' aria-labelledby='bd-theme-text'>
			              <li>
			                <button type='button' class='dropdown-item d-flex align-items-center' data-bs-theme-value='light' aria-pressed='false'>
			                  <svg class='bi me-2 opacity-50 theme-icon' width='1em' height='1em'><use href='#sun-fill'></use></svg>
			                  Light
			                  <svg class='bi ms-auto d-none' width='1em' height='1em'><use href='#check2'></use></svg>
			                </button>
			              </li>
			              <li>
			                <button type='button' class='dropdown-item d-flex align-items-center' data-bs-theme-value='dark' aria-pressed='false'>
			                  <svg class='bi me-2 opacity-50 theme-icon' width='1em' height='1em'><use href='#moon-stars-fill'></use></svg>
			                  Dark
			                  <svg class='bi ms-auto d-none' width='1em' height='1em'><use href='#check2'></use></svg>
			                </button>
			              </li>
			              <li>
			                <button type='button' class='dropdown-item d-flex align-items-center active' data-bs-theme-value='auto' aria-pressed='true'>
			                  <svg class='bi me-2 opacity-50 theme-icon' width='1em' height='1em'><use href='#circle-half'></use></svg>
			                  Auto
			                  <svg class='bi ms-auto d-none' width='1em' height='1em'><use href='#check2'></use></svg>
			                </button>
			              </li>
			            </ul>
			          </li>
			        </ul>
			      </div>
			    </div>
			  </div>
			</nav>
			<?php
		}
	// Admin
	function navbarAdmin($hideKategori,$hideSearch,$hideSearchPegawai,$username){
		global $globalicClass;
		$tampilKategori = $globalicClass->tampilKategori();
		?>
			<nav class='navbar navbar-expand-lg bd-navbar fixed-top bg-danger'>
			    <div class='container-fluid'>
			      <!-- COLLAPSE BUTTON NAVBAR -->
			      <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
			        <span class='navbar-toggler-icon'></span>
			      </button>
			      <!-- NAVBAR COLLAPSE -->
			      <div class='collapse navbar-collapse px-3' id='navbarSupportedContent'>
			        <div class='col'>
			          <a class='navbar-brand link-light' href='/perpus/admin'>Merdeka Membaca</a>
			        </div>
			        <!-- SEARCH FORM DENGAN FILTER FORMAT -->
			        <div class='col' <?= $hideSearch?>>
			          <form class='d-flex my-auto' role='search'>
			            <div class='input-group me-2'>
			              <i class='bi bi-filter fs-4 btn btn-outline-warning rounded-start' data-bs-toggle='dropdown' type='button' aria-expanded='false'></i>
			              <ul class='dropdown-menu dropdown-menu-start w-100'>
			                <li><span class='dropdown-item-text'>Format</span></li>
			                <li>
			                  <hr class='dropdown-divider'>
			                </li>
			                <li><a class='dropdown-item' href='./'>Semua</a></li>
			                <li><a class='dropdown-item' href='?<?= isset($_GET['kategori']) ? 'kategori=' . $_GET['kategori'] . '&' : '' ?>format=Fisik'>Fisik</a></li>
			                <li><a class='dropdown-item' href='?<?= isset($_GET['kategori']) ? 'kategori=' . $_GET['kategori'] . '&' : '' ?>format=E-Book'>E-Book</a></li>
			              </ul>
			              <input class='form-control w-auto' type='search' name='judul' placeholder='Search' autocomplete='off' value='<?= (isset($_GET['judul'])) ? $_GET['judul'] : '';?>'>
			              <a href='./' class='btn btn-secondary rounded-end' aria-expanded='false' <?= $value= (isset($_GET['judul'])) ? '' : 'hidden' ?>><i class='bi bi-x fs-4'></i></a>
			            </div>
			          </form>
			        </div>

			        <!-- SEARCH PEGAWAI -->
			        <div class="col" <?= $hideSearchPegawai?>>
						<form class='d-flex my-auto' role='search'>
							<div class="input-group me-2">
								<input class='form-control w-auto' type='search' name='username' placeholder='Search' autocomplete='off' value='<?= (isset($_GET['username'])) ? $_GET['username'] : '';?>'>
								<a href='<?= $_SERVER['PHP_SELF']?>' class='btn btn-secondary rounded-end' aria-expanded='false' <?= (isset($_GET['username'])) ? '' : 'hidden'; ?>><i class='bi bi-x fs-4'></i></a>
							</div>
						</form>
					</div>
			        
			        <!-- KATEGORI FILTER & DAFTAR / MASUK DROPDOWN LINK -->
			        <div class='col'>
			          <ul class='navbar-nav justify-content-end align-items-start mb-2 mb-lg-0 '>
			            <!-- KATEGORI FILTER -->
			            <li class='nav-item dropdown' <?= $hideKategori?>>
			              <button class='nav-link text-decoration-none dropdown-toggle mt-1 link-light' href='#' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
			                Kategori
			              </button>
			              <ul class='dropdown-menu dropdown-menu-end'>
			              	<li><a href="./" class="dropdown-item link-danger" <?= (isset($_GET['kategori'])) ? '' : 'hidden' ?>>Reset</a></li>
			                <?php foreach ($tampilKategori as $d) { ?>
			                  <li><a class='dropdown-item' href='?<?= isset($_GET['format']) ? 'format=' . $_GET['format'] . '&' : '' ?>kategori=<?= $d['nama_kategori'] ?>'><?= $d['nama_kategori'] ?></a></li>
			                <?php } ?>
			              </ul>
			            </li>
			            <!-- PROFILE -->
			            <li class='nav-item dropdown'>
			              <button class='nav-link text-decoration-none dropdown-toggle mt-1 link-light' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
			                <img src='https://github.com/mdo.png' alt='' width='32' height='32' class='rounded-circle me-2' />
			              </button>
			              <ul class='dropdown-menu text-small shadow dropdown-menu-end'>
							<li><div class='dropdown-header fw-bold'><?= $username?></div></li>
							<li><a href='/perpus/admin/' class='dropdown-item'>Beranda</a></li>
							<li><a href='/perpus/admin/book/' class='dropdown-item'>Daftar Buku</a></li>
							<li><a href='/perpus/admin/pegawai/' class='dropdown-item'>Daftar Pegawai</a></li>
							<li><hr class='dropdown-divider' /></li>
							<li><a class='dropdown-item' href='/perpus/logout.php'>Keluar</a></li>
						</ul>
			            </li>
			            <!-- TOGGLE MODE -->
			            <li class='nav-item dropdown bd-mode-toggle mt-1'>
			              <button class='nav-link btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center link-light'
			              id='bd-theme'
			              type='button'
			              aria-expanded='false'
			              data-bs-toggle='dropdown'
			              aria-label='Toggle theme (auto)'>
			              <svg class='bi my-1 theme-icon-active' width='1em' height='1em'><use href='#circle-half'></use></svg>
			              <span class='visually-hidden' id='bd-theme-text'>Toggle theme</span>
			            </button>
			            <ul class='dropdown-menu dropdown-menu-end shadow' aria-labelledby='bd-theme-text'>
			              <li>
			                <button type='button' class='dropdown-item d-flex align-items-center' data-bs-theme-value='light' aria-pressed='false'>
			                  <svg class='bi me-2 opacity-50 theme-icon' width='1em' height='1em'><use href='#sun-fill'></use></svg>
			                  Light
			                  <svg class='bi ms-auto d-none' width='1em' height='1em'><use href='#check2'></use></svg>
			                </button>
			              </li>
			              <li>
			                <button type='button' class='dropdown-item d-flex align-items-center' data-bs-theme-value='dark' aria-pressed='false'>
			                  <svg class='bi me-2 opacity-50 theme-icon' width='1em' height='1em'><use href='#moon-stars-fill'></use></svg>
			                  Dark
			                  <svg class='bi ms-auto d-none' width='1em' height='1em'><use href='#check2'></use></svg>
			                </button>
			              </li>
			              <li>
			                <button type='button' class='dropdown-item d-flex align-items-center active' data-bs-theme-value='auto' aria-pressed='true'>
			                  <svg class='bi me-2 opacity-50 theme-icon' width='1em' height='1em'><use href='#circle-half'></use></svg>
			                  Auto
			                  <svg class='bi ms-auto d-none' width='1em' height='1em'><use href='#check2'></use></svg>
			                </button>
			              </li>
			            </ul>
			          </li>
			        </ul>
			      </div>
			    </div>
			  </div>
			</nav>
			<?php
	}

	function toggleMode(){
		echo '<svg xmlns="http://www.w3.org/2000/svg" class="d-none">
			      <symbol id="check2" viewBox="0 0 16 16">
			        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
			      </symbol>
			      <symbol id="circle-half" viewBox="0 0 16 16">
			        <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
			      </symbol>
			      <symbol id="moon-stars-fill" viewBox="0 0 16 16">
			        <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
			        <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
			      </symbol>
			      <symbol id="sun-fill" viewBox="0 0 16 16">
			        <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
			      </symbol>
			    </svg>';
	}
}
$lib = new lib();
$mode = $lib->toggleMode();
?>

<?php
// Fungsi untuk memeriksa izin akses sesuai role Peminjam
function peminjam() {
    if(isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
        $role = $_SESSION['role'];
        if($role == 'Peminjam') {
            return true; // Pengguna Peminjam memiliki izin akses
        }
    }
    return false; // Pengguna bukan Peminjam atau belum login
}

// Fungsi untuk memeriksa izin akses sesuai role petugas
function petugas() {
    if(isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
        $role = $_SESSION['role'];
        if($role == 'Petugas') {
            return true; // Pengguna petugas memiliki izin akses
        }
    }
    return false; // Pengguna bukan petugas atau belum login
}

// Fungsi untuk memeriksa izin akses sesuai role administrator
function admin() {
    if(isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
        $role = $_SESSION['role'];
        if($role == 'Administrator') {
            return true; // Pengguna administrator memiliki izin akses
        }
    }
    return false; // Pengguna bukan administrator atau belum login
}
?>