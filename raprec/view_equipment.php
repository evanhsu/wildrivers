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
/*
	rope status		:	"in-service", "suspended", "missing", "retired"
	retired category	:	"age", "use", "field_damage", "other_damage"
*/
	include('includes/php_doc_root.php');
	
	require_once("classes/mydb_class.php");
	require_once("classes/user_class.php");
	require_once("classes/email_class.php");
	require_once("classes/hrap_class.php");
	require_once("classes/crew_class.php");
	require_once("classes/operation_class.php");
	require_once("classes/rappel_class.php");
	require_once("classes/item_class.php");
	require_once("classes/rappel_equipment_class.php");
	require_once("classes/rope_class.php");
	require_once("classes/genie_class.php");
	require_once("classes/letdown_line_class.php");
	
	session_name('raprec');
	session_start();
	
	require("includes/constants.php");	// Force 'constants.php' to load, even if it has been previously included by one of the classes above.  Must set SESSION vars AFTER the session_start() declaration.
	require_once("includes/auth_functions.php");
	require_once("includes/check_get_vars.php");
	require_once("includes/make_menu.php");
	require_once("includes/photo_upload_functions.php");
	require_once("includes/aircraft_layouts.php");

/*********************************************************************************************************************/
/*********************************************************************************************************************/
/*********************************************************************************************************************/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Equipment | RapRec Central</title>

<link rel="Shortcut Icon" href="favicon.ico">
<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, rappel, rappelling, rappeller, rapel, rapell, rapeller, repeller, repelling, records, record, history" />
<meta name="Description" content="The National Rappel Record Website - This page displays equipment use records." />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />
<?php if($_SESSION['mobile'] == 1) echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/mobile.css\" />\n"; ?>

<script type="text/javascript" src="scripts/searchautosuggest/lib/ajax_framework.js"></script>

<script type="text/javascript">
	AC_FL_RunContent = 0;
	DetectFlashVer = 0;
</script>
<script type="text/javascript" src="includes/charts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="includes/charts/equipment_stat_charts.js"></script>

<style>
a:hover {text-decoration:none;}
</style>
</head>

<body>
    <div id="banner_left"><a href="index.php"><img src="images/raprec_banner_left.jpg" style="border:none" alt="RapRec Central Logo" /></a></div>
    <div id="banner_right"><a href="index.php"><img src="images/raprec_banner_right.jpg" style="border:none" alt="RapRec Central" /></a></div>
	
    <div id="left_sidebar">
    	<?php make_menu(); ?>
    </div>
	
    <div id="location_bar"><?php echo $_SESSION['location_bar']; ?></div>
    
    <div id="content" style="text-align:center">
    
<?php
/* ------------------------------------------------<< BEGIN CONTENT >>-------------------------------------------------------------*/

$eq_type = false;		// The TYPE of equipment ('rope', 'genie')
$zoom_level = false;	// The amount of equipment to display ('crew', 'region')

