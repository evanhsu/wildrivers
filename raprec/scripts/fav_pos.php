<?php
	/*******************************************************************************************************/
	/* Copyright (C) 2012 Evan Hsu
       Permission is hereby granted, free of charge, to any person obtaining a copy of this software
	   and associated documentation files (the "Software"), to deal in the Software without restriction,
	   including without limitation the rights to use, copy, modify, merge, publish, distribute,
	   sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is
	   furnished to do so, subject to the following conditions:

       The above copyright notice and this permission notice shall be included in all copies or
	   substantial portions of the Software.

       THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT
	   NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
	   IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
	   WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
	   SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE. */
	/********************************************************************************************************/
include('../includes/php_doc_root.php');

require_once("classes/mydb_class.php");
require_once("classes/hrap_class.php");
require_once("classes/crew_class.php");

session_name('raprec');
session_start();
/***********************************************************************************/
/***********************************************************************************/
/***********************************************************************************/
function bad_image($msg) {
	$img = imagecreatetruecolor (270, 300); /* Create a blank image */
	$bgc = imagecolorallocate ($img, 255, 255, 255);
	$tc = imagecolorallocate ($img, 50, 150, 50);
	imagefilledrectangle ($img, 0, 0, 325, 400, $bgc);
	/* Output an errmsg */
	imagestring ($img, 2, 5, 50, $msg, $tc);
	header('Content-Type: image/gif');
	imagegif($img);
	exit;
}

/***********************************************************************************/
/***********************************************************************************/
/***********************************************************************************/

//$helicopter_img_filename = "../images/fav_position.gif";
$helicopter_img_filename = "../images/fav_position.png";

// Load the background image
//$helicopter = @imagecreatefromgif($helicopter_img_filename);
$helicopter = @imagecreatefrompng($helicopter_img_filename);
if(!$helicopter) bad_image("Error loading ".$helicopter_img_filename);

// Define the starting coordinates for each of the text fields in the image
// These coordinates will be adjusted depending on the number of digits that
// need to fit into the text field
// Seats are numbered as follows (this number corresponds to 'seat_num' in the $raps_by_seat array):
/*------------------------------------------------------
			 MEDIUM						LIGHT
	0		1		2		3
	
		4				5				8	9
		
		6				7

--------------------------------------------------------*/
$seat_coords = array(
					 array('x'=>21,'y'=>84),	/* 0: Medium Bench Stick 1 Left (same as Medium Hellhole Stick 1 Left) */
					 array('x'=>50,'y'=>84),	/* 1: Medium Bench Stick 2 Left  */
					 array('x'=>80,'y'=>84),	/* 2: Medium Bench Stick 2 Right  */
					 array('x'=>110,'y'=>84),	/* 3: Medium Bench Stick 1 Right (same as Medium Hellhole Stick 1 Right)  */
					 array('x'=>35,'y'=>131),	/* 4: Medium Bench Stick 3 Left (same as Medium Hellhole Stick 2 Left)  */
					 array('x'=>93,'y'=>131),	/* 5: Medium Bench Stick 3 Right (same as Medium Hellhole Stick 2 Right)  */
					 array('x'=>35,'y'=>159),	/* 6: Medium Hellhole Stick 3 Left  */
					 array('x'=>93,'y'=>159),	/* 7: Medium Hellhole Stick 3 Right  */
					 array('x'=>187,'y'=>97),	/* 8: Light Stick 1 Left  */
					 array('x'=>228,'y'=>97)	/* 9: Light Stick 1 Right  */
					 );
					 
$seats = array(
			  'light'=>array(
						   'bench'=>array(
										  '1'=>array(
													 'left'=>array('raps'=>0,'seat_num'=>8),
													'right'=>array('raps'=>0,'seat_num'=>9)
													)
										  ),
						   'hellhole'=>array(
										  '1'=>array(
													 'left'=>array('raps'=>0,'seat_num'=>8),
													'right'=>array('raps'=>0,'seat_num'=>9)
													)
										  )
						   ),
			 'medium'=>array(
						   'bench'=>array(
										  '1'=>array(
													 'left'=>array('raps'=>0,'seat_num'=>0),
													'right'=>array('raps'=>0,'seat_num'=>3)
													),
										  '2'=>array(
													 'left'=>array('raps'=>0,'seat_num'=>1),
													'right'=>array('raps'=>0,'seat_num'=>2)
													),
										  '3'=>array(
													 'left'=>array('raps'=>0,'seat_num'=>4),
													'right'=>array('raps'=>0,'seat_num'=>5)
													)
										  ),
						   'hellhole'=>array(
											 '1'=>array(
														'left'=>array('raps'=>0,'seat_num'=>0),
														'right'=>array('raps'=>0,'seat_num'=>3)
														),
											 '2'=>array(
														'left'=>array('raps'=>0,'seat_num'=>4),
														'right'=>array('raps'=>0,'seat_num'=>5)
														),
											 '3'=>array(
														'left'=>array('raps'=>0,'seat_num'=>6),
														'right'=>array('raps'=>0,'seat_num'=>7)
														)
											 )
						   )
			 );

$heli_types = array('1'=>'heavy', '2'=>'medium', '3'=>'light'); // Match helicopter type number with their type description


$raps_by_seat = array(0,0,0,0,0,0,0,0,0,0); // This array is used to consolidate redundant entries in the $raps_by_seat array so that each seat has only one entry

$query = "
	SELECT rappels.door, rappels.stick, aircraft_types.type, aircraft_types.configuration, COUNT( rappels.id ) as rap_count
	FROM rappels
	INNER JOIN operations ON rappels.operation_id = operations.id
	INNER JOIN aircraft_types ON operations.aircraft_type_config = aircraft_types.id
	
	WHERE hrap_id = " . $_SESSION['current_view']['hrap']->get('id')."
	GROUP BY TYPE , configuration, stick, door";

$result = mydb::cxn()->query($query);

if(mydb::cxn()->error != NULL) bad_image("Error retrieving HRAP stats");

while($row = $result->fetch_assoc()) {
	$raps_by_seat[$seats[$heli_types[$row['type']]][$row['configuration']][$row['stick']][$row['door']]['seat_num']] += $row['rap_count'];
}

// Define the font color
$fc = imagecolorallocate ($helicopter, 50, 50, 50);

foreach($raps_by_seat as $seat_num=>$rap_count) {
	$x = $seat_coords[$seat_num]['x'];
	$y = $seat_coords[$seat_num]['y'];
	
	$x = $x - 4 * (strlen($raps_by_seat[$seat_num]) - 1); // Move the starting point LEFT 4 pixels for each digit in excess of 1
	imagestring ($helicopter, 4, $x, $y, $raps_by_seat[$seat_num], $fc);
}


// Write finished image to browser
header('Content-Type: image/gif');
imagegif($helicopter);

?>
