<?php

	session_start();
	require_once("../classes/mydb_class.php");
	require_once("../includes/auth_functions.php");


	function to_fiscal_year($input_date_str) {
		$input_date = new DateTime($input_date_str);
		$date_pieces = explode("/",$input_date_str);

		$calendar_year = $input_date->format('Y');
		$start_of_next_fiscal_year = new DateTime("10/1/".$date_pieces[2]);

		if($input_date >= $start_of_next_fiscal_year) $fiscal_year = (int)$calendar_year += 1;
		else $fiscal_year = $calendar_year;

		//return $fiscal_year;
		return $fiscal_year;
	}
	function first_day_of_fiscal_year($fiscal_year) {
		return "10/1/".((int)$fiscal_year - 1);
	}
	function last_day_of_fiscal_year($fiscal_year) {
		return "9/30/".$fiscal_year;
	}

	if(($_SESSION['logged_in'] == 1) && check_access("budget_helper")) {
		// Access Granted!
		if(check_access("budget_helper_admin")) $is_approver = true; //Allow this user to "approve" wishlist items
		else $is_approver = false;
	}
	else {
		// Access Denied.
		if($_SESSION['logged_in'] != 1) $_SESSION['intended_location'] = $_SERVER['PHP_SELF'];
		header('location: http://www.siskiyourappellers.com/admin/index.php');
	}

	$_SESSION['split_qty'] = 10;
	$err_msg = "";
	$content = "";

	$_SESSION['cardholders'] = array('dan', 'tim', 'larrimore', 'jonah', 'nick', 'other', 'wishlist');

	if(isset($_GET['sort_by'])) $_SESSION['sort_req_view_by'] = $_GET['sort_by'];
	elseif (!isset($_SESSION['sort_req_view_by'])) $_SESSION['sort_req_view_by'] = "date";

	if(isset($_GET['year'])){
		$_SESSION['requisition_start_date'] = first_day_of_fiscal_year($_GET['year']);
		$_SESSION['requisition_end_date'] = last_day_of_fiscal_year($_GET['year']);
		$_SESSION['requisition_year'] = $_GET['year'];
	}
	else {
		if(isset($_GET['start_date']) && ($_GET['start_date'] != '')) $_SESSION['requisition_start_date'] = $_GET['start_date'];
		else $_SESSION['requisition_start_date'] = first_day_of_fiscal_year(to_fiscal_year(date('m/d/Y'))); // Default to the first day of the current FISCAL year

		if(isset($_GET['end_date']) && ($_GET['end_date'] != '')) $_SESSION['requisition_end_date'] = $_GET['end_date'];
		else $_SESSION['requisition_end_date'] = last_day_of_fiscal_year(to_fiscal_year(date('m/d/Y'))); // Default to the last day of the current FISCAL year

		$_SESSION['requisition_year'] = to_fiscal_year(date('m/d/Y'));
	}

	if(isset($_GET['update_filters']) && ($_GET['update_filters'] == 'true')) {
		$_SESSION['filter_cards_to_include'] = array(); //Clear all purchase cards from the include list
		foreach($_SESSION['cardholders'] as $cardholder) {
			if(isset($_GET['include_card_'.$cardholder]) && ($_GET['include_card_'.$cardholder] == true)) {
				array_push($_SESSION['filter_cards_to_include'],$cardholder);
			}
		}
	}
	else {
		if(!isset($_SESSION['filter_cards_to_include'])) $_SESSION['filter_cards_to_include'] = $_SESSION['cardholders'];
	}

	 !isset($_GET['function'])?$function = '':$function = $_GET['function'];

	 try {
	   switch($function) {
		  case 'view_requisition':
			  $content = view_requisition();
			  break;
/*
		  case 'delete_requisition':
			  if(isset($_POST['id']) && ($_POST['id'] != '')) $content = delete_requisition($_POST['id']);
			  $content .= show_budget_summary();
			  break;
*/
                  case 'view_wishlist':
                          $content = show_budget_summary("wishlist");
                          break;
		  default:
			  //if(isset($_POST['order_total'])) $content = commit_requisition();
			  $content = show_budget_summary();
			  break;
	   } // END switch()
	 } catch (Exception $e) {
		$content = $e->getMessage();
	 }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Budget :: Siskiyou Rappel Crew</title>

	<?php include("../includes/basehref.html"); ?>

	<meta name="Author" content="Evan Hsu" />
	<meta name="Keywords" content="phonelist, phone, contact, crewmembers, people, email, address, mail, fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
	<meta name="Description" content="View & Update the crew budget" />

	<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
	<link rel="stylesheet" type="text/css" href="styles/menu.css" />
    <link rel="stylesheet" type="text/css" href="styles/inventory.css" />

    <script type="application/javascript">
	function update_totals() {
		//var percent_field; //Remove this field
		var amount_field;
		var amount_1_field;
		var order_total_field;
		var id;
		var form_name = 'req_form'

		order_total_field = document.forms[form_name].elements['order_total'];
		amount_1_field = document.forms[form_name].elements['amount_1'];
		
		//if(amount_1_field.value == "") {
			// If the first line item has not yet been populated with a dollar amount, copy the order total into that field.
			// THIS WILL OVERWRITE EXISTING VALUES IN THE 'TOTAL' FIELD FOR THE 1ST LINE ITEM
			amount_1_field.value = order_total_field.value;
		//}
			
/*	
		for(id=1;id<=10;id++) {
			//percent_field = document.forms[form_name].elements['percent_'+id];
			amount_field = document.forms[form_name].elements['amount_'+id];

			if(percent_field.value != '') {
				amount_field.value = Math.round(req_form.order_total.value * percent_field.value * 100) / 10000;
			}
			else amount_field.value = '';
		}
*/
		check_total_math();
	}

	function update_percent(id) {
		//THIS FUNCTION IS NO LONGER USED - the 'percent' field has been removed
		//Update the 'percent' field when the 'amount' field is changed
		var form_name = 'req_form'
		
		var percent_field = document.forms[form_name].elements['percent_'+id];
		var amount_field = document.forms[form_name].elements['amount_'+id];
		percent_field.value = Math.round(parseFloat(amount_field.value) / parseFloat(req_form.order_total.value) * 10000) / 100;
	}

	function check_total_math() {
		var form_name = 'req_form';
		var submit_button = document.forms[form_name].elements['save_req_button'];
		//var percent_field;
		var amount_field;
                var total_field = document.forms[form_name].elements['order_total'];
		var split_total = 0;
		var id;

		for(id=1;id<=10;id++) {
			//percent_field = document.forms[form_name].elements['percent_'+id];
			amount_field = document.forms[form_name].elements['amount_'+id];

			if(isNaN(parseFloat(amount_field.value))) {
				amount_field.value = "";
			}
			else {
				amount_field.value = parseFloat(amount_field.value).toFixed(2);
				split_total += parseFloat(amount_field.value); // Maintain a running total
			}
		}
		split_total = +(split_total.toFixed(2));
		if(split_total != parseFloat(total_field.value)) {
			document.getElementById("split_error_div").innerHTML = "Your line items ($"+split_total+") don't add up to your order total! ($"+(total_field.value)+")";
			submit_button.disabled = true;
		}
		else {
			document.getElementById("split_error_div").innerHTML = "";
			submit_button.disabled = false;
		}
	}

	function validateForm()
	{
		//This function simply checks to make sure that the required form fields are not blank. No other validation applied.
		var msg = "";
		var required_fields = ['vendor_info', 'date', 'description', 'order_total'];
		for(var i=0;i<4;i++) {
			if(document.forms['req_form'].elements[required_fields[i]].value == "") {
				msg += required_fields[i] + " can't be blank. ";
			}
		}
		
		if(msg != "") {
			document.getElementById("split_error_div").innerHTML = msg;
			return false;
		}
		else {
			return true;
		}
	}

	function confirmDelete()
	{
	var agree=confirm("Are you sure you want to delete this entry?");
	if (agree)
		return true ;
	else
		return false ;
	}
	
	function deleteReqAttachment(reqId, attachmentId) {
		var agree=confirm("Are you sure you want to delete this attachment?");
		if (agree) {
			// Set the random number to add to URL request to prevent the browser from caching the results
			var nocache = Math.random();
			http.open('get', 'scripts/budget_helper_ajax/budget_helper_delete_attachment.php?req_id='+reqId+'&attachment_num='+attachmentId+'&nocache='+nocache);
			
			http.send(null);
			alert("Attachment deleted!");
			return true;
		}
		else {
			return false;
		}
	}

	function select_all_checkboxes() {
		var inputs = document.getElementsByTagName("input");
		for(var i=0; i < inputs.length;i++) {
			if(inputs[i].type === 'checkbox') inputs[i].checked = true;
		}
	}

	function deselect_all_checkboxes() {
		var inputs = document.getElementsByTagName("input");
		for(var i=0; i < inputs.length;i++) {
			if(inputs[i].type === 'checkbox') inputs[i].checked = false;
		}
	}	
	</script>

    <script language="javascript" src="scripts/budget_helper_ajax/budget_helper_ajax.js"></script>
    <script language="javascript" src="scripts/popup_calendar/cal.js">

	/*
	Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)
	Script featured on/available at http://www.dynamicdrive.com/
	This notice must stay intact for use
	*/
    </script>
    <script language="javascript" src="scripts/popup_calendar/cal_conf.js"></script>
    <!-- <script language="javascript" src="scripts/jquery-1.11.3.min.js" /></script>
    <script language="javascript" src="scripts/jquery-ui-1.11.4/jquery-ui.js" /></script>-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

    <?php
    if($is_approver) {
        print "<script language=\"javascript\" src=\"scripts/make_wishlist_sortable.js\"></script>\n";
    }
    ?>

    <style>
	.highlight {
		color:#000000;
		background-color:#FF0;
	}
	.lowlight {
		background-color:none;
	}
	</style>
