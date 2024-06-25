-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2024 at 05:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpus`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `penulis` varchar(255) NOT NULL,
  `penerbit` varchar(255) NOT NULL,
  `tahun_terbit` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `sampul_buku` varchar(255) NOT NULL,
  `file_buku` varchar(255) NOT NULL,
  `halaman` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `format` enum('Fisik','E-Book') NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `judul`, `penulis`, `penerbit`, `tahun_terbit`, `deskripsi`, `sampul_buku`, `file_buku`, `halaman`, `harga`, `format`, `stok`) VALUES
(1, 'Romansa Hari Libur', 'Charles Dickens', 'Diva Pers', 2022, 'Merasa bahwa akan seumur hidup menjejak bumi selaku bocah,—maksudku lelaki dewasa,—yang tercemar harga dirinya, atau bahwa aku harus membersihkan nama baikku, aku menuntut untuk diadili di Pengadilan Militer. Kolonel mengakui hakku untuk diadili. Beberapa kesulitan muncul dalam usaha pembentukan pengadilan itu, terutama penolakan bibi Kaisar Prancis untuk mengizinkan sang kaisar keluar. Kaisarlah yang menjadi Hakim Ketua. Belum juga kami menunjuk penggantinya, dia sudah melarikan diri lewat tembok belakang, dan berdiri di antara kami, sebagai penguasa yang merdeka.\r\nEmpat anak yang belum genap berusia sepuluh tahun, menulis cerita fiksi masing-masing dengan menampilkan tokoh-tokoh orang dewasa. Tidak hanya kisah percintaan, tapi ada juga petualangan seru dan konflik orang dewasa. Itulah yang mereka lakukan untuk mengisi waktu luang di hari libur.\r\n\r\nRomansa Pembuka dari Tulisan yang Terhormat William Tinkling (Usia 8 Tahun)\r\n\r\nBagian pembuka romansa ini, kautahu, bukanlah khayalan yang menyembul dari isi kepala siapa pun. Kisah ini benar-benar terjadi. Kau harus memercayai bagian pembuka kisah ini lebih dari kisah yang tercantum sesudahnya, kalau tidak, kau tidak akan mengerti rentetan ceritanya. Kau harus memercayai semua kisah ini; namun sekali lagi aku minta bagian inilah yang kau paling percayai. Akulah penyunting kisah-kisah ini. Bob Redforth (dia sepupuku, dan lihatlah, dia sengaja mengguncang-guncang meja) ingin menjadi penyunting kisah-kisah ini: tetapi kupikir dia tidak boleh menjadi penyunting karena dia tidak akan mampu. Dia tidak punya bayangan apa pun tentang bagaimana menjadi penyunting.', '1471761071_romansa hari libur (komik-novelg-grafis).jpg', '', 84, 50000, 'Fisik', 10),
(2, 'The Paper Magician (The Paper Magician #1)', 'Charlie N. Holmberg', 'Elex Media Komputindo', 2019, 'Novel The Paper Magician merupakan novel fiksi fantasi yang begitu unik, dimana penyihirnya memiliki kekuatan untuk mengendalikan objek seperti kertas, gelas, besi dan lain sebagainya. Tokoh utama, -Ceony, memiliki kemampuan membuat burung dari kerta dan dapat bergerak, seakan burung tersebut hidup.\r\nBuku ini merupakan buku pertama dari sekuel magician. Buku ini menggunakan bahasa yang sederhana, sehingga dapat dibaca untuk anak-anak sekalipun, buku ini begitu mudah sekali dinikmati jalan ceritanya. Dunia dan alur cerita dalam buku ini juga dibangun dengan sangat kokoh, saling menopang satu sama lain. Dan yang paling utama adalah bagaimana pesulap pesulap ini menggunakan media seperti kertas, karet, plastik, logam, hingga kaca sekalipun, menerbangkannya dan lain sebagainya.\r\n\r\nSinopsis Buku\r\nCeony Twill tiba di pondok Penyihir Emery Thane dengan perasaan kecewa. Meskipun lulus dengan peringkat tinggi dari Tagis Praff School, masa magangnya harus dihabiskan sebagai Pengendali Kertas, bukan sebagai Pengendali Besi, sebagaimana mimpinya selama ini. Dan sekali dia terikat pada kertas, maka kertas akan menjadi satu-satunya sihir yang Ceony kuasai selamanya.\r\nTernyata, menjadi Pengendali Kertas ternyata tidak seburuk yang dia bayangkan. Begitu banyak keajaiban yang dapat tercipta dengan media kertas. Ceony pun mulai menikmati masa-masanya sebagai Pengendali Kertas-magang. Sayangnya, kesenangan Ceony terusik ketika Lira, seorang Penyihir Pembelot, datang ke pondok Emery Thane, lalu mengambil jantung Emery.\r\nCeony panik. Namun dengan bekal pembelajaran tentang lipatan kertas, dia berhasil membuat jantung kertas untuk Emery. Jantung itu hanya akan bertahan dua hari. Ceony harus tetap menemukan jantung Emery, atau sang penyihir itu akan mati.', '579321364_9786230010071_Paper_Magician.jpg', '', 236, 70000, 'Fisik', 3),
(3, 'Seberapa Tangguh Mentalmu?', 'Denanda Pratiwi Putry', 'Anak Hebat Indonesia', 2024, 'Seberapa Tangguh Mentalmu? Strategi Kekuatan Mental dalam Kehidupan Sehari-hari\r\n\r\nBuku ini hanya sebuah langkah awal kamu untuk hidup lebih baik dari bersikap mental lebih positif setiap harinya. Kamu bisa benar-benar melakukan banyak perubahan besar dalam hidupmu saat kamu fokus dengan konsep dari attitude is everything, kamu mengimplementasikan setiap gagasan yang ditulis di buku ini, dan kamu sedang berproses memperbaiki hidupmu dengan serius.\r\n\r\nSekarang ini saatnya kamu mengambil kontrol sikap mentalmu. Ini saatnya kamu menciptakan keajaiban dalam hidupmu. Melangkahlah. Percaya dengan dirimu sendiri. Be the BEST of YOU.\r\nSebelum kamu membaca banyak hal di buku ini dan kemudian ingin melakukan banyak hal, kamu perlu mengawali semua rencana-rencanamu dengan mengetahui tujuanmu melakukan suatu hal.\r\n\r\n“There is one quality that one must possess to win,\r\nand that is definiteness of purpose, the knowledge of what one wants\r\nand a burning desire to achieve it.”\r\n-Napoleon Hill\r\n\r\nYang kamu perlu lakukan sebelum apa pun adalah perjelas impian dan tujuan hidupmu. Ada banyak sekali alasan kenapa seseorang dapat melakukan dan mencapai suatu hal dengan cepat, salah satunya karena mereka memiliki impian dan tujuan yang jelas. Mereka bisa menentukan langkah apa yang mereka harus tempuh.\r\n\r\nSemakin jelas kita menggambarkan impian dan tujuan hidup kita, semakin mudah bagi kita berjalan mencapainya dan mengatasi penyakit-penyakit malas dan menunda kita.', '1331146160_sebarapa tangguh mental mu.jpg', '', 240, 55000, 'Fisik', 19),
(4, 'The Great History of Muhammad Al-Fatih', 'Manaya Qurrota Ayun', 'Anak Hebat Indonesia', 2023, 'Muhammad Al-Fatih berhasil menorehkan tinta sejarah dengan menaklukkan Konstantinopel, ibukota Kekaisaran Romawi Timur. Peristiwa ini telah diramalkan oleh Nabi Muhammad sebagaimana dijelaskan dalam hadis di bawah ini.\r\n\r\nRasulullah ditanya oleh salah seorang sahabat. “Ya Rasul, mana yang lebih dahulu jatuh ke tangan kaum Muslimin, Konstantinopel atau Romawi?” Nabi menjawab, “Kota Heraklius (Konstantinopel).” (HR Ahmad, Ad-Darimi, Al-Hakim).\r\n\r\nBuku ini bertujuan menyajikan sepak terjang Muhammad Al-Fatih secara mendalam dan komprehensif, di antaranya meliputi:\r\n1. Siapakah Muhammad Al-Fatih, dan mengapa dia dikenal sebagai Muhammad “Al-Fatih”?\r\n2. Bagaimana latar belakang dan masa kecil Muhammad Al-Fatih sebelum menjadi sultan?\r\n3. Bagaimana kepribadian dan keluhuran Muhammad Al-Fatih selama menjabat sebagai sultan?\r\n4. Apa yang membuat penaklukan Konstantinopel oleh Muhammad Al-Fatih menjadi begitu penting dalam sejarah?\r\n5. Apa yang terjadi selama pengepungan Konstantinopel, dan bagaimana akhirnya kota tersebut jatuh ke tangan Muhammad Al-Fatih?\r\n6. Bagaimana penaklukan Konstantinopel memengaruhi perjalanan sejarah dunia dan hubungan antara Timur dan Barat?\r\n7. Selain penaklukan Konstantinopel, apakah pencapaian penting lainnya yang dikaitkan dengan Muhammad Al-Fatih?\r\n8. Apa saja wasiat Muhammad Al-Fatih sebelum ia meninggal dunia?\r\n\r\nPernahkah Anda terpikir betapa menariknya dunia yang terbuka lebar lewat lembaran buku? Membaca bukan hanya kegiatan rutin, tetapi juga petualangan tak terbatas ke dalam imajinasi dan pengetahuan.\r\n\r\nMembaca mengasah pikiran, membuka wawasan, dan memperkaya kosakata. Ini adalah pintu menuju dunia di luar kita yang tak terbatas.\r\n\r\nTetapkan waktu khusus untuk membaca setiap hari. Dari membaca sebelum tidur hingga menyempatkan waktu di pagi hari, kebiasaan membaca dapat dibentuk dengan konsistensi.\r\nPilih buku sesuai minat dan level literasi. Mulailah dengan buku yang sesuai dengan keinginan dan kemampuan membaca.\r\n\r\nTemukan tempat yang tenang dan nyaman untuk membaca. Lampu yang cukup, kursi yang nyaman, dan sedikit musik pelataran bisa menciptakan pengalaman membaca yang lebih baik.\r\n\r\nBergabunglah dalam kelompok membaca atau forum literasi. Diskusikan buku yang Anda baca dan dapatkan rekomendasi dari sesama pembaca.\r\nBuat catatan atau jurnal tentang buku yang telah Anda baca. Tuliskan pemikiran, kesan, dan pelajaran yang Anda dapatkan.\r\n\r\nLibatkan keluarga dalam kegiatan membaca. Bacakan cerita untuk anak-anak atau ajak mereka membaca bersama. Ini menciptakan ikatan keluarga yang erat melalui kegiatan positif.\r\n\r\nJangan ragu untuk menjelajahi genre baru. Terkadang, kejutan terbaik datang dari buku yang tidak pernah Anda bayangkan akan Anda nikmati.\r\nManfaatkan teknologi dengan membaca buku digital atau bergabung dalam komunitas literasi online. Ini membuka peluang untuk terhubung dengan pembaca dari seluruh dunia.', '168833789_muhammad al fatih.jpg', '', 272, 72000, 'Fisik', 11),
(5, 'Belajar Pemrograman Web untuk Pemula', 'Kristianto Haryodi', 'Anak Hebat Indonesia', 2024, 'Buku ini memberikan panduan esensial yang membimbing pemula melalui perjalanan mendalam pengembangan web, Dimulai dengan memahami HTML sebagai tulang punggung halaman web, pembaca akan diajak merinci struktur dokumen, menyematkan gambar, tautan, dan formulir, serta memahami semantik HTML untuk meningkatkan SEO. Setelah itu, panduan menyeluruh tentang CSS akan mempercantik tampilan halaman web, membahas dari penggunaan selektor hingga animasi. Kemudian, JavaScript menghidupkan halaman web dengan pembahasan variabel, perulangan, dan manipulasi DOM, memungkinkan pembaca membangun interaktivitas yang kuat.\r\n\r\nBuku ini tidak hanya menyajikan teori, tapi juga mengajak pembaca membangun proyek web sederhana dari awal hingga akhir. Melibatkan HTML, CSS, dan JavaScript, pembaca akan merasakan interaksi harmonis ketiganya. Lampiran buku memberikan solusi latihan dan contoh kode lengkap dari proyek \"Toko Online Mini\", memberikan pembaca kepercayaan diri untuk menguasai dasar-dasar web development. Buku ini bukan sekadar panduan, tapi teman setia bagi mereka yang ingin meraih potensi penuh dalam pengembangan web. Selamat mencoba!', '896346766_pemrograman web.jpg', '', 256, 60000, 'Fisik', 2),
(25, 'Sejarah Dunia yang Disembunyikan', 'Jonathan Black', 'PT Pustaka Alvabet', 2015, 'Buku Sejarah Dunia Yang Disembunyikan yang ditulis oleh Jonathan Black merupakan buku yang mengungkapkan tentang keraguan dan kepercayaan kita akan sejarah mitologi Yunani dan Mesir Kuno serta cerita rakyat Yahudi yang tidak dapat kita lihat langsung kebenarannya. Buku ini ditulis dengan tujuan memberitahu pembaca tentang fakta dasar sejarah yang berbeda dari yang kita tahu. Pembaca akan mendapatkan pengetahuan baru dan lebih tercerahkan akan wawasan sejarah dunia.\r\n\r\nSinopsis Buku\r\n\r\nBanyak orang mengatakan bahwa sejarah ditulis oleh para pemenang. Hal ini sama sekali tak mengejutkan alias wajar belaka. Tetapi, bagaimana jika sejarah—atau apa yang kita ketahui sebagai sejarah—ditulis oleh orang yang salah? Bagaimana jika semua yang telah kita ketahui hanyalah bagian dari cerita yang salah tersebut?\r\nDalam buku kontroversial yang sangat tersohor ini, Jonathan Black mengupas secara tajam penelusurannya yang brilian tentang misteri sejarah dunia. Dari mitologi Yunani dan Mesir kuno sampai cerita rakyat Yahudi, dari kultus Kristiani sampai Freemason, dari Karel Agung sampai Don Quixote, dari George Washington sampai Hitler, dan dari pewahyuan Muhammad hingga legenda Seribu Satu Malam, Jonathan menunjukkan bahwa pengetahuan sejarah yang terlanjur mapan perlu dipikirkan kembali secara revolusioner. Dengan pengetahuan alternatif ihwal sejarah dunia selama lebih dari 3.000 tahun, dia mengungkap banyak rahasia besar yang selama ini disembunyikan.\r\nBuku ini akan membuat Anda mempertanyakan kembali segala sesuatu yang telah diajarkan kepada Anda. Dan, berbagai pengetahuan baru yang diungkapkan sang penulis benar-benar akan membuka dan mencerahkan wawasan Anda.', '1626580217_BukuSejarahDuniaYangDisembunyikanJonathanBlack.jpg', '1626580217_Buku - Sejarah Dunia yang Disembunyikan (Jonathan Black).pdf', 636, 135000, 'E-Book', 1),
(26, 'Night of Wolves (The Paladins #1)', 'David Dalglish', 'David Dalglish', 2011, ' The humans are weak. Their skin is soft, and their minds dull from years of safety. We are the vicious. We are the destroyers. Come the full moon, when our goddess watches our victory, we will taste of their blood!\r\n\r\n\r\nWolf-men, savage creatures given humanoid form in an ancient war, mass along the Gihon River. Led by their packleader Redclaw, they seek to cross the river and claim a land of their own, slaughtering those that would stand in their way. Two paladins, Jerico of the god Ashhur, and Darius of the god Karak, must helm the desperate defense against the invasion. Their friendship will be tested as their gods resume an unending war, and their very faiths call for the death of the other. Together, friend or foe, they must face Redclaws horde. \r\n\r\n\r\n\r\nNIGHT OF WOLVES by David Dalglish \r\n\r\nCan faith remain when the gods call for blood? ', '544748289_roger sumatra.jpg', '366828219_nightofwolves.pdf', 132, 0, 'E-Book', 1);

-- --------------------------------------------------------

--
-- Table structure for table `genre_buku`
--

CREATE TABLE `genre_buku` (
  `id_genre` int(11) NOT NULL,
  `nama_genre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genre_buku`
--

INSERT INTO `genre_buku` (`id_genre`, `nama_genre`) VALUES
(1, 'Horor'),
(2, 'Sejarah fiksi'),
(4, 'Komedi'),
(5, 'Romantis'),
(6, 'Fantasi'),
(7, 'Pengembangan Diri'),
(8, 'Geografi Sejarah'),
(9, 'Pengembangan & Rekayasa Perangkat Lunak'),
(10, 'Aplikasi Perkantoran');

-- --------------------------------------------------------

--
-- Table structure for table `genre_buku_relasi`
--

CREATE TABLE `genre_buku_relasi` (
  `id_genre_buku` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `id_genre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genre_buku_relasi`
--

INSERT INTO `genre_buku_relasi` (`id_genre_buku`, `id_buku`, `id_genre`) VALUES
(45, 1, 5),
(48, 2, 6),
(54, 5, 9),
(55, 25, 2),
(57, 26, 2),
(62, 3, 7),
(71, 4, 8);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_buku`
--

