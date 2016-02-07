<?php


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

function offset_coords_to_center_crosshairs_at($x,$y,$crosshair_img) {
	$x_corrected = $x - floor(imagesx($crosshair_img)/2);
	$y_corrected = $y - floor(imagesy($crosshair_img)/2);
	
	return array($x_corrected,$y_corrected);
}

function load_card_config($zone) {
	//This is a list of stored calibration arrays for different pocket cards
	//
	//	Input: a string, referring to a particular pocket card that should be loaded
	//	Output: an array of calibration values:
	//			[image_filename,x0_pixel,y0_pixel,x_max_pixel,y_max_pixel,x0,y0,x_max,y_max]
	
	switch($zone) {
		case 'interior':
			$pocket_card = array('image_filename'=>'../images/2012_rsf_erc_interior.png',
								'x0_pixel'=>72,		// The pixel coordinates of the lower-left corner of the graph axis.
								'y0_pixel'=>400,
								'x_max_pixel'=>558,	// The pixel coordinates of the upper-right corner of the graph axis.
								'y_max_pixel'=>69,
								'x0'=>5,			// The lowest value of 'x' that can be graphed 
								'y0'=>0,			// The lowest value of 'y' that can be graphed
								'x_max'=>10,		// The highest value of 'x' that can be graphed
								'y_max'=>70);		// The highest value of 'y' that can be graphed
			break;
		
		case 'westside':
			$pocket_card = array('image_filename'=>'../images/2012_rsf_erc_westside.png',
								'x0_pixel'=>72,		// The pixel coordinates of the lower-left corner of the graph axis.
								'y0_pixel'=>402,
								'x_max_pixel'=>560,	// The pixel coordinates of the upper-right corner of the graph axis.
								'y_max_pixel'=>74,
								'x0'=>5,			// The lowest value of 'x' that can be graphed 
								'y0'=>0,			// The lowest value of 'y' that can be graphed
								'x_max'=>10,		// The highest value of 'x' that can be graphed
								'y_max'=>90);		// The highest value of 'y' that can be graphed
			break;
			
		default:
			bad_image("The pocket card requested does not exist.");
			break;
	}
	//Since the graph extends until the END of the last month on the x-axis, add 1 to the ending month
	$pocket_card['x_max']++;
	
	return $pocket_card;
} // END function load_card_config()

function load_pocket_card($zone) {
	$pocket_card = array();
	
	// Load the background image
	$pocket_card = load_card_config($zone);
	$pocket_card['image'] = @imagecreatefrompng($pocket_card['image_filename']);
	if(!$pocket_card['image']) bad_image("Error loading ".$pocket_card['image_filename']);
	
	return $pocket_card;
} // END function load_pocket_card()

/***********************************************************************************/
/***********************************************************************************/
/***********************************************************************************/
$zone = $_GET['zone'];
$month = $_GET['month'];
$day = $_GET['day'];
$value = $_GET['value'];

$pocket_card = load_pocket_card($zone);

$crosshairs_img_filename = "../images/crosshairs.png";

// Load the crosshairs image
$crosshairs = @imagecreatefrompng($crosshairs_img_filename);
if(!$crosshairs) bad_image("Error loading ".$crosshairs_img_filename);

/***********************************************************************************************/
/***********************************************************************************************/
/***********************************************************************************************/


// Get the value to plot
//$erc = 75;
//$month = date("m");
//$day = date("d");

// Calculate x-coordinate
$dst_x = (($month + ($day / 30)) - $pocket_card['x0']) / ($pocket_card['x_max'] - $pocket_card['x0']);
$dst_x = floor($dst_x * ($pocket_card['x_max_pixel'] - $pocket_card['x0_pixel'])) + $pocket_card['x0_pixel'];

// Calculate y-coordinate
$dst_y = ($value / ($pocket_card['y_max'] - $pocket_card['y0']));
$dst_y = $pocket_card['y0_pixel'] - floor($dst_y * ($pocket_card['y0_pixel'] - $pocket_card['y_max_pixel']));

$corrected_coords = offset_coords_to_center_crosshairs_at($dst_x, $dst_y, $crosshairs);
$dst_x = $corrected_coords[0];
$dst_y = $corrected_coords[1];

$src_x = 0;
$src_y = 0;
$src_width = imagesx($crosshairs);
$src_height = imagesy($crosshairs);

imagecopy ($pocket_card['image'], $crosshairs, $dst_x, $dst_y, $src_x, $src_y, $src_width, $src_height);


// Write finished image to browser
header('Content-Type: image/png');
imagepng($pocket_card['image']);
//imagepng($crosshairs);

?>