</head>

<body>
<div id="wrapper" style="min-height:150px;">
 <div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none" alt="Scroll down..." /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Siskiyou Rappel Crew - Budget Helper</div>
    </div>

 <?php include("../includes/menu.php"); ?>
	</div><!-- END 'wrapper' -->
    <div id="wrapper" style="width:95%;">
    <div id="content" style="text-align:center">
	<br />
 <?php
 	if(isset($_SESSION['status_msg']) && ($_SESSION['status_msg'] != '')) {
		print "<div style=\"font-size:1.5em;color:#dd3333;\">".$_SESSION['status_msg']."</div><br /><br />\n\n";
		$_SESSION['status_msg'] = '';
	}
    print $content;
  ?>


    </div> <!-- End 'content' -->
</div><!-- end 'wrapper'-->


<?php include("../includes/footer.html"); ?>

</body>
</html>

<?php
/*********************************************************************************************************/
function view_requisition() {
	$attachment_labels = array("Receipt", "Supply Order", "Other");
	if($_GET['wishlist'] == 'true') $wishlist = true;
	else $wishlist = false;

	
	if(isset($_GET['id']) && $_GET['id'] != 'new') {
		$requisition_id = $_GET['id'];
		populate_requisition_by_id($requisition_id);

	}
	else {
		$_SESSION['form_memory']['requisition'] = array();
		$requisition_id = "new";
	}

	for($i=0;$i<$_SESSION['split_qty'];$i++) {
		if(!isset($_SESSION['form_memory']['requisition'][$i]['amount']) || ($_SESSION['form_memory']['requisition'][$i]['amount']=='')) {
			$_SESSION['form_memory']['requisition'][$i] = array('s_number'=>'',
										'charge_code'=>'',
										'override'=>'',
										'percent'=>'',
										'order_total'=>'',
										'amount'=>'',
										'split_comments'=>'',
										'split_received'=>'',
										'split_reconciled'=>'');
			if($i==0) {
				$_SESSION['form_memory']['requisition'][$i]['charge_code'] = 'WFPR10';
				$_SESSION['form_memory']['requisition'][$i]['override'] = '0610';
				$_SESSION['form_memory']['requisition'][$i]['percent'] = '100';
				$_SESSION['form_memory']['requisition'][$i]['added_by'] = $_SESSION['user_real_name'];

			}
			else {
				$_SESSION['form_memory']['requisition'][$i]['charge_code'] = '';
				$_SESSION['form_memory']['requisition'][$i]['override'] == '';
				$_SESSION['form_memory']['requisition'][$i]['percent'] = '';
			}
			$_SESSION['form_memory']['requisition'][$i]['amount'] = '';
		}

		elseif($_SESSION['form_memory']['requisition'][$i]['order_total'] > 0) {
			$_SESSION['form_memory']['requisition'][$i]['percent'] = number_format($_SESSION['form_memory']['requisition'][$i]['amount'] / $_SESSION['form_memory']['requisition'][$i]['order_total'] * 100,2,'.','');
		}
		else {
			$_SESSION['form_memory']['requisition'][$i]['percent'] = 0;
		}
	}

	// Display a requisition form
	$content = "<h2>Requisition</h2><br /><br /><br />\n";
	if($_SESSION['form_memory']['requisition'][0]['card_used'] == 'wishlist') {
		$content .="<a href=\"admin/budget_helper.php?function=view_wishlist\">Back to Wishlist</a><br>\n";
	}
	else {
		$content .="<a href=\"admin/budget_helper.php\">Back to Requisition Summary</a><br>\n";
	}
	
		$content .= "<form enctype=\"multipart/form-data\" action=\"admin/process_forms.php\" onSubmit=\"return validateForm()\" method=\"post\" name=\"req_form\">\n"
			. "<input type=\"hidden\" name=\"form_name\" value=\"req_form\">\n"
			. "<input type=\"hidden\" name=\"id\" value=\"".$requisition_id."\">\n"
			. "<table style=\"width:50em;margin:0 auto 0 auto;\">\n"
			. "<tr><td>Vendor/Source Information:</td><td><textarea name=\"vendor_info\" style=\"width:100%;height:5em;\">".htmlentities($_SESSION['form_memory']['requisition'][0]['vendor_info'])."</textarea></td></tr>\n"
			. "<tr><td>Entered By:</td><td style=\"text-align:left;\"><input type=\"text\" style=\"width:15em;\" name=\"added_by\" value=\"".$_SESSION['form_memory']['requisition'][0]['added_by']."\" readonly></td></tr>\n"
			. "<tr><td colspan=\"2\"><hr style=\"border:none; color:#555555; background-color:#555555; width:100%; height:2px;\"></td></tr>\n"
			. "<tr><td style=\"width:11em;text-align:right\">Order Date:</td><td style=\"text-align:left;\"><input type=\"text\" style=\"width:5em;\" name=\"date\" value=\"".$_SESSION['form_memory']['requisition'][0]['date']."\"><small><a href=\"javascript:showCal('requisition_date')\">Select Date</a></small></td></tr>\n"
			. "<tr><td style=\"text-align:right\">Order Description:</td><td style=\"text-align:left;\"><input type=\"text\" style=\"width:100%;\" name=\"description\" value=\"".htmlentities($_SESSION['form_memory']['requisition'][0]['description'])."\"></td></tr>\n"
			. "<tr><td style=\"text-align:right\">Order Total:</td><td style=\"text-align:left;\"><input type=\"text\" name=\"order_total\" value=\"".$_SESSION['form_memory']['requisition'][0]['order_total']."\" style=\"width:5em;\" onInput=\"update_totals();\"></td></tr>\n";

			$content .= "<tr><td style=\"text-align:right\">Which Card?</td>\n"
			. "    <td style=\"text-align:left;\">";

			foreach($_SESSION['cardholders'] as $cardholder) {
				$content .= "    <input type=\"radio\" name=\"card_used\" value=\"".$cardholder."\"";
				if($_SESSION['form_memory']['requisition'][0]['card_used'] == $cardholder) $content .= " CHECKED";
				$content .= ">".ucwords(str_replace("_"," ",$cardholder))." &nbsp;&nbsp; ";
			}

			$content .= "</td></tr>\n"
			. "<tr><td><br /></td></tr>\n";
		
			
			$i = 0;
			foreach($attachment_labels as $attachment_label) {
				$i++;
				$content .= "<tr><td style=\"text-align:right\">".$attachment_label.":</td>\n"
						.	"<td style=\"text-align:left; width:100%;\">\n"
						.	"<table><tr><td>";

				if($_SESSION['form_memory']['requisition'][0]['attachment'.$i] != '') {
						$content .= "<td style=\"text-align:left;width:150px;\">\n"
								.	"<a href=\"admin/".$_SESSION['form_memory']['requisition'][0]['attachment'.$i]."\" target=\"_new\"><img src=\"images/magnifying_glass.png\"> View Attachment</a></td>";
						
						$content .=  "<td style=\"text-align:right;\">"
									.	"<a href=\"".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."\" onClick=\"deleteReqAttachment(".$_SESSION['form_memory']['requisition'][0]['id'].",".$i.");\"><img src=\"images/trash.jpg\" />Delete</span>";

				}
				else {
					  $content .= "<td style=\"text-align:left;\">\n"
							  . "	<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"5000000\" />\n"
							  . "	<input name=\"uploadedfile".$i."\" type=\"file\">\n";
				}
				
				$content .= "</td></tr></table>\n";
			} //End foreach()
			
	
	$content .=  "<tr><td colspan=\"2\"><hr style=\"border:none; color:#555555; background-color:#555555; width:100%; height:2px;\"></td></tr>\n"
			. "<tr><td>Split the Bill:</td>\n"
			. "    <td><table>\n"
			. "				<tr>	<td>Comments</td>\n"
			. "						<td>S-Number</td>\n"
			. "						<td>Charge Code</td>\n"
			. "						<td colspan=\"2\">Override</td>\n"
//			. "						<td colspan=\"2\">% to Charge</td>\n"
			. "						<td>Total</td>\n"
			. "						<td><img src=\"images/received.png\"></td>\n"
			. "						<td><img src=\"images/reconciled.png\"</td></tr>\n";

	for($i=0;$i<$_SESSION['split_qty'];$i++) {
		$content .= "<tr><td><input type=\"text\" name=\"split_comments_".($i+1)."\" value=\"".$_SESSION['form_memory']['requisition'][$i]['split_comments']."\" style=\"width:auto;\"></td>\n"
			. "<td><input type=\"text\" name=\"s_number_".($i+1)."\" value=\"".$_SESSION['form_memory']['requisition'][$i]['s_number']."\" style=\"width:4.5em;\" style=\"width:5em;\"></td>\n"
			. "<td><input type=\"text\" name=\"charge_code_".($i+1)."\" value=\"".$_SESSION['form_memory']['requisition'][$i]['charge_code']."\" style=\"width:5em;\"></td>\n"
			. "<td><input type=\"text\" name=\"override_".($i+1)."\" value=\"".$_SESSION['form_memory']['requisition'][$i]['override']."\" style=\"width:4em;\"></td>\n"
//			. "<td><input type=\"text\" name=\"percent_".($i+1)."\" value=\"".$_SESSION['form_memory']['requisition'][$i]['percent']."\" style=\"width:4em;\" onChange=\"update_totals();\"></td>\n"
			. "<td>=</td>\n"
			. "<td><input type=\"text\" name=\"amount_".($i+1)."\" value=\"".$_SESSION['form_memory']['requisition'][$i]['amount']."\" style=\"width:5em;\" onChange=\"check_total_math();\"></td>\n"
			. "<td><input type=\"checkbox\" name=\"split_received_".($i+1)."\" value=\"checked\" ".$_SESSION['form_memory']['requisition'][$i]['split_received']."></td>\n"
			. "<td><input type=\"checkbox\" name=\"split_reconciled_".($i+1)."\" value=\"checked\" ".$_SESSION['form_memory']['requisition'][$i]['split_reconciled']."></td></tr>\n";
	}

	$content .= "</table></td></tr>\n"
				."<tr><td>&nbsp;</td><td style=\"text-align:left;\">"
				."<span><input type=\"submit\" id=\"save_req_button\" value=\"Save\" style=\"width:6em; height:2.5em; font-weight:bold;\"></span>\n"
				."<span id=\"split_error_div\" style=\"font-size:1.4em;color:#cc5555;font-weight:bold;\"></span>"
				."</td></tr>\n</table></form>\n";

	if(isset($_SESSION['form_memory']['requisition'][0]['id']) && ($_SESSION['form_memory']['requisition'][0]['id'] != '')) {
		$content .=  "<table style=\"width:100%;margin:0 auto 0 auto;\"><tr>"
					."<td style=\"text-align:right;\"><form action=\"admin/process_forms.php\" method=\"post\">\n"
					."<input type=\"hidden\" name=\"form_name\" value=\"req_delete\">\n"
					."<input type=\"hidden\" name=\"id\" value=\"".$_SESSION['form_memory']['requisition'][0]['id']."\">\n"
					."<input type=\"submit\" style=\"font-size:0.8em;height:2.5em;\" value=\"Delete from Database\" onClick=\"return confirmDelete()\"></form></td></tr></table>\n";
	}

//	$content .= "<div id=\"split_error_div\" style=\"font-size:1.4em;color:#cc5555;font-weight:bold;\"></div>\n";

	return $content;
} // END function view_requisition()