// Determine whether to show ROPE, GENIE or LETDOWN_LINE records
if(isset($_GET['eq_type']) && in_array(strtolower($_GET['eq_type']),array('rope','genie','letdown_line'))) {
	$eq_type = strtolower($_GET['eq_type']);
	try {
		$eq = new $eq_type;
		if(isset($_GET['eq_id']) && ($_GET['eq_id'] != "")) $eq->load($_GET['eq_id']);
		else $eq->load(false);
	} catch(Exception $e) {
		/* An equipment type was specified, but not a specific equipment ID - show cumulatives for this eq_type */
		// Determine whether to show equipment records for a particular CREW or an entire REGION
		try {
			$zoom_obj = new crew;	// $zoom_obj will become either a CREW OBJECT or a REGION ID, depending on $zoom_level
			
			isset($_GET['crew']) ? $zoom_obj->load($_GET['crew']) : $zoom_obj->load(false);
			$zoom_level = 'crew';
		} catch(Exception $e) {/* No CREW was specified - check for a preselected CREW in the 'current_view' SESSION var */
			try {
				if($_SESSION['current_view']['crew'] != NULL) $zoom_obj->load($_SESSION['current_view']['crew']->get('id'));
				else throw new Exception('This exception is meant solely to trigger the following catch block');
				$zoom_level = 'crew';
			} catch(Exception $e) {/* No CREW selection was stored in the SESSION - check for a REGION selection*/
			
				if(isset($_SESSION['current_view']['region']) && $_SESSION['current_view']['region'] !== NULL) {
					$zoom_level = 'region';
					unset($zoom_obj);
					$zoom_obj = $_SESSION['current_view']['region'];
				}
				elseif(in_array($_GET['region'],array(1,2,3,4,5,6,8,9,10))) {
					$zoom_level = 'region';
					unset($zoom_obj);
					$_SESSION['current_view']['region'] = $_GET['region'];
					$zoom_obj = $_GET['region'];
				}
				elseif($_SESSION['current_user']->get('crew_affiliation_id') != false) {
					/* No REGION was selected - reload the page with the crew set as the current USER's crew) */
					if(isset($_GET['eq_type'])) $param = "eq_type=".$_GET['eq_type']."&";
					echo "<script type=\"text/javascript\">window.location = \"".$_SERVER['PHP_SELF']."?".$param."crew=".$_SESSION['current_user']->get('crew_affiliation_id')."\";</script>";
					//$zoom_obj->load($_SESSION['current_user']->get('crew_affiliation_id'));
					//$zoom_level = 'crew';
				}
				else {
					//There is no CREW or REGION specified, and all attempts to choose a reasonable region have failed.
					//The show_eq_type_menu() will display an error message
					$text = show_eq_type_menu();
				}
				
			} // End: catch #3
		} // End: catch #2
	} // End: catch #1
	
	if($eq->get('id') != NULL) $text = show_one_rappel_equipment_item($eq);
	else $text = show_rappel_equipment($eq->get('item_type'),$zoom_level, $zoom_obj);
	
} // End: if(in_array(strtolower($_GET['eq_type']),array('rope','genie'))

else {/* No equipment type was specified - we need this info before we can continue, display a menu */
	$text = show_eq_type_menu();
}


echo $text."\n</div>\n";

/* -------------------------------------------------<< END CONTENT >>--------------------------------------------------------------*/
?>  
    </div> <!-- End 'content' -->
   	
<div style="clear:both; display:block; visibility:hidden;"></div>
</body>
</html>

<?php
/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_header() *******************************************************************/
/*******************************************************************************************************************************/
	function show_header($viewing, $mfr_serial_num = "", $owner = NULL) {
		
		$text = "<div style=\"width:100%; text-align:left; font-size:1.5em; font-weight:bold;\">Viewing ".$viewing."<br>";
		if($mfr_serial_num != "") {
			//This item has a manufacturer's serial number in addition to the primary serial #
			$text .= "AKA: #".strtoupper($mfr_serial_num)."<br />";
		}
		$text .= "Belonging to ".$owner."</div>\n"
			."<hr style=\"width:100%;height:2px; border:none; color:#666666; background-color:#666666;\">\n"
			."<div style=\"text-align:center; width:100%;\">\n";

		print $text;
	} // End: function show_header()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_rappel_equipment() ********************************************************************/