CREATE TABLE `kategori_buku` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_buku`
--

INSERT INTO `kategori_buku` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Komik'),
(2, 'Novel'),
(3, 'Pengembangan Diri'),
(4, 'Sejarah'),
(5, 'Komputer');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_buku_relasi`
--

CREATE TABLE `kategori_buku_relasi` (
  `id_kategori_buku` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL COMMENT 'relasi ke table "kategori_buku"'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_buku_relasi`
--

INSERT INTO `kategori_buku_relasi` (`id_kategori_buku`, `id_buku`, `id_kategori`) VALUES
(1, 1, 2),
(2, 2, 1),
(3, 3, 3),
(4, 4, 4),
(5, 5, 5),
(25, 25, 4),
(26, 26, 2);

-- --------------------------------------------------------

--
-- Table structure for table `koleksipribadi`
--

CREATE TABLE `koleksipribadi` (
  `id_koleksi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `koleksipribadi`
--

INSERT INTO `koleksipribadi` (`id_koleksi`, `id_user`, `id_buku`) VALUES
(1, 4, 26),
(2, 4, 25),
(3, 4, 2),
(4, 5, 25),
(5, 6, 26),
(6, 6, 25),
(7, 6, 5);

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `id_pembelian` int(11) NOT NULL,
  `id_peminjam` int(11) NOT NULL,
  `id_petugas` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `jumlah_beli` int(11) NOT NULL,
  `total_biaya` int(11) NOT NULL,
  `status` enum('Belum Bayar','Lunas','Batal') NOT NULL,
  `metode_pembayaran` varchar(255) NOT NULL,
  `alasan` text NOT NULL,
  `bukti_pembayaran` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`id_pembelian`, `id_peminjam`, `id_petugas`, `id_buku`, `jumlah_beli`, `total_biaya`, `status`, `metode_pembayaran`, `alasan`, `bukti_pembayaran`) VALUES