/*********************************************************************************************************/
function show_budget_summary($type="") {

	try {
		if($type == "wishlist") {
			$requisition_array = get_wishlist_requisitions();
			$content = display_wishlist($requisition_array);
		}
		else {
			$requisition_array = get_requisitions();
			$content = display_requisitions($requisition_array);
		}
	} catch (Exception $e) {
		$content = $e->getMessage();
	}

	return $content;
}

/*********************************************************************************************************/
function get_wishlist_requisitions() {
	$query = "SELECT	requisitions.id,
				requisitions.priority,
				requisitions.added_by,
				requisitions.approved_by,
				requisitions.date,
				requisitions.vendor_info,
				requisitions.description,
				requisitions.amount as order_total,
				requisitions.card_used,
				requisitions.attachment1,
				requisitions.attachment2,
				requisitions.attachment3 
		FROM 	requisitions 
		WHERE 	requisitions.card_used = 'wishlist'
		ORDER BY requisitions.priority";	

	$result = mydb::cxn()->query($query);

	if(mydb::cxn()->error != '') throw new Exception('There was a problem retrieving the requisition list from the database.<br />\n'.mydb::cxn()->error);

	while($row = $result->fetch_assoc()) {
		$requisition_array[] = $row;
	}

	return $requisition_array;

}//End get_wishlist_requisitions()