/*******************************************************************************************************************************/
	function show_rappel_equipment($item_type, $zoom_level, $zoom_obj) {
		$empty_result = true; // This will be changed to 'false' if the database query returns at least 1 row
		$editable = false;
		$item_type_text = ucwords(str_replace("_"," ",$item_type)); // Build a displayable version of the item_type (i.e. change 'letdown_line' to Letdown Line)
		$item_type == "rope" ? $special_rope_fields = "uses_a, uses_b, forced_retire_date, life_days_remaining," : $special_rope_fields = ""; // Add rope fields to the dB selection query if the item type is 'rope'
		switch($zoom_level) {
			case 'crew':
				// $zoom_obj is a CREW OBJECT
				$editable = check_access("crew_admin",$zoom_obj->get('id')); // Check edit permissions for the current user

				$text = show_header($item_type_text."s", $zoom_obj->get('mfr_serial_num'),$zoom_obj->get('name'));
				
				$query = "SELECT id, crew_affiliation_id, serial_num, mfr_serial_num, status, ".$special_rope_fields."in_service_date, retired_date, retired_category, retired_reason "
						."FROM ".$item_type."_use_view "
						."WHERE crew_affiliation_id = ".$zoom_obj->get('id')." ORDER by status, serial_num";
						
				
				$result = mydb::cxn()->query($query);
				
				if(mydb::cxn()->affected_rows < 1) $text .= "<div class=\"error_msg\">This Crew has no ".$item_type_text."s!</div>\n";
				else {
					$empty_result = false;
					$text .=build_cumulative_charts($item_type,"crew",$zoom_obj->get('id'));
				}
				break;
			
			case 'region':
				// $zoom_obj is a REGION ID (integer)
				$text = show_header($item_type_text."s",$zoom_obj->get('mfr_serial_num'), "Region ".$zoom_obj);
				
				$query = "SELECT id, crew_affiliation_id, serial_num, mfr_serial_num, status, ".$special_rope_fields."in_service_date, retired_date, retired_category, retired_reason "
						."FROM ".$item_type."_use_view "
						."WHERE region = ".$zoom_obj." ORDER by status, serial_num";
				$result = mydb::cxn()->query($query);
				
				if(mydb::cxn()->affected_rows < 1) $text .= "<div class=\"error_msg\">This Region has no ".$item_type_text."s!</div>\n";
				else {
					$empty_result = false;
					$text .=build_cumulative_charts($item_type,"region",$zoom_obj);
				}
				break;
		} // End: switch()
		
		$text .= "<div style=\"width:100%;text-align:left;\"><h2>".$item_type_text."s:</h2></div>\n";
		
		if(!$empty_result) {
			$text .= "<table class=\"alternating_rows\" style=\"margin:0 auto 0 auto; border:2px solid #555555; width:100%;\">\n"
					."<tr>\n";
			if($editable) $text .="	<th>Edit</th>\n";
			$text .="	<th>Serial #</th>\n"
				."	<th>Alternate<br />Serial #</th>\n"
				."	<th>Status</th>\n";
			if($item_type == "rope") {
				$text.="<th>Uses<br>1 End</th>\n"
					."	<th>Uses<br>Total</th>\n";
			}
			$text .= "	<th>Date<br>Manufactured</th>\n"
				."	<th>Date<br>Out-Service</th>\n";
			if($item_type == "rope") {
				$text.="<th>Date<br>Force Retire</th>\n";
			}
			$text .= "	<th>Retirement<br>Category</th>\n"
					."</tr>\n";
			
			$count = 1;
			while($row = $result->fetch_assoc()) {
				if($item_type == "rope") {
					//Determine which rope end has the most uses (display in the rope use column)
					if($row['uses_b'] > $row['uses_a']) $max_end = $row['uses_b']." (B)";
					else $max_end = $row['uses_a']." (A)";
					$total_uses = $row['uses_a'] + $row['uses_b'];
				}
				
				//If item is not yet retired, display "--" as retired_date instead of a blank field
				if($row['retired_date'] == "") $retired_date = "--";
				else $retired_date = $row['retired_date'];
				
				$highlight = "";
				//Set alternating row background depending on whether this row is EVEN or ODD
				$rowclass = "odd";
				if($count % 2 == 0) $rowclass = "evn";
				
				$highlight = "";
				if($item_type == "rope") {
					//Set highlight color for different special conditions
					//Highlight row RED if rope life is expired (and rope is not already retired)
					if((max($row['uses_a'],$row['uses_b']) >= 100) && ($row['status'] == 'in_service')) $highlight = "background-color:#ff6666;";
					elseif(($row['life_days_remaining'] <= 0) && ($row['status'] == 'in_service')) $highlight = "background-color:#ff6666;";
					//Highlight row YELLOW if rope life is nearing end (because of time or # of rappels)
					elseif((max($row['uses_a'],$row['uses_b']) >= 95) && ($row['status'] == 'in_service')) $highlight = "background-color:#ffff66;";
					elseif(($row['life_days_remaining'] <= 30) && ($row['status'] == 'in_service')) $highlight = "background-color:#ffff66;";
					//Highlight row YELLOW if this rope has no in-service date
					if(($row['in_service_date'] == '') && ($row['status'] == 'in_service')) $highlight = "background-color:#ffff66;";
				}
				
				
				$text .= "<tr class=\"".$rowclass."\" style=\"".$highlight."\">\n";
				if($editable) $text .= "<td><a href=\"modify_equipment.php?eq_type=".$item_type."&eq_id=".$row['id']."\">edit</a></td>\n";
				$text .="	<td style=\"text-transform:uppercase;\"><a href=\"view_equipment.php?crew=".$row['crew_affiliation_id']."&eq_type=".$item_type."&eq_id=".$row['id']."\">".$row['serial_num']."</a></td>\n"
						."<td>".$row['mfr_serial_num']."</td>\n"
						."	<td style=\"text-transform:capitalize;\">".str_replace("_"," ",$row['status'])."</td>\n";
				if($item_type == "rope") {
					$text.="<td style=\"text-align:center;\">".$max_end."</td>\n"
						."	<td style=\"text-align:center;\">".$total_uses."</td>\n";
				}
				$text .= "	<td style=\"text-align:center\">".$row['in_service_date']."</td>\n"
						."	<td style=\"text-align:center\">".$retired_date."</td>\n";
				if($item_type == "rope") {
					$text.="<td style=\"text-align:center\">".$row['forced_retire_date']."</td>\n";
				}
				$text .= "	<td style=\"text-transform:capitalize;\">".str_replace('_',' ',$row['retired_category'])."</td>\n"
						."</tr>\n";
				$count++;
			} // End: while()
						
			$text .= "</table>\n\n";
			
			if($item_type == "rope") {
				$text .= "<br /><div class=\"alternating_rows\" style=\"width:100%;text-align:left;\"><h2>Legend:</h2></div>\n";
				$text .= "<table style=\"margin:0 auto 0 auto; border:2px solid #555555; width:100%;\">\n"
						."<tr class=\"evn\"><td style=\"text-align:left;\">&nbsp;This is a normal row</td></tr>\n"
						."<tr class=\"odd\"><td style=\"text-align:left;\">&nbsp;This is a normal row</td></tr>\n"
						."<tr class=\"evn\" style=\"background-color:#ffff66\"><td style=\"text-align:left;\">&nbsp;Yellow ropes are either nearing the end of their life (within 30 days or 5 uses on one end) OR no manufacture date has been entered</td></tr>\n"
						."<tr class=\"odd\" style=\"background-color:#ff6666\"><td style=\"text-align:left;\">&nbsp;Red ropes have met retirement criteria but are still in service</td></tr>\n"
						."</table>\n";
			}
		} // End: if(!$empty_result)
		
		return $text;
	} // End: function show_rappel_equipment()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_one_rappel_equipment_item() ************************************************/
