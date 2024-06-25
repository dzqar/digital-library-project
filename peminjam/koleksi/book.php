<!-- <iframe id="pdf-js-viewer" src="../../style/buku/file/<?= $_GET['file']?>" style="width: 100%; height: 100%" frameborder="0"></iframe> -->

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Icon Title -->
	<link rel="icon" href="../../style/logo/logo.png" type="image/x-icon">
	<!-- Bootstrap Icons -->
	<link rel="stylesheet" href="../../style/assets/bootstrap/bootstrap-icons.css">
	<link rel="stylesheet" href="../../style/assets/bootstrap/bootstrap-icons.min.css">
	<!-- Style Bootstrap -->
	<link rel="stylesheet" href="../../style/assets/bootstrap/bootstrap.min.css">
	<script src="../../style/assets/bootstrap/bootstrap.bundle.min.js"></script>
	<!-- Style CSS -->
	<link rel="stylesheet" href="../../style/style.css">
	<!-- SweetAlert2 -->
	<link rel="stylesheet" href="../../style/assets/sweetalert/sweetalert2.min.css">
	<link rel="stylesheet" href="../../style/assets/sweetalert/animate.min.css">
	<script src="../../style/assets/sweetalert/sweetalert2.min.js"></script>
	<title>Baca Buku</title>
	<style>
		canvas{
			/*border: 1px solid #000;*/
			width: 100%;
			height: 50%;
		}
	</style>
</head>
<body>
	<!-- <input type="text" id="urul" value="<?= $_GET['file']?>"> -->
	<!-- <?= $_GET['file']?> -->
	<div class="container border w-50 justify-content-center align-items-center">
		<div class="row">
			<div class="col">
				<button class="btn btn-primary" id="prev">Prev</button>
				<button class="btn btn-primary" id="next">Next</button>
				<span id="npages">not yet</span>
			</div>
		</div>
			<div class="row" style="height: 5%">
		<canvas id="cnv"></canvas>
				
			</div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.4.456/pdf.min.js"></script>
	<script>
		// urul = document.getElementById("urul");
		const PDFStart = nameRoute => {           
			let loadingTask = pdfjsLib.getDocument(nameRoute),
			pdfDoc = null,
			canvas = document.querySelector('#cnv'),
			ctx = canvas.getContext('2d'),
			scale = 1.5,
			numPage = 1;

			const GeneratePDF = numPage => {

				pdfDoc.getPage(numPage).then(page => {

					let viewport = page.getViewport({ scale: scale });
					canvas.height = viewport.height;
					canvas.width = viewport.width;

					let renderContext = {
						canvasContext : ctx,
						viewport:  viewport
					}

					page.render(renderContext);
				})
				document.querySelector('#npages').innerHTML = numPage;

			}

			const PrevPage = () => {
				if(numPage === 1){
					return
				}
				numPage--;
				GeneratePDF(numPage);
			}

			const NextPage = () => {
				if(numPage >= pdfDoc.numPages){
					return
				}
				numPage++;
				GeneratePDF(numPage);
			}

			document.querySelector('#prev').addEventListener('click', PrevPage)
			document.querySelector('#next').addEventListener('click', NextPage )

			loadingTask.promise.then(pdfDoc_ => {
				pdfDoc = pdfDoc_;
				document.querySelector('#npages').innerHTML = pdfDoc.numPages;
				GeneratePDF(numPage)
			});
		}

		const startPdf = () => {
			PDFStart('../../style/buku/file/<?= $_POST['file']?>')
		}

		window.addEventListener('load', startPdf);
	</script>
</body>
</html>

<!-- SRC : https://medium.com/geekculture/how-to-use-pdf-js-and-how-to-create-a-simple-pdf-viewer-for-your-web-in-javascript-5cff608a3a10 -->