/*********************************************************************************************************/
function get_requisitions() {

// Build a database query based on a user-specified sort field
// Run display_requisition_summary to step through each row and display the data

	$filter = "";
	$inverse_filter = "";
	$include_others = "";
	$cards_to_omit = array_diff($_SESSION['cardholders'], array('other'));
	
	foreach($_SESSION['filter_cards_to_include'] as $card_to_include) {
		if($filter != "") $filter .= "OR ";
		$filter .= "card_used = '".$card_to_include."' ";
	}
	
	foreach($cards_to_omit as $card_to_omit) {
		if($inverse_filter != "") $inverse_filter .= "AND ";
		$inverse_filter .= "card_used != '".$card_to_omit."' ";
	}
	//if($inverse_filter == "") $inverse_filter

	if(in_array("other", $_SESSION['filter_cards_to_include'])) {
		$include_others .= " OR (".$inverse_filter.") ";
	}
	
	if($filter == "" && $include_others == "") $filter = "card_used = NULL"; // This shouldn't return any results from the dB
	
	$query = "SELECT	requisitions.id,
						requisitions.date,
						requisitions.vendor_info,
						requisitions.description,
						requisitions.amount as order_total,
						requisitions.card_used,
						requisitions_split.id as split_id,
						requisitions_split.comments as split_comments,
						requisitions_split.amount,
						requisitions_split.s_number,
						CONCAT(requisitions_split.s_number,' (',requisitions_split.charge_code,')') as s_number_distinct,
						requisitions_split.charge_code,
						requisitions_split.override,
						requisitions_split.received as split_received,
						requisitions_split.reconciled as split_reconciled,
						requisitions.attachment1,
						requisitions.attachment2,
						requisitions.attachment3 ";

	$sort_by = $_SESSION['sort_req_view_by'];

	switch($sort_by) {
		case "id":
			$order_by = " ORDER BY id";
			break;
		case "vendor_info":
			$order_by = " ORDER BY vendor_info, date, card_used";
			break;
		case "amount":
			$order_by = " ORDER BY amount, date, card_used";
			break;
		case "override":
			$order_by = " ORDER BY override, charge_code, s_number, date";
			break;
		case "s_number_distinct":
			$order_by ="	AND requisitions_split.s_number != ''
							ORDER BY s_number, charge_code, date";
			break;
		case "charge_code":
			$order_by = " ORDER BY charge_code, s_number, date";
			break;
		case "card_used":
			$order_by = " ORDER BY card_used, date, vendor_info, charge_code, s_number, amount";
			break;
		case "card_used_and_charge_code":
			$order_by = " ORDER BY card_used, date, vendor_info, charge_code, s_number, amount";
			break;
		case "date":
		default:
			$order_by = " ORDER BY date, id, charge_code";
			$_SESSION['sort_req_view_by'] = "date";
			break;
	}

	$query .= "FROM 	requisitions left outer join requisitions_split
			   ON 		requisitions.id = requisitions_split.requisition_id
			   WHERE 	requisitions.date >= str_to_date('".$_SESSION['requisition_start_date']."','%m/%d/%Y') && requisitions.date <= str_to_date('".$_SESSION['requisition_end_date']."','%m/%d/%Y') AND (" . $filter . $include_others .") ". $order_by;

	$result = mydb::cxn()->query($query);

	if(mydb::cxn()->error != '') throw new Exception('There was a problem retrieving the requisition list from the database.<br />\n'.mydb::cxn()->error);

	while($row = $result->fetch_assoc()) {
		$requisition_array[] = $row;
	}
	
	/*
	echo $query."<br>";
	print_r($_SESSION['filter_cards_to_include']);
	echo "<br />";
	print_r($_SESSION['cardholders']);
	echo "<br />";
	print_r($cards_to_omit);
	*/
	return $requisition_array;
}

