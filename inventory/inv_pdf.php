<?php
	if(isset($_GET['session_id'])) session_id($_GET['session_id']);
	session_start();
	require_once("../includes/auth_functions.php");
	
	// if(substr(strtolower($_SERVER['PHP_SELF']),1,9) == "inventory") header('location: http://inventory.siskiyourappellers.com');
	$php_self = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

	if(($_SESSION['logged_in'] == 1) && check_access("inventory")) {
		include("../includes/inv_functions.php"); //Contains functions: add_item, rm_item, get_inv
		require_once("../classes/mydb_class.php");
		
		if(isset($_GET['sort_by'])) $_SESSION['sort_view_by'] = $_GET['sort_by'];
		elseif (!isset($_SESSION['sort_view_by'])) $_SESSION['sort_view_by'] = "item_type";
		
	}//END if($_SESSION['logged_in'] == 1)
	else header('location: http://tools.siskiyourappellers.com/admin/index.php');

?>
<?php
session_start();
if(!isset($_SESSION['logged_in']) || ($_SESSION['logged_in'] != 1)) header('location: index.php');

require_once('../scripts/fpdf/fpdf.php');

class PDF extends FPDF
{
//Colored table
function FancyTable($header,$data, $rows_printed)
{
	if($rows_printed == 0) {
		//Configure cell to display person's name
		$this->SetFillColor(255);
		$this->SetTextColor(0);
		$this->SetDrawColor(255);
		$this->SetLineWidth(0);
		$this->SetFont('Arial','B',10);
		$this->Cell(100,2,date('d-M-Y').' Helitack Inventory',0,1,'L',0);
		$this->Cell(100,7,'Sorted by '.$_SESSION['sort_view_by'],0,1,'L',0);
	}
	//Print page number
	$this->SetFont('Arial','',8);
	$this->SetLineWidth(0);
	$this->Cell(100,2,'Page '.$this->PageNo(),0,1,'L',0);
	$this->Ln();
	
    //Configure cells for header row
    $this->SetFillColor(255,255,255);
    $this->SetTextColor(0);
    $this->SetDrawColor(128,128,128);
    $this->SetLineWidth(.3);
    $this->SetFont('','B','8');
    //Header
    $w=array(15,18,15,12,47,27,22,10,35);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],5,$header[$i],1,0,'C',1);
    $this->Ln();
	
    //Configure cells for main data table
    //$this->SetFillColor(235,235,235);
	$this->SetFillColor(250,250,250);
	$this->SetDrawColor(200,200,200);
    $this->SetLineWidth(.1);
    $this->SetTextColor(0);
    $this->SetFont('Arial','',8);
	//Data
    $fill=0;
	$B = 'B';
	$rows_this_page = 0;
	$offset = 0;
	$last_cat_title = '';
	$cur_cat_title = '';
    foreach($data as $row)
    {
		$offset++;
		if($offset <= $rows_printed) continue;
		
		$cur_cat_title = $row[$_SESSION['sort_view_by']];
		if($last_cat_title != $cur_cat_title) {
			$fill=!$fill;
			//if($B == 'B') $B = '';
			//else $B = 'B';
		}
		$last_cat_title = $cur_cat_title;
		
        $this->Cell($w[0],4,$row['serial_no'],		'LRT'.$B,0,'L',$fill);
        $this->Cell($w[1],4,$row['item_type'],		'LRT'.$B,0,'L',$fill);
        $this->Cell($w[2],4,$row['size'],			'LRT'.$B,0,'L',$fill);
		$this->Cell($w[3],4,$row['color'],			'LRT'.$B,0,'L',$fill);
		$this->Cell($w[4],4,$row['description'],	'LRT'.$B,0,'L',$fill);
		$this->Cell($w[5],4,$row['checked_out_to'],	'LRT'.$B,0,'L',$fill);
		$this->Cell($w[6],4,$row['item_condition'],	'LRT'.$B,0,'L',$fill);
		$this->Cell($w[7],4,$row['usable'],			'LRT'.$B,0,'L',$fill);
		$this->Cell($w[8],4,$row['note'],			'LRT'.$B,0,'L',$fill);
        $this->Ln();
		
		$rows_printed++;
		$rows_this_page++;
		if($rows_this_page >= 60) break;
    }
    $this->Cell(array_sum($w),0,'','T');
	
	return $rows_printed;
}
}

$pdf=new PDF();
$pdf->SetMargins(4,5,4);
//Column titles
$header = $_SESSION['inv_headers'];
		
//Data loading
$data = $_SESSION['inv_array'];
$pdf->SetFont('Arial','',12);

$rows_printed = 0;
while($rows_printed < count($data)) {
	$pdf->AddPage();
	$rows_printed = $pdf->FancyTable($header,$data, $rows_printed);
}
$pdf->Output();
?>