(1, 4, 0, 26, 1, 0, 'Lunas', 'Cash', '', ''),
(2, 4, 0, 25, 1, 135000, 'Batal', 'Dana (088808580061)', 'ga punya duit ~ andri (Peminjam)', '1273857763_contoh bukti pembayaran.jpeg'),
(3, 4, 2, 25, 1, 135000, 'Lunas', 'Dana (088808580061)', '', '2003431621_contoh bukti pembayaran.jpeg'),
(4, 4, 0, 25, 1, 135000, 'Batal', 'Dana (088808580061)', 'kepencet ~ andri (Peminjam)', ''),
(5, 4, 0, 1, 2, 100000, 'Batal', 'Cash', 'salah buku ~ andri (Peminjam)', ''),
(6, 4, 2, 2, 1, 70000, 'Lunas', 'Cash', '', ''),
(7, 4, 0, 5, 1, 60000, 'Batal', 'Cash', 'Bruh ~ petugas (Petugas)', ''),
(8, 5, 2, 25, 1, 135000, 'Lunas', 'Dana (088808580061)', '', '1740943985_contoh bukti pembayaran.jpeg'),
(9, 6, 0, 26, 1, 0, 'Lunas', 'Cash', '', ''),
(10, 6, 2, 25, 1, 135000, 'Lunas', 'Dana (088808580061)', '', '1085600836_contoh bukti pembayaran.jpeg'),
(11, 6, 2, 5, 1, 60000, 'Lunas', 'Cash', '', '');