/*********************************************************************************************************/
function display_wishlist($requisition_array) {

// Display a summary of requisitions from the wishlist
// This function requires that get_wishlist_requisitions() has been previously run to submit the initial database query

	if(check_access("budget_helper_admin")) $is_approver = true; //Allow this user to "approve" wishlist items
	else $is_approver = false;

	$content = "";
	$sort_by = $_SESSION['sort_req_view_by'];
	$content .= "<span class=\"highlight1\" style=\"display:block\">Requisitions are sorted by " . $sort_by . "</span>\n";
	$content .= "<a href=\"admin/budget_helper.php?function=view_requisition\">Add New Wishlist Item</a><br />\n";


	$content .= "<table><tr><td><button onclick=\"javascript:window.location.href='admin/budget_helper.php'\">View Requisitions
</button></td></tr></table><br />";


	$content .= "<table style=\"width:100%\" id=\"wishlist_table\">\n";

	//INITIALIZE PHP VARIABLES
	$row_count = 0;
	$table_headers = "<thead><tr>	<th>#</th>
					<th>Date Added</th>
					<th>Vendor</th>
					<th>Description</th>
					<th>Added By</th>
					<th>Amount</th>
					<th>Approved</th>
			</tr></thead>\n";


	$class = "'odd'";
	$last_requisition_id = -1;
	$row_count = 0;
	$amount_sum = 0;

	$content .= $table_headers;
	$content .= "<tbody>\n";

	if(sizeof($requisition_array) < 1) {
		$content .= "<tr><td colspan=\"12\">There's nothing in the wishlist!</td></tr></tbody></table>\n";
		$_SESSION['form_memory']['requisition'] = array();
		return $content;
	}
	
	foreach($requisition_array as $row) {
		$row_count++;

		
		// Shorten notes to 50 characters to save space on screen
		$full_desc = htmlentities($row['description']);
		$short_desc = trim(substr($full_desc,0,47));
		if(strlen($full_desc) > strlen($short_desc)) $short_desc .= "...";

		// Shorten Vendor Info to 30 characters to save space on screen
		$full_vendor = htmlentities($row['vendor_info']);
		$short_vendor = trim(substr($full_vendor,0,25));
		if(strlen($full_vendor) > strlen($short_vendor)) $short_vendor .= "...";

		$content .= "<tr id=\"".$row['id']."\" class=".$class." style=\"height:2em;\" "
		  	. "onMouseOver=\"document.getElementById('".$row['id']."').className='highlight';\" "
		  	. "onMouseOut=\"document.getElementById('".$row['id']."').className=".$class.";\">\n";

		$amount_sum += $row['order_total'];
		if($is_approver) $approval_script = "onClick=\"setBudgetItemStatus(".$row['id'].",'approved_".$row['id']."','".$_SESSION['user_real_name']."')\" ";
		else $approval_script = "";

		$content .= "<td class=\"priority\">".$row['priority']."</td>"
			. "<td><a href=\"".$_SERVER['PHP_SELF']."?function=view_requisition&id=".$row['id']."\">".$row['date']."</a></td>"
			. "<td style=\"text-align:left;\"><a href=\"".$_SERVER['PHP_SELF']."?function=view_requisition&id=".$row['id']."\">".$short_vendor."</a></td>"
			. "<td style=\"text-align:left;\"><a href=\"".$_SERVER['PHP_SELF']."?function=view_requisition&id=".$row['id']."\">".$short_desc."</a></td>"
			. "<td>".$row['added_by']."</td>"
			. "<td style=\"text-align:right; \">$".number_format($row['order_total'],2)."</td>"
			. "<td>";
		if(($row['approved_by'] != "") && ($row['approved_by'] != null)) {
			$content .= "<img id=\"approved_".$row['id']."\" src=\"images/approved_yes.png\" ".$approval_script."title=\"Approved by ".$row['approved_by']."\">";
		}
		else {
			$content .= "<img id=\"approved_".$row['id']."\" src=\"images/approved_no.png\" ".$approval_script."title=\"Not approved\">";
		}
		$content .= "</td></tr>\n";

	} //END foreach($requisition_array as $row)

	$content .= "<tr style=\"background-color:none;\"><td style=\"text-align:right\" colspan=\"5\">Total</td>"
		. " <td style=\"text-align:right;\" >$".number_format($amount_sum,2)."</td>"
		. " <td colspan=\"3\">&nbsp;</td></tr></tbody>\n"
		.  "</table>";

	$_SESSION['form_memory']['requisition'] = array();

	return $content;
}//END show_wishlist

