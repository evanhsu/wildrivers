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
	
	require_once("includes/constants.php");	// Force 'constants.php' to load, even if it has been previously included by one of the classes above.  Must set SESSION vars AFTER the session_start() declaration.
	require_once("includes/auth_functions.php");
	require_once("includes/check_get_vars.php");
	require_once("includes/make_menu.php");
	require_once("includes/photo_upload_functions.php");
	require_once("includes/aircraft_layouts.php");

	// Determine whether the current user has permission to access this page
	// If user doesn't have permission to add new equipment to the Crew being viewed, reload this page with the user's own crew specified
	if($_SESSION['logged_in'] == 1) {
		if(isset($_GET['crew']) && check_crew($_GET['crew']) && (check_access("crew_admin",$_GET['crew']) != false)) $crew_id = $_GET['crew']; // ACCESS GRANTED
		elseif(check_access("crew_admin")) header('location: '.$_SERVER['PHP_SELF'].'?crew='.$_SESSION['current_user']->get('crew_affiliation_id')); //Redirect to own crew
		else header('location: index.php'); // ACCESS DENIED!
	}
	else {
		// ACCESS DENIED!
		store_intended_location();  //Redirect user back to their intended location after they log in
		header('location: index.php');
	}

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
<meta name="Description" content="The National Rappel Record Website - This page is for adding new equipment information." />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<?php if(isset($_SESSION['mobile']) && ($_SESSION['mobile'] == 1)) echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/mobile.css\" />\n"; ?>

<script type="text/javascript" src="scripts/searchautosuggest/lib/ajax_framework.js"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script language="javascript" src="scripts/popup_calendar/cal2.js">
/*
Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)
Script featured on/available at http://www.dynamicdrive.com/
This notice must stay intact for use
*/
</script>
<script language="javascript" src="scripts/popup_calendar/cal_conf2.js"></script>
<script type="text/javascript">
function updateAbbrev() {
	var crew_id = document.getElementById('crew_affiliation_id').value;
	document.getElementById('eq_abbrev').value = document.getElementById('crew_'+crew_id+'_abbrev').value;
}
</script>

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

if(isset($_GET['eq_type']) && in_array($_GET['eq_type'],array('rope','genie','letdown_line'))) {
	if(isset($_POST['eq_num1'])) {
		try {
			commit_eq($_GET['eq_type']);
			show_add_eq_form($_GET['eq_type'], "Your equipment has been added.");
		} catch (Exception $e) {
			show_add_eq_form($_GET['eq_type'],$e->getMessage());
		}
	}
	else {
		show_add_eq_form($_GET['eq_type'],NULL);
	}
}
else {
	echo show_eq_type_menu();
} // End: if(in_array($_GET['eq_type'],array('rope','genie','letdown_line'))
	
?>

    </div> <!-- End 'content' -->
   	