--
-- Triggers `pembelian`
--
DELIMITER $$
CREATE TRIGGER `tambah_koleksi_gratis` AFTER INSERT ON `pembelian` FOR EACH ROW BEGIN
DECLARE frmt ENUM('Fisik');
SELECT format INTO frmt FROM buku WHERE id_buku=NEW.id_buku;

IF NEW.total_biaya > 0 AND NEW.status = 'Belum Bayar' THEN
	IF frmt = 'Fisik' THEN
		UPDATE buku SET stok=stok-NEW.jumlah_beli WHERE id_buku=NEW.id_buku AND format=frmt;
	END IF;
ELSEIF NEW.total_biaya = 0 AND NEW.status='Lunas' THEN
	INSERT INTO koleksipribadi VALUES(NULL,NEW.id_peminjam,NEW.id_buku);
END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_koleksi` AFTER UPDATE ON `pembelian` FOR EACH ROW BEGIN
IF NEW.status = 'Lunas' THEN
    INSERT INTO koleksipribadi VALUES(NULL,NEW.id_peminjam,NEW.id_buku);
ELSEIF NEW.status = 'Batal' THEN
	DELETE FROM koleksipribadi WHERE id_buku=NEW.id_buku;
    UPDATE buku SET stok=stok+NEW.jumlah_beli WHERE id_buku=NEW.id_buku;
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `id_peminjam` int(11) NOT NULL,
  `id_petugas` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date NOT NULL,
  `status` enum('Belum Diambil','Meminjam','Dikembalikan','Telat','Rusak','Hilang','Batal') NOT NULL,
  `alasan` text NOT NULL,
  `kode` varchar(255) NOT NULL,
  `estimasi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `id_peminjam`, `id_petugas`, `id_buku`, `tgl_pinjam`, `tgl_kembali`, `status`, `alasan`, `kode`, `estimasi`) VALUES