/*******************************************************************************************************************************/
	function show_one_rappel_equipment_item($item) {
		// $item is either a GENIE OBJECT, ROPE OBJECT, or LETDOWN_LINE OBJECT
		$editable = check_access("crew_admin",$item->get('crew_affiliation_id')); // Check edit permissions for the current user
		
		$item_type_text = ucwords(str_replace("_"," ",$item->get('item_type')));
		$text = show_header($item_type_text." :: ".strtoupper($item->get('serial_num')), $item->get('mfr_serial_num'),$item->get('crew_affiliation_name'));

		if($item->get('item_type') == 'rope') $text .= build_one_rope_charts($item->get('id'));

		$highlight_status = "";
		if($item->get('status') == 'retired') $highlight_status = "background-color:#dd1111;";
		elseif($item->get('status') != 'in_service') $highlight_status = "background-color:#ffff66;";
		else $highlight_status = "background-color:#33dd33;";

		$text .= "<div style=\"width:100%;text-align:left;\"><h2>".$item_type_text." Info:</h2></div>\n"
				."<table style=\"text-align:left;\">\n";
		if($editable) $text .= "<tr><td>Edit This ".$item_type_text.":</td><td><a href=\"modify_equipment.php?eq_type=".$item->get('item_type')."&eq_id=".$item->get('id')."\">Edit</a></td></tr>\n";
		$text .= "<tr><td>".$item_type_text." Status:</td><td style=\"".$highlight_status."font-weight:bold;\">&nbsp;".ucwords(str_replace("_"," ",$item->get('status')))."&nbsp;</td></tr>\n";
				
		if($item->get('status') == 'retired') {
			$text .= "<tr><td>Retirement Category:</td><td>".ucwords(str_replace("_"," ",$item->get('retired_category')))."</td></tr>\n"
					."<tr><td>Retirement Explanation:</td><td>".$item->get('retired_reason')."</td></tr>\n"
					."<tr><td>Date of Retirement:</td><td>".$item->get('retired_date')."</td></tr>\n";
		}
		
		if($item->get('item_type') == "rope") {
			$result = mydb::cxn()->query('SELECT uses_a, uses_b, age FROM rope_use_view WHERE id = '.$item->get('id'));
			$row = $result->fetch_assoc();
			$total_uses = $row['uses_a'] + $row['uses_b'];
			$text .= "<tr><td>Age:</td><td>".$row['age']." years</td></tr>\n";
			$text .= "<tr><td>Number of Uses:</td><td>".$total_uses." Total [".$row['uses_a']."A, ".$row['uses_b']."B]</td></tr>\n";
		}
		else {
			$result = mydb::cxn()->query('SELECT uses, age FROM '.$item->get('item_type').'_use_view WHERE id = '.$item->get('id'));
			$row = $result->fetch_assoc();
			$text .= "<tr><td>Age:</td><td>".$row['age']." years</td></tr>\n";
			$text .= "<tr><td>Number of Uses:</td><td>".$row['uses']."</td></tr>\n";
		}
		
		$text .= "<tr><td>Manufacture Date:</td><td>".$item->get('in_service_date')."</td></tr>\n"
				."</table><br>\n";
		
		$text .= "<div style=\"width:100%;text-align:left;\"><h2>Rappels Using This ".$item_type_text.":</h2></div>\n";
		
		$query = "SELECT *,str_to_date(date,\"%m/%d/%Y\") as 'date1' FROM view_rappels WHERE ".$item->get('item_type')."_id = ".$item->get('id')." ORDER BY date1 DESC";
		$result = mydb::cxn()->query($query);

		if(mydb::cxn()->affected_rows > 0) {
			$text .= "<table class=\"alternating_rows\" style=\"width:100%; border:2px solid #555555;\">\n"
					."<th>+</th>"
					."<th>Date</th>"
					."<th>Type</th>"
					."<th>Crew</th>"
					."<th>HRAP</th>"
					."<th>Aircraft</th>"
					."<th>Comments</th></tr>\n";
			
			$current_row = 0;
			while($row = $result->fetch_assoc()) {
				$current_row++;
				
				// Alternate the background color of each row
				if($current_row % 2 == 0) $class = "class=\"evn\"";
				else $class = "class=\"odd\"";
			
				$text .= "<tr ".$class.">\n"
						."<td style=\"text-align:center;\">"
							."<a href=\"view_rappels.php?".$_SERVER['QUERY_STRING']."&op=".$row['operation_id']."\"><img src=\"images/magnifying_glass.png\" style=\"margin:0;\" title=\"View Rappel\"></a></td>"
						."<td>".$row['date']."</td>"
						."<td>".ucwords(str_replace("_"," ",$row['operation_type']))."</td>"
						."<td><a href=\"view_rappels.php?crew=".$row['crew_id']."\">".$row['crew_name']."</a></td>"
						."<td><a href=\"view_rappels.php?hrap=".$row['hrap_id']."\">".$row['hrap_name']."</a></td>"
						."<td>".$row['aircraft_fullname']."</td>"
						."<td>".$row['comments']."</td>\n"
						."</tr>\n\n";
				
			}
			$text .= "</table><br>\n\n";
		}
		else $text .= "This ".$item_type_text." hasn't been used yet.\n";
		
		return $text;
	} // End: function show_one_rappel_equipment_item()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_eq_type_menu() *************************************************************/
