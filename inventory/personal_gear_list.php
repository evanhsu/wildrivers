<?php
session_start();
if(!isset($_SESSION['logged_in']) || ($_SESSION['logged_in'] != 1)) header('location: index.php');

require_once('../scripts/fpdf/fpdf.php');

class PDF extends FPDF
{
//Colored table
function FancyTable($header,$data,$name)
{
	//Configure cell to display person's name
	$this->SetFillColor(255);
	$this->SetTextColor(0);
    $this->SetDrawColor(255);
    $this->SetLineWidth(0);
    $this->SetFont('Arial','B',12);
	$this->Cell(100,6,$name . ' : Personal Gear : ' . date('d-M-Y'),0,1,'L',0);
	
    //Configure cells for header row
    $this->SetFillColor(255,255,255);
    $this->SetTextColor(0);
    $this->SetDrawColor(128,128,128);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    //Header
    $w=array(20,30,20,17,80,25);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',1);
    $this->Ln();
	
    //Configure cells for main data table
    //$this->SetFillColor(235,235,235);
	$this->SetFillColor(255,255,255);
	$this->SetDrawColor(200,200,200);
    $this->SetLineWidth(.1);
    $this->SetTextColor(0);
    $this->SetFont('Arial','',9);
	//Data
    $fill=0;
	$B = 'B';
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR'.$B,0,'L',$fill);
        $this->Cell($w[1],6,$row[1],'LR'.$B,0,'L',$fill);
        $this->Cell($w[2],6,$row[2],'LR'.$B,0,'L',$fill);
		$this->Cell($w[3],6,$row[3],'LR'.$B,0,'L',$fill);
		$this->Cell($w[4],6,$row[4],'LR'.$B,0,'L',$fill);
		$this->Cell($w[5],6,$row[5],'LR'.$B,0,'L',$fill);
        $this->Ln();
        //$fill=!$fill;
		//if($B == 'B') $B = '';
		//else $B = 'B';
    }
    $this->Cell(array_sum($w),0,'','T');
}
}

$pdf=new PDF();

//Column titles
$header = $_SESSION['pg_header'];

//Data loading
$data = $_SESSION['pg_array'];
$pdf->SetFont('Arial','',12);
$pdf->AddPage();
$pdf->FancyTable($header,$data,$_SESSION['pg_crewmember_name']);
$pdf->Output();
?>