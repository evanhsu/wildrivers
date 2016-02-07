<?php
/*****************************************************************************************/
/*****************************<< Server Location >>***************************************/
/*****************************************************************************************/
	
	$_SESSION['email_sender'] = 'RapRec@siskiyourappellers.com';
	// See 'includes/php_doc_root.php' to set server root path
	
/*****************************************************************************************/
/*****************************<< Behavioral Settings >>***********************************/
/*****************************************************************************************/	

	$_SESSION['max_crew_admins_per_crew'] = 8;
	$_SESSION['days_until_confirmation_expires'] = 7;
	
	$_SESSION['raps_for_merit_1'] = 50;		// Make sure this number is LESS than $_SESSION['raps_for_merit_2']
	$_SESSION['raps_for_merit_2'] = 100;	// Make sure this number is GREATER than $_SESSION['raps_for_merit_1']
	$_SESSION['raps_for_merit_3'] = 150;	// Make sure this number is GREATER than $_SESSION['raps_for_merit_2']
	$_SESSION['raps_for_merit_4'] = 200;	// Make sure this number is GREATER than $_SESSION['raps_for_merit_3']
	$_SESSION['raps_for_merit_5'] = 300;	// Make sure this number is GREATER than $_SESSION['raps_for_merit_4']
	
	$_SESSION['merit_1_image'] = "images/genie_silver.png";	// This is the filename of the 'badge' image that will appear next to an HRAP's name if that HRAP has enough rappels to meet 'raps_for_merit_1'
	$_SESSION['merit_2_image'] = "images/genie_gold.png";	// This is the filename of the 'badge' image that will appear next to an HRAP's name if that HRAP has enough rappels to meet 'raps_for_merit_2'
	$_SESSION['merit_3_image'] = "images/merit3.png";
	$_SESSION['merit_4_image'] = "images/merit4.png";
	$_SESSION['merit_5_image'] = "images/merit5.png";
	
	$_SESSION['missing_headshot_image'] = 'images/hrap_headshots/missing.jpg';	// Image to use when an HRAP has not uploaded a headshot photo
	$_SESSION['temp_image_filename'] = 'images/temp_image.jpg';					// A spot to stash an existing image while a replacement is being processed (in case the replacement fails, original can be restored)
	
	$_SESSION['autosuggest_num_results'] = 10;	// How many results should the autocomplete script return?  This should be small enough to not flow off the screen (Recommend 5 - 10)
	
	
	
/*****************************************************************************************/
/***************************<< Equipment Parameters >>************************************/
/*****************************************************************************************/

	$_SESSION['max_rope_life_time'] = 5;	//Number of years before a rope MUST be retired
	$_SESSION['max_rope_life_uses'] = 200;	//Number of uses on BOTH ENDS COMBINED before a rope MUST be retired (100 uses on each end == 200 max_rope_life_uses)
	$_SESSION['normalization_bins_in_raps_before_retirement_chart'] = 5; //The number of 'bins' you want in the normalized graph
	
	$_SESSION['$equipment_retirement_categories'] = array('','age','use','field_damage','other_damage','defect');
	
/*****************************************************************************************/
/***************************<< Training Parameters >>*************************************/
/*****************************************************************************************/

	$_SESSION['proficiency_duration'] = 14;	//Number of days an HRAP can go without rappelling and still remain proficient
	
	$_SESSION['password_pattern'] = '/^[^\'"\/\\\\]{6,15}$/'; // 6 - 15 characters, no quotes or slashes allowed

?>