/*******************************************************************************************************************************/
	function show_eq_type_menu() {
		$param = "";
		if(isset($_GET['crew']) && ($_GET['crew'] != '')) $param = "crew=".$_GET['crew']."&";
		elseif(isset($_GET['region']) && ($_GET['region'] != '')) $param = "region=".$_GET['region']."&";
		else $text = "<br /><div class=\"error_msg\">You must select a Crew or Region before you can view equipment</div>\n";
		
		if($param != "") {
			$text	="<br><div style=\"font-size:1.2em; font-weight:bold; margin:0 auto 0 auto; text-align:center;\">Please select the equipment you would like to view:<br>\n"
					."<table style=\"margin:0 auto 0 auto;\">\n"
					."	<tr>"
					."		<td style=\"padding:10px;\"><a href=\"".$_SERVER['PHP_SELF']."?".$param."eq_type=rope\"><img src=\"images/rope_segment.jpg\"><br>Ropes</a></td>\n"
					."		<td style=\"padding:10px;\"><a href=\"".$_SERVER['PHP_SELF']."?".$param."eq_type=genie\"><img src=\"images/genie_shaft.jpg\"><br>Genies</a></td>\n"
					."		<td style=\"padding:10px;\"><a href=\"".$_SERVER['PHP_SELF']."?".$param."eq_type=letdown_line\"><img src=\"images/letdown_line.jpg\"><br>Letdown Lines</a></td>\n"
					."	</tr>\n"
					."</table>\n"
					."</div>\n\n";
		}
				
		return $text;
	} // End: function show_eq_type_menu()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: build_cumulative_charts() ******************************************************************/