/*********************************************************************************************************/
function display_requisitions($requisition_array) {

// Display a summary of requisitions from the specified time period, sorted by a user-specified field
// This function requires that get_requisitions() has been previously run to submit the initial database query
	$content = "";

	$sort_by = $_SESSION['sort_req_view_by'];
	$content .= "<span class=\"highlight1\" style=\"display:block\">Requisitions are sorted by " . $sort_by . "</span>\n";
	$content .= "<a href=\"admin/budget_helper.php?function=view_requisition\">Add New Requisition</a><br />\n";

	//Create dropdown menu to select year
	$content .= "<table><tr><td>";
	$result = mydb::cxn()->query("SELECT DATE_FORMAT(date,'%m/%d/%Y') as datef FROM requisitions ORDER BY date ASC LIMIT 1"); // Get earliest date
	
	$content .= "<form action=\"".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."\" method=\"GET\">"
			   ."<select name=\"year\">\n";
	$row = $result->fetch_assoc();
	$earliest_fiscal_year = to_fiscal_year($row['datef']);
	$current_fiscal_year = to_fiscal_year(date('m/d/Y'));

	for($fiscal_year = $current_fiscal_year; $fiscal_year >= $earliest_fiscal_year; $fiscal_year--) {
		if($fiscal_year == $_SESSION['requisition_year']) $content .= "<option value=\"".$fiscal_year."\" SELECTED>".$fiscal_year."</option>\n";
		else $content .= "<option value=\"".$fiscal_year."\">".$fiscal_year."</option>\n";
	}

	$content .=  "</select>"
				."<input type=\"submit\" value=\"View\">"
				."</form>\n\n"
				."</td></tr>\n"
		. "<tr><td><button onclick=\"javascript:window.location.href='admin/budget_helper.php?function=view_wishlist'\">Wishlist</button></td></tr></table><br />";


	//Create 'Restrict by date' & 'filter' menu
	$content .= "<form action=\"".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."\" method=\"GET\" name=\"req_view_filter_form\">"
				."<table><tr><td>Start Date:</td><td>&nbsp;</td><td>End Date:</td><td>&nbsp;</td><td style=\"width:10em;\"></td><td style=\"text-align:left;background-color:#e0e0e0;font-weight:bold;font-size:14px;\">Filter:"
				."<button onclick=\"javascript: deselect_all_checkboxes();\">Deselect All</button> <button onclick=\"select_all_checkboxes();\">Select All</button> </td></tr>\n"
				."<tr>	<td><input type=\"text\" style=\"width:5em;\" name=\"start_date\" value=\"".$_SESSION['requisition_start_date']."\"><br /><small><a href=\"javascript:showCal('requisition_view_start_date')\">Select Date</a></small></td><td>>></td>"
				."		<td><input type=\"text\" style=\"width:5em;\" name=\"end_date\" value=\"".$_SESSION['requisition_end_date']."\"><br /><small><a href=\"javascript:showCal('requisition_view_end_date')\">Select Date</a></small></td><td><input type=\"submit\" value=\"View\"></td>"
				."<td>&nbsp;</td>"

				."<td>";

	foreach($_SESSION['cardholders'] as $cardholder) {
		if($cardholder != "wishlist") {//Don't show a 'Wishlist' checkbox because the wishlist view needs special formatting
			$content .= "<input type=\"checkbox\" name=\"include_card_".$cardholder."\" value=\"true\" ";
			if(in_array($cardholder,$_SESSION['filter_cards_to_include'])) $content .= "checked";
			$content .= ">".ucwords(str_replace("_"," ",$cardholder));
			if(!in_array($cardholder,array("project_fuel","wishlist","other"))) $content .= "'s Card";
			$content .= "&nbsp; &nbsp; \n";
		}
	}

	$content .= "</td></tr>\n"
				."</table>\n"

				."<input type=\"hidden\" name=\"update_filters\" value=\"true\"></form>\n";

	$content .= "<table style=\"width:100%\">\n";

	//INITIALIZE PHP VARIABLES
	$rest_of_query = '';
	if(isset($_GET['start_date']) && ($_GET['start_date'] != '')) $rest_of_query .= '&start_date='.$_GET['start_date'];
	if(isset($_GET['end_date']) && ($_GET['end_date'] != '')) $rest_of_query .= '&end_date='.$_GET['end_date'];
	foreach($_SESSION['cardholders'] as $cardholder) {
		if(isset($_GET['include_card_'.$cardholder]) && ($_GET['include_card_'.$cardholder] != '')) $rest_of_query .= '&include_card_'.$cardholder.'=true';
	}

	$row_count = 0;
	$last_cat_title = "~`$(*#)*@(!-_~}}"; //Junk - an unlikely initial value
	$table_headers = "	<tr><th><a href=\"" . $_SERVER['PHP_SELF'] . "?&sort_by=id".$rest_of_query."\">#</a></th>
							<th><a href=\"" . $_SERVER['PHP_SELF'] . "?&sort_by=date".$rest_of_query."\">Date</a></th>
							<th><a href=\"" . $_SERVER['PHP_SELF'] . "?&sort_by=vendor_info".$rest_of_query."\">Vendor</a></th>
							<th>Description</th>
							<th><a href=\"" . $_SERVER['PHP_SELF'] . "?&sort_by=card_used".$rest_of_query."\">Card</a></th>
							<th><a href=\"" . $_SERVER['PHP_SELF'] . "?&sort_by=s_number_distinct".$rest_of_query."\">S-Number</a></th>
							<th><a href=\"" . $_SERVER['PHP_SELF'] . "?&sort_by=charge_code".$rest_of_query."\">Charge Code</a></th>
							<th><a href=\"" . $_SERVER['PHP_SELF'] . "?&sort_by=override".$rest_of_query."\">Override</a></th>
							<th><a href=\"" . $_SERVER['PHP_SELF'] . "?&sort_by=amount".$rest_of_query."\">Amount</a></th>
							<th><img src=\"images/received.png\"></th>
							<th><img src=\"images/reconciled.png\"></th>
							<th>IMG</th>
						</tr>\n";


	$class = "'odd'";
	$last_requisition_id = -1;
	$row_count = 0;
	$amount_sum = 0;

	if(sizeof($requisition_array) < 1) {
		$content .= $table_headers;
		$content .= "<tr><td colspan=\"12\">There are no entries that match your filter.</td></tr></table>\n";
		$_SESSION['form_memory']['requisition'] = array();
		return $content;
	}
	
	foreach($requisition_array as $row) {
		$row_count++;
		if($requisition_array[$row_count]['id'] == $requisition_array[$row_count - 1]['id']) $requisition_has_multiple_lines = true;
		else $requisition_has_multiple_lines = false;

		// Shorten notes to 30 characters to save space on screen
		$full_desc = htmlentities($row['description']);
		$short_desc = trim(substr($full_desc,0,35));
		if(strlen($full_desc) > strlen($short_desc)) $short_desc .= "...";

		// Shorten Vendor Info to 30 characters to save space on screen
		$full_vendor = htmlentities($row['vendor_info']);
		$short_vendor = trim(substr($full_vendor,0,25));
		if(strlen($full_vendor) > strlen($short_vendor)) $short_vendor .= "...";

		if($sort_by == 'date') $cur_cat_title = $row['date'];
		else $cur_cat_title = $row[$sort_by];

		if(($row['id'] != $last_requisition_id) || ($last_cat_title != $cur_cat_title)) {
			if($class == "'evn'") $class = "'odd'";
			else $class = "'evn'";
		}

		//Print table headers ONCE at the top when sorting by date
		if(($sort_by == 'date' /*|| $sort_by == 'id'*/) && ($row_count == 1)) {
			$content .= $table_headers;
		}

		elseif(($last_cat_title != $cur_cat_title) && ($sort_by != 'date')/* && ($sort_by != 'id')*/) { //Don't reprint table headers when sorting by date
			if($row_count != 1) {
				$content .= "<tr style=\"background-color:none;\"><td style=\"text-align:right;\" colspan=\"8\">Total</td><td style=\"text-align:right;\">$".number_format($amount_sum,2)."</td><td colspan=\"3\">&nbsp;</td></tr>\n";
				$amount_sum = 0;
			}
			$content .= "<tr class=\"new_cat_row\"><td class=\"new_cat_cell\" style=\"text-transform:capitalize;\" colspan=\"12\">".htmlentities($cur_cat_title)."</td></tr>\n";
			$content .= $table_headers;
		}
		$amount_sum += $row['amount'];

		// Group expenses from the same requisition by color
		if(($row['id'] != $last_requisition_id) || ($last_cat_title != $cur_cat_title)) {

			$content	.= "<tr id=\"".$row['id']."\" class=".$class." "
				  	. "onMouseOver=\"document.getElementById('".$row['id']."').className='highlight';\" "
				  	. "onMouseOut=\"document.getElementById('".$row['id']."').className=".$class.";\">\n";


			if($requisition_has_multiple_lines) {
				$content .= insert_split_header_row($row, $short_vendor, $short_desc, $class);
				
				$content .= insert_split_detail_row($row, $row_count, $cur_cat_title, $last_cat_title, $class);
			}
			else {
				$content .= insert_single_row($row, $short_vendor, $short_desc, $class);
			}
		}
		else {
			$content.= insert_split_detail_row($row, $row_count, $cur_cat_title, $last_cat_title, $class);
		}

		$last_cat_title = $cur_cat_title;
		$last_requisition_id = $row['id'];
	} //END foreach($requisition_array as $row)

	$content .= "<tr style=\"background-color:none;\"><td style=\"text-align:right\" colspan=\"8\">Total</td><td style=\"text-align:right;\" >$".number_format($amount_sum,2)."</td><td colspan=\"3\">&nbsp;</td></tr>\n"
			 .  "</table>";

	$_SESSION['form_memory']['requisition'] = array();

	return $content;
}