(1, 4, 0, 2, '0000-00-00', '0000-00-00', 'Batal', 'Salah Buku ~ andri (Peminjam)', '2994_4', 3),
(2, 4, 2, 3, '2024-03-07', '2024-03-28', 'Telat', '', '4113_4', 3),
(3, 4, 0, 3, '0000-00-00', '0000-00-00', 'Batal', 'dadah ~ petugas (Petugas)', '8130_4', 2),
(4, 6, 2, 4, '2024-03-07', '2024-03-28', 'Telat', '', '5986_6', 3);

--
-- Triggers `peminjaman`
--
DELIMITER $$
CREATE TRIGGER `KURANG_STOK` AFTER INSERT ON `peminjaman` FOR EACH ROW BEGIN
IF NEW.status = 'Belum Diambil' THEN
    UPDATE buku SET stok=stok-1 WHERE id_buku=NEW.id_buku;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `TAMBAH_STOK` AFTER UPDATE ON `peminjaman` FOR EACH ROW BEGIN    
    IF NEW.status = 'Dikembalikan' THEN
    UPDATE buku SET stok=stok+1 WHERE id_buku=NEW.id_buku;
    
    ELSEIF NEW.status = 'Telat' THEN
    UPDATE buku SET stok=stok+1 WHERE id_buku=NEW.id_buku;
    
    ELSEIF NEW.status = 'Rusak' THEN
    UPDATE buku SET stok=stok+1 WHERE id_buku=NEW.id_buku;
    
    ELSEIF NEW.status = 'Batal' THEN
    UPDATE buku SET stok=stok+1 WHERE id_buku=NEW.id_buku;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_peminjam` int(11) NOT NULL COMMENT 'Customer (Peminjam)',
  `id_petugas` int(11) NOT NULL COMMENT 'Petugas',
  `id_peminjaman` int(11) NOT NULL COMMENT 'table "peminjaman"',
  `pelanggaran` varchar(255) NOT NULL,
  `total_biaya` int(11) NOT NULL,
  `status` enum('Belum Lunas','Lunas','Batal') NOT NULL,
  `tgl_transaksi` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_peminjam`, `id_petugas`, `id_peminjaman`, `pelanggaran`, `total_biaya`, `status`, `tgl_transaksi`) VALUES
