<?php
session_start();
if(!isset($_SESSION['logged_in']) || ($_SESSION['logged_in'] != 1)) header('location: index.php');

require_once('../scripts/fpdf/fpdi.php');

// initiate FPDI 
$pdf =& new FPDI(); 

// add a page 
$pdf->AddPage(); 

// set the sourcefile 
$pdf->setSourceFile('blm_receipt_for_property.pdf'); 

// import page 1 (of 1)
$tplIdx = $pdf->importPage(1); 

// use the imported page and place it at point 10,10 with a width of 100 mm 
$pdf->useTemplate($tplIdx, 0, 0); 
 
// Set font properties for upper portion
$pdf->SetFont('Arial','',8); // 'font name','style [B,I,U]', size (points)
$pdf->SetTextColor(128,128,128);

// now overlay some text
//Print Property Name
$pdf->SetXY(22, 56); 
$pdf->Write(0, urldecode($_GET['serial_no']));

//Print Description
$pdf->SetXY(48, 56); 
$pdf->Write(0, urldecode($_GET['item_type']) . ", " . urldecode($_GET['description']));

// Set font properties for lower portion
$pdf->SetFont('Arial','',12); // 'font name','style [B,I,U]', size (points)
$pdf->SetTextColor(128,128,128);

//Print "Issued By"
$pdf->SetXY(15, 230); 
$pdf->Write(0, urldecode($_GET['checked_out_by']));

//Print "Date Issued"
$pdf->SetXY(130, 230); 
$my_date = date('d') . " " . date('F') . " " . date('Y');
$pdf->Write(0, $my_date);

//Print "Received By"
$pdf->SetXY(15, 260); 
$pdf->Write(0, $_GET['checked_out_to']);

$pdf->Output('blm_receipt_for_property.pdf', 'I'); //send the file inline to the browser
//$pdf->Output('blm_receipt_for_property.pdf', 'D'); //send to the browser and force a file download

?>