/*******************************************************************************************************************************/
function build_cumulative_charts($eq_type, $scope, $id) {
	//$scope must be either "crew" or "region"
	switch($eq_type) {
	case 'rope':
		$text =  "<table style=\"width:100%; border:2px solid #555555; vertical-align:top;\">\n"
				."	<tr style=\"background-color:#cccccc; font-size:12px;\"><th style=\"width:50%\">Cause of Retirement</th><th>Raps Before Retirement</th></tr>\n"
				."	<tr style=\"background-color:#aaaaaa;\"><td><script type=\"text/javascript\">".$scope."_rope_retirement_cause_chart(".$id.");</script></td>\n"
				."		<td><script type=\"text/javascript\">".$scope."_rope_raps_before_retirement_chart(".$id.");</script></td></tr>\n"
				."</table><br>\n";
	
		return $text;
		break;
	
	case 'genie':
		
		break;
	} // End: switch($eq_type)
} // End: function build_cumulative_charts

/*******************************************************************************************************************************/
/*********************************** FUNCTION: build_one_rope_charts() *********************************************************/
/*******************************************************************************************************************************/
function build_one_rope_charts($rope_id) {
	  $text =  "<table style=\"width:100%; border:2px solid #555555; vertical-align:top;\">\n"
			  ."	<tr style=\"background-color:#cccccc; font-size:12px;\">\n"
			  ."		<th style=\"width:33%\">Life Span (Time)</th>\n"
			  ."		<th>Genies Used With This Rope</th>\n"
			  ."		<th style=\"width:33%\">Life Span (Use)</th></tr>\n"
			  ."	<tr style=\"background-color:#aaaaaa;\">\n"
			  ."		<td><script type=\"text/javascript\">rope_lifespan_chart('time',".$rope_id.");</script></td>\n"
			  ."		<td style=\"vertical-align:top; background-color:#ffffff;\"><div style=\"font-weight:bold; font-size:12px; background-color:#666666; width:100%; color:#cccccc;\">The most recent genies to be used with this rope are listed below:</div>\n";
		
		$query = "SELECT view_rappels.genie_id, view_rappels.genie_num, view_rappels.operation_id, "
				."str_to_date(view_rappels.date,'%m/%d/%Y') as sort_date, view_rappels.date, genies.crew_affiliation_id "
				."FROM view_rappels INNER JOIN items AS genies ON (genies.id = view_rappels.genie_id AND genies.item_type = 'genie') "
				."WHERE view_rappels.rope_id = ".$rope_id." ORDER BY sort_date DESC LIMIT 9";
		$result = mydb::cxn()->query($query);
		
		$text .= "		<table style=\"width:100%\">\n"
				."		<tr><th>Genie</th><th>Date</th><th>View Rap</th></tr>\n";
		
		if(mydb::cxn()->affected_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$text .= "<tr><td><a href=\"view_equipment.php?crew=".$row['crew_affiliation_id']."&eq_type=genie&eq_id=".$row['genie_id']."\">".strtoupper($row['genie_num'])."</a></td>"
						."	<td>".$row['date']."</td>"
						."	<td><a href=\"view_rappels.php?op=".$row['operation_id']."\"><img src=\"images/magnifying_glass.png\" style=\"margin:0;\" title=\"View Rappel\"></a></td></tr>\n";
			}
		}
		
		$text .= "		</table>\n"
				."	</td>\n"
				."	<td><script type=\"text/javascript\">rope_lifespan_chart('use',".$rope_id.");</script></td></tr>\n"
				."</table><br>\n";
  
	  return $text;
} // End: function build_one_rope_charts()
?>
