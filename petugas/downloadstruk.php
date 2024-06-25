<?php
ob_start();
include('struk.php'); // Sesuaiin nama file nya
$htmlContent = ob_get_clean();

// Include autoloader
require '../script/library/dompdf/vendor/autoload.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$dompdf->loadHtml(''.$htmlContent);

// Render
$dompdf->render();

//Output
$dompdf->stream('document', array('Attachment' => 0));

?>