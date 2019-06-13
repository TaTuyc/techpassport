<?php
    include_once '../php/action.php';
    
    // reference the Dompdf namespace
    use Dompdf\Dompdf;
    include_once '../dompdf/autoload.inc.php';
    
    // instantiate and use the dompdf class
   $dompdf = new Dompdf();
   $html = $_SESSION["prepared_print_page"];
   $dompdf->loadHtml($html);
   
   // (Optional) Setup the paper size and orientation
   $dompdf->setPaper('A4', 'portrait');
   
   // Render the HTML as PDF
   $dompdf->render();
   
   // Output the generated PDF to Browser
   $dompdf->stream();
?>