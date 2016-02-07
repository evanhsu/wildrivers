<?php

/***************************
  Merge a PDF fillable form with field data, resulting in a dynamically-filled form.
  Requires the FPDF library and 'fpdm.php' (http://fpdf.org/en/script/script93.php)
  
  INPUT:
  	An associative array that matches PDF field names with their intended values.

  OUTPUT:
  	A PDF file with fields populated.
****************************/
require('../scripts/fpdf/fpdf.php');
require('../scripts/fpdm/fpdm.php');

function mergePDF($template,$fields,$outfilename = "src_output.pdf") {
	
	$pdf = new FPDM($template);
	$pdf->Load($fields, true); // second parameter: false if field values are in ISO-8859-1, true if UTF-8
	$pdf->Merge();
	$pdf->Output($outfilename,"D");
}

?>