<div style="clear:both; display:block; visibility:hidden;"></div>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("eq_num2_spry", "integer", {minChars:3, maxChars:7, validateOn:["blur","change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("in_service_date_spry", "date", {format:"mm/dd/yyyy", isRequired:false, validateOn:["blur"]});
//-->
</script>
</body>
</html>

<?php


/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_eq_type_menu() *************************************************************/
/*******************************************************************************************************************************/
	function show_eq_type_menu() {
		if(isset($_GET['crew']) && ($_GET['crew'] != NULL)) $param = "crew=".$_GET['crew']."&";
		
		$text	="<br><div style=\"font-size:1.2em; font-weight:bold; margin:0 auto 0 auto; text-align:center;\">Please select the equipment you would like to add:<br>\n"
				."<table style=\"margin:0 auto 0 auto;\">\n"
				."	<tr>"
				."		<td style=\"padding:10px;\"><a href=\"".$_SERVER['PHP_SELF']."?".$param."eq_type=rope\"><img src=\"images/rope_segment.jpg\"><br>Ropes</a></td>\n"
				."		<td style=\"padding:10px;\"><a href=\"".$_SERVER['PHP_SELF']."?".$param."eq_type=genie\"><img src=\"images/genie_shaft.jpg\"><br>Genies</a></td>\n"
				."		<td style=\"padding:10px;\"><a href=\"".$_SERVER['PHP_SELF']."?".$param."eq_type=letdown_line\"><img src=\"images/letdown_line.jpg\"><br>Letdown Lines</a></td>\n"
				."	</tr>\n"
				."</table>\n"
				."</div>\n\n";
				
		return $text;
	} // End: function show_eq_type_menu()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: show_add_eq_form() **************************************************************/
/*******************************************************************************************************************************/
	function show_add_eq_form($eq_type, $msg = "") {

		$field1 = "";
		$field2 = "";
		$field3 = "";
		$field4 = $_SESSION['current_view']['crew']->get('id');
		
		if(($msg != "") && ($msg != "Your equipment has been added.")) { //If an error was thrown, repopulate the form with the POST'ed values
			$field1 = $_POST['eq_num1'];
			$field2 = $_POST['eq_num2'];
			$field3 = $_POST['in_service_date'];
			$field4 = $_POST['crew_affiliation_id'];
		}
		// Build Crew selection menu AND
		// Build a hidden list of Crew Abbreviations.
		// This list is used to update the equipment # field in the modify_equipment_form when the Ownership is changed
		echo "<form action=\"\" method=\"GET\" id=\"abbrev_list\">\n";
		
		$query = "SELECT DISTINCT id, name, abbrev FROM crews ORDER BY name";
		$result = mydb::cxn()->query($query);
		
		$crew_menu = "";
		while($row = $result->fetch_assoc()) {
			if($field4 == $row['id']) {
				$crew_menu .= "<option value=\"".$row['id']."\" selected=\"selected\">".$row['name']."</option>\n";
				$abbrev = $row['abbrev'];
			}
			elseif(($_SESSION['current_user']->get('account_type') == 'admin')
			|| ($row['id'] == get_academy_id($_SESSION['current_user']->get('region')))) $crew_menu .= "<option value=\"".$row['id']."\">".$row['name']."</option>\n";
				echo "<input type=\"hidden\" name=\"crew_".$row['id']."_abbrev\" id=\"crew_".$row['id']."_abbrev\" value=\"".$row['abbrev']."\">\n";
		}
		echo "</form>\n\n";
	
	
			echo "<br><div class=\"error_msg\">".$msg."</div>\n";
			echo "<form id=\"modify_equipment_form\" method=\"post\" action=\"add_new_equipment.php?crew=".$_GET['crew']."&eq_type=".$eq_type."\" style=\"text-align:center;\">
					<input type=\"hidden\" name=\"eq_type\" value=\"".$eq_type."\">
					<table width=\"500\" style=\"border:2px solid #555555; background-color:#bbbbbb;margin:25px auto 0 auto;\">
						<tr><td colspan=\"2\" style=\"text-align:left; font-size:15px; font-weight:bold;\">Add a New ".ucwords(str_replace("_"," ",$eq_type))."</td></tr>
						<tr>
							<td style=\"width:150px;\">".ucwords(str_replace("_"," ",$eq_type))." #:</td>
							<td style=\"width:auto;text-align:left;\">
								<input type=\"text\" name=\"eq_num1\" id=\"eq_abbrev\" value=\"".$abbrev."\" style=\"width:2.5em; background-color:#bbbbbb; border:none; text-transform:uppercase; text-align:right;\" readonly=\"readonly\"/> -
								<span id=\"eq_num2_spry\">
								<input type=\"text\" name=\"eq_num2\" id=\"eq_num2\" value=\"".$field2."\" style=\"width:4.5em\"/>
									<span class=\"textfieldRequiredMsg\">Required</span>
									<span class=\"textfieldInvalidFormatMsg\">Must be a 3-7 digit number.</span>
									<span class=\"textfieldMinCharsMsg\">Must be 3 digits.</span>
									<span class=\"textfieldMaxCharsMsg\">Must be 7 digits.</span>
								</span>
							</td>
						</tr>";
			if($eq_type == 'rope') {
				echo "<tr><td>Unrecorded uses<br />on End 'A':</td><td style=\"text-align:left\"><input type=\"text\" name=\"use_offset_a\" style=\"width:2.5em\"></td></tr>\n"
					."<tr><td>Unrecorded uses<br />on End 'B':</td><td style=\"text-align:left\"><input type=\"text\" name=\"use_offset_b\" style=\"width:2.5em\"></td></tr>\n";
			}
			else {
				echo "<tr><td>Unrecorded uses:</td><td style=\"text-align:left\"><input type=\"text\" name=\"use_offset\" style=\"width:2.5em\"></td></tr>\n";
			}
								
			echo "		<tr>
							<td>Manufacture Date:</td>
							<td style=\"text-align:left\">
								<span id=\"in_service_date_spry\">
									<input type=\"text\" name=\"in_service_date\" id=\"in_service_date\" style=\"width:5em\" value=\"".$field3."\" onFocus=\"showCal('equipment_in_service_date')\" />
									<span class=\"textfieldRequiredMsg\">Required</span>
									<span class=\"textfieldInvalidFormatMsg\">Date must be: mm/dd/yyyy</span>
								</span>
							</td>
						</tr>
						<tr>
							<td>Ownership:</td>
							<td style=\"text-align:left\"><select name=\"crew_affiliation_id\" id=\"crew_affiliation_id\" onchange=\"updateAbbrev()\">".$crew_menu."</select></td>
						</tr>
						<tr>
							<td colspan=\"2\" style=\"text-align:center\"><input name=\"submit\" type=\"submit\" class=\"form_button\" style=\"width:150px\" value=\"Save\" /></td>
						</tr>
					</table>
				</form>";	
		
	} // End: show_add_eq_form()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: commit_eq() *********************************************************************/
/*******************************************************************************************************************************/
	function commit_eq($eq_type) {
		$eq = new $eq_type;
		$eq->set('serial_num',$_POST['eq_num1']."-".$_POST['eq_num2']);
		$eq->set('crew_affiliation_id',$_POST['crew_affiliation_id']);
		$eq->set('in_service_date',$_POST['in_service_date']);
		$eq->set('status','in_service');
		
		if($eq_type == 'rope') {
			$eq->set('use_offset_a', $_POST['use_offset_a']);
			$eq->set('use_offset_b', $_POST['use_offset_b']);
		}
		else {
			$eq->set('use_offset', $_POST['use_offset']);
		}
		
		$eq->save();
		
		return true;
	} // End: function commit_eq()

?>