/*********************************************************************************************************/
function populate_requisition_by_id($id) {
	/* Check id validity here */
	$requisition_id = mydb::cxn()->real_escape_string($_GET['id']);
	$query = "SELECT count(*) as num FROM requisitions WHERE id = ".$requisition_id;
	$result = mydb::cxn()->query($query);
	$row = $result->fetch_assoc();
	if($row['num'] == 0) throw new Exception('The requested requisition does not exist (Requisition #'.$requisition_id.').');

	$query = "SELECT
			  requisitions.id,
                          requisitions.added_by,
			  requisitions.vendor_info,
			  requisitions.description,
			  requisitions.attachment1, 
			  requisitions.attachment2,
			  requisitions.attachment3,
			  round(requisitions.amount,2) as order_total,
			  requisitions.card_used,
			  date_format(requisitions.date,'%m/%d/%Y') as date,
			  requisitions_split.id as split_id,
			  requisitions_split.comments as split_comments,
			  requisitions_split.s_number,
			  requisitions_split.charge_code,
			  requisitions_split.override,
			  round(requisitions_split.amount,2) as amount,
			  requisitions_split.received as split_received,
			  requisitions_split.reconciled as split_reconciled
			  FROM requisitions LEFT OUTER JOIN requisitions_split 
			  ON requisitions.id = requisitions_split.requisition_id
			  WHERE requisitions.id = ".$id."
			  ORDER BY split_id";
	
	$result = mydb::cxn()->query($query);
	if(!$result) {
		throw new Exception('Database error: '.mydb::cxn()->error);
	}
	$_SESSION['form_memory']['requisition'] = array();
	while($row = $result->fetch_assoc()) {
		$_SESSION['form_memory']['requisition'][] = $row;
	}

	return;
}


