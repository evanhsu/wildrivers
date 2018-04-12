<?php

/***************************
  Sample using an FDF file
****************************/

require_once('fpdm.php');

$pdf = new FPDM('template.pdf', 'fields.fdf');
$pdf->Merge();
$pdf->Output();
?>