(1, 4, 2, 2, 'Hilang', 55000, 'Batal', '2024-03-07'),
(2, 4, 2, 2, 'Telat', 16500, 'Lunas', '2024-03-07'),
(3, 6, 2, 4, 'Telat', 21600, 'Lunas', '2024-03-07');

--
-- Triggers `transaksi`
--
DELIMITER $$
CREATE TRIGGER `BATAL_TRANSAKSI` AFTER UPDATE ON `transaksi` FOR EACH ROW BEGIN
	IF NEW.status = 'Batal' THEN
    UPDATE peminjaman SET status=2 WHERE id_peminjaman=NEW.id_peminjaman;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ulasan`
--

CREATE TABLE `ulasan` (
  `id_ulasan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `ulasan` text NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ulasan`
--

INSERT INTO `ulasan` (`id_ulasan`, `id_user`, `id_buku`, `ulasan`, `rating`) VALUES
(2, 4, 26, 'Keren Bukunya !', 5),
(3, 4, 2, 'Bukunya kurang menarik !', 3),
(4, 6, 26, 'Mantap', 4);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `role` enum('Administrator','Petugas','Peminjam') NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `alamat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `role`, `username`, `password`, `email`, `nama_lengkap`, `alamat`) VALUES
(1, 'Peminjam', 'peminjam', '', '', '', ''),
(2, 'Petugas', 'petugas', '', '', '', ''),
(3, 'Administrator', 'admin', '', '', '', ''),
(4, 'Peminjam', 'andri', '123', 'andri@gmail', 'Andri', 'Jl Mana Aja'),
(5, 'Peminjam', 'kiki', '123', 'kiki@gmail.com', 'kiki', 'Depok'),
(6, 'Peminjam', 'budi', '123', 'budianto@email.com', 'Budi', 'Jalan Kemana neng'),
(7, 'Petugas', 'asep', '123', 'asep@email', 'Asep', 'jalan asep');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `genre_buku`
--
ALTER TABLE `genre_buku`
  ADD PRIMARY KEY (`id_genre`);

--
-- Indexes for table `genre_buku_relasi`
--
ALTER TABLE `genre_buku_relasi`
  ADD PRIMARY KEY (`id_genre_buku`);

--
-- Indexes for table `kategori_buku`
--
ALTER TABLE `kategori_buku`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `kategori_buku_relasi`
--
ALTER TABLE `kategori_buku_relasi`
  ADD PRIMARY KEY (`id_kategori_buku`);

--
-- Indexes for table `koleksipribadi`
--
ALTER TABLE `koleksipribadi`
  ADD PRIMARY KEY (`id_koleksi`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_pembelian`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indexes for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id_ulasan`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `genre_buku`
--
ALTER TABLE `genre_buku`
  MODIFY `id_genre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `genre_buku_relasi`
--
ALTER TABLE `genre_buku_relasi`
  MODIFY `id_genre_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `kategori_buku`
--
ALTER TABLE `kategori_buku`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kategori_buku_relasi`
--
ALTER TABLE `kategori_buku_relasi`
  MODIFY `id_kategori_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `koleksipribadi`
--
ALTER TABLE `koleksipribadi`
  MODIFY `id_koleksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id_pembelian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id_ulasan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