function insert_split_header_row($row, $short_vendor, $short_desc, $class) {
	$content = "<td $class style=\"\"><a href=\"".$_SERVER['PHP_SELF']."?function=view_requisition&id=".$row['id']."\">".$row['id']."</a></td>\n"
			  ."<td style=\"width:7em;\">".$row['date']."</td>\n"
			  ."<td style=\"text-align:left;\">".$short_vendor."</td>\n"
			  ."<td style=\"text-align:left;\">".$short_desc."</td>\n"
			  ."<td style=\"text-transform:capitalize;\">".ucwords(str_replace("_"," ",$row['card_used']))."</td>\n";
			  
			  $content .= "<td colspan=\"6\">&nbsp;</td>";
			  $content .= "<td style=\"text-align:center\">";
			  if(($row['attachment1'] != '') || ($row['attachment2'] != '') || ($row['attachment3'] != '')) {
				  $content .= "<img src=\"images/receipt_yes.png\" title=\"Attachment Uploaded\">";
			  }
			  $content .= "</td>\n</tr>\n";
			  
	return $content;
}

function insert_split_detail_row($row, $row_count, $cur_cat_title, $last_cat_title, $class) {
	
	//if($class == "'odd'") $comment_color = "#777";
	//else $comment_color = "#fff";
	$content = "<tr id=\"row_split_".$row_count."\" class=".$class." "
			. "onMouseOver=\"document.getElementById('row_split_".$row_count."').className='highlight';\" "
			. "onMouseOut=\"document.getElementById('row_split_".$row_count."').className=".$class.";\">\n";
	
	$content .=  "<td colspan=\"2\">&nbsp</td>"
			."<td colspan=\"3\" style=\"text-align:left; color:#555; font-style:italic;\">".$row['split_comments']."</td>\n"
			."<td style=\"text-transform:uppercase;\">".$row['s_number']."</td>\n"
			."<td style=\"text-transform:uppercase;\">".$row['charge_code']."</td>\n"
			."<td style=\"text-transform:uppercase;\">".$row['override']."</td>\n"
			."<td style=\"text-transform:uppercase; text-align:right; font-weight:bold;\">$"
			.number_format($row['amount'],2)."</td>\n"
			."<td style=\"text-align:center\">";
				
	if($row['split_received'] == 'checked') $content .= "<img id=\"delivered_".$row['split_id']."\" src=\"images/received_yes.png\" onClick=\"setBudgetItemStatus(".$row['split_id'].",'delivered_".$row['split_id']."')\" title=\"Received (Click to un-receive)\">";
	else $content .= "<img id=\"delivered_".$row['split_id']."\" src=\"images/received_no.png\" onClick=\"setBudgetItemStatus(".$row['split_id'].",'delivered_".$row['split_id']."')\" title=\"Not Received (Click to receive)\">";
	$content .= "</td>\n"
				."<td style=\"text-align:center\">";
				
	if($row['split_reconciled'] == 'checked') $content .= "<img id=\"reconciled_".$row['split_id']."\" src=\"images/reconciled_yes.png\" onClick=\"setBudgetItemStatus(".$row['split_id'].",'reconciled_".$row['split_id']."')\" title=\"Reconciled (Click to un-reconcile)\">";
	else $content .= "<img id=\"reconciled_".$row['split_id']."\" src=\"images/reconciled_no.png\" onClick=\"setBudgetItemStatus(".$row['split_id'].",'reconciled_".$row['split_id']."')\" title=\"Un-Reconciled (Click to reconcile)\">";
	
	$content .= "</td>\n";
	
	$content .= "<td style=\"text-align:center\">";
	
	if(($row['attachment1'] != '') || ($row['attachment2'] != '') || ($row['attachment3'] != '')) {
		$content .= "<img src=\"images/receipt_yes.png\" title=\"Attachment Uploaded\">";
	}
	$content .= "</td>\n</tr>\n";
	
	return $content;
}

function insert_single_row($row, $short_vendor, $short_desc,$class) {
	$content = "<td $class style=\"\"><a href=\"".$_SERVER['PHP_SELF']."?function=view_requisition&id=".$row['id']."\">".$row['id']."</a></td>\n"
			  ."<td style=\"width:7em;\">".$row['date']."</td>\n"
			  ."<td style=\"text-align:left;\">".$short_vendor."</td>\n"
			  ."<td style=\"text-align:left;\">".$short_desc."</td>\n"
			  ."<td style=\"text-transform:capitalize;\">".str_replace("_"," ",$row['card_used'])."</td>\n"
			  ."<td style=\"text-transform:uppercase;\">".$row['s_number']."</td>\n"
			  ."<td style=\"text-transform:uppercase;\">".$row['charge_code']."</td>\n"
			  ."<td style=\"text-transform:uppercase;\">".$row['override']."</td>\n"
			  ."<td style=\"text-transform:uppercase; text-align:right; font-weight:bold;\">$"
			  .number_format($row['amount'],2)."</td>\n"
			  ."<td style=\"text-align:center\">";
				
	if($row['split_received'] == 'checked') $content .= "<img id=\"delivered_".$row['split_id']."\" src=\"images/received_yes.png\" onClick=\"setBudgetItemStatus(".$row['split_id'].",'delivered_".$row['split_id']."')\" title=\"Received (Click to un-receive)\">";
	else $content .= "<img id=\"delivered_".$row['split_id']."\" src=\"images/received_no.png\" onClick=\"setBudgetItemStatus(".$row['split_id'].",'delivered_".$row['split_id']."')\" title=\"Not Received (Click to receive)\">";
	$content .= "</td>\n"
				."<td style=\"text-align:center\">";
				
	if($row['split_reconciled'] == 'checked') $content .= "<img id=\"reconciled_".$row['split_id']."\" src=\"images/reconciled_yes.png\" onClick=\"setBudgetItemStatus(".$row['split_id'].",'reconciled_".$row['split_id']."')\" title=\"Reconciled (Click to un-reconcile)\">";
	else $content .= "<img id=\"reconciled_".$row['split_id']."\" src=\"images/reconciled_no.png\" onClick=\"setBudgetItemStatus(".$row['split_id'].",'reconciled_".$row['split_id']."')\" title=\"Un-Reconciled (Click to reconcile)\">";
	
	$content .= "</td>\n";
			  $content .= "<td style=\"text-align:center\">";
			  if(($row['attachment1'] != '') || ($row['attachment2'] != '') || ($row['attachment3'] != '')) {
				  $content .= "<img src=\"images/receipt_yes.png\" title=\"Attachment Uploaded\">";
			  }
			  $content .= "</td>\n</tr>\n";
	
	return $content;
}
?>
