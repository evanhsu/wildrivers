<?php

function get_inv() {
// Build a database query based on a user-specified sort field
// Run display_inv to step through each row and display the inventory data

	$query = "	SELECT	inventory.id,
						inventory.serial_no,
						inventory.quantity,
						inventory.item_type,
						inventory.color,
						inventory.size,
						inventory.description,
						inventory.restock_trigger,
						inventory.restock_to_level,
						inventory.item_source,
						inventory.item_condition,
						inventory.checked_out_to_id,
						concat(crewmembers.firstname,' ',crewmembers.lastname) as checked_out_to,
						inventory.note,
						inventory.usable,MAX(unix_timestamp(inventory_history.date)) as date,
						vip.name as vip_name,
						vip.contact as vip_contact
				FROM			inventory
				LEFT OUTER JOIN	crewmembers
					ON (inventory.checked_out_to_id = crewmembers.id)
				LEFT OUTER JOIN	vip
					ON (inventory.id = vip.item_id)
				LEFT OUTER JOIN inventory_history
					ON (inventory.id = inventory_history.item_id)";
				
	$cat = strtolower(mydb::cxn()->real_escape_string($_SESSION['inventory_category_view']));
	if($cat != "") $query .= " WHERE inventory.item_type LIKE '" . $cat . "'";

	$query .= "\nGROUP BY inventory.id\n";

	$sort_by = $_SESSION['sort_view_by'];
	switch($sort_by) {
		case "serial_no":
			$query .= " ORDER BY item_type, serial_no, description";
			break;
		case "item_type":
			$query .= " ORDER BY item_type, description, serial_no, size, color";
			break;
		case "size":
			$query .= " ORDER BY item_type, size, description, serial_no";
			break;
		case "color":
			$query .= " ORDER BY color, item_type, size, serial_no";
			break;
		case "description":
			$query .= " ORDER BY description, item_type, size, color, serial_no";
			break;
		case "checked_out_to":
			$query .= " ORDER BY checked_out_to, vip_name, item_type, serial_no, size";
			break;
		case "item_condition":
			$query .= " ORDER BY item_condition, item_type, size, description";
			break;
		case "usable":
			$query .= " ORDER BY usable, item_condition, item_type, size, description";
			break;
		default:
			$query .= " ORDER BY item_type, description, serial_no, size, color";
			$_SESSION['sort_view_by'] = "item_type";
			break;
	}
	$result = mydb::cxn()->query($query) or die("dB query failed (Retrieving inventory): " . mydb::cxn()->error);
	
	return $result;
}


function get_restock_list() {
// Build a database query based on a user-specified sort field
// Only include items whose QUANTITY is equal-to-or-less-than the restocking level.
// Run display_inv to step through each row and display the inventory data

	$query = "	SELECT	inventory.id,
						inventory.serial_no,
						inventory.quantity,
						inventory.item_type,
						inventory.color,
						inventory.size,
						inventory.description,
						inventory.restock_trigger,
						inventory.restock_to_level,
						inventory.item_source,
						inventory.item_condition,
						inventory.checked_out_to_id,
						concat(crewmembers.firstname,' ',crewmembers.lastname) as checked_out_to,
						inventory.note,
						inventory.usable,MAX(unix_timestamp(inventory_history.date)) as date,
						vip.name as vip_name,
						vip.contact as vip_contact
				FROM			inventory
				LEFT OUTER JOIN	crewmembers
					ON (inventory.checked_out_to_id = crewmembers.id)
				LEFT OUTER JOIN	vip
					ON (inventory.id = vip.item_id)
				LEFT OUTER JOIN inventory_history
					ON (inventory.id = inventory_history.item_id)";
				
	$query .= " WHERE inventory.quantity <= inventory.restock_trigger ";

	$query .= "\nGROUP BY inventory.id\n";

	$sort_by = $_SESSION['sort_view_by'];
	switch($sort_by) {
		case "serial_no":
			$query .= " ORDER BY item_type, serial_no, description";
			break;
		case "item_type":
			$query .= " ORDER BY item_type, description, serial_no, size, color";
			break;
		case "size":
			$query .= " ORDER BY item_type, size, description, serial_no";
			break;
		case "color":
			$query .= " ORDER BY color, item_type, size, serial_no";
			break;
		case "description":
			$query .= " ORDER BY description, item_type, size, color, serial_no";
			break;
		case "checked_out_to":
			$query .= " ORDER BY checked_out_to, vip_name, item_type, serial_no, size";
			break;
		case "item_condition":
			$query .= " ORDER BY item_condition, item_type, size, description";
			break;
		case "usable":
			$query .= " ORDER BY usable, item_condition, item_type, size, description";
			break;
		default:
			$query .= " ORDER BY item_type, description, serial_no, size, color";
			$_SESSION['sort_view_by'] = "item_type";
			break;
	}
	$result = mydb::cxn()->query($query) or die("dB query failed (Retrieving inventory): " . mydb::cxn()->error);
	
	return $result;
}

//==========================================================================================================
/*
function get_categories() {
	$query = "SELECT DISTINCT ucase(inventory.item_type) as capital_item_type, inventory.item_type as item_type FROM inventory WHERE 1 GROUP BY capital_item_type ORDER BY item_type";
	$result = mydb::cxn()->query($query) or die("dB query failed (Retrieving inventory category list): " . mydb::cxn()->error);
	
	return $result; 

}//End function get_categories()
*/
//==========================================================================================================
function display_inv($result, $php_self) {
// Display the inventory, sorted by a user-specified field
// This function requires that get_inv has been previously run to submit the initial database query
	
	$sort_by = $_SESSION['sort_view_by'];

	echo "<span class=\"highlight1\" style=\"display:block\">Inventory is sorted by " . $sort_by . "</span>\n";
	echo "<a href=\"http://inventory.siskiyourappellers.com/inv_pdf.php\">[Printer-friendly View]</a><br><br>\n";
	
	//Display crewmember menu
	//Get current roster

	$result_crewmembers = mydb::cxn()->query("	SELECT concat(crewmembers.firstname, ' ', crewmembers.lastname) as name, crewmembers.id as id
									FROM crewmembers inner join roster
									ON crewmembers.id = roster.id
									WHERE roster.year like '" . $_SESSION['year'] . "' ORDER BY name");

	$option_menu = 	"<select name=\"id\">\n"
					."<option value=\"-1\">&nbsp;</option>\n";

	while($row = $result_crewmembers->fetch_assoc()) {
		if(isset($_GET['id']) && ($row['id'] == $_GET['id'])) $option_menu .= "<option value=\"" . $row['id'] . "\" SELECTED>" . $row['name'] . "</option>\n";
		else $option_menu .= "<option value=\"" . $row['id'] . "\">" . $row['name'] . "</option>\n";
	}

	$option_menu .= "</select>\n";
	
	echo "Personal Gear List: <form action=\"".$php_self."\" method=\"GET\">\n"
		."<input type=\"hidden\" name=\"function\" value=\"personal_gear_list\">\n"
		. $option_menu
		."<input type=\"submit\" value=\"View\">\n"
		."</form>\n";
		
	$output = "<table>\n";
	
	if($_SESSION['sort_view_by'] == 'serial_no') $sort_by = 'item_type'; 	//Sorting by serial # or size are special cases:
	if($_SESSION['sort_view_by'] == 'size') $sort_by = 'item_type';			//we actually want to sort by item_type, then serial_no/size
																			//but we want to display "Inventory is sorted by serial_no"
	
	//INITIALIZE PHP VARIABLES
	//$_SESSION['inv_headers'] = array('Serial #','Item Type','Size','Color','Description','Checked Out To','Condition','Usable','Note');
	//$_SESSION['inv_array'] = array();
	$item_type = ""; // "bulk", "bulk_issued", "accountable" - this value will be used to index the $row_count table
	
	$row_count = array("all" => 0, "bulk" => 0, "bulk_issued" => 0, "accountable" => 0);
	
	$bulk_item_table = ""; //This will be appended to $output to display the table of bulk items under each category
	$bulk_issued_item_table = ""; //This will be appended to $output to display the table of bulk items that also have a "checked_out_to" value
	$accountable_item_table = ""; //This will be appended to $output to display the table of accountable items under each category
	
	$last_cat_title = "~`$(*#)*@(!-_~}}";
	
	// Use different table headers depending on whether this 'item_type' is a bulk item or an 'accountable property' item (with a serial number)
	// The variables: $item_is_bulk and $item_is_checked_out are used to determine which table headers to put each item under
	$table_headers_accountable = "<tr><th>&nbsp;</th>
							  <th><a href=\"" . $php_self . "?sort_by=serial_no\">Serial #</a></th>
							  <th><a href=\"" . $php_self . "?sort_by=item_type\">Item Type</a></th>
							  <th><a href=\"" . $php_self . "?sort_by=size\">Size</a></th>
							  <th><a href=\"" . $php_self . "?sort_by=color\">Color</a></th>
							  <th><a href=\"" . $php_self . "?sort_by=description\">Description</a></th>
							  <th><a href=\"" . $php_self . "?sort_by=checked_out_to\">Checked Out To</a></th>
							  <th><a href=\"" . $php_self . "?sort_by=item_condition\">Condition</a></th>
							  <th><a href=\"" . $php_self . "?sort_by=usable\">Usable</a></th>
							  <th>Note</th>
							  <th>&nbsp;</th>
							  <th>&nbsp;</th>
							  <th>Date Modified</th>
						  </tr>\n";
	
	$table_headers_bulk = "<tr><th>&nbsp;</th>
							  <th>QTY</th>
							  <th><a href=\"" . $php_self . "?function=".$_GET['function']."&sort_by=item_type\">Item Type</a></th>
							  <th><a href=\"" . $php_self . "?function=".$_GET['function']."&sort_by=size\">Size</a></th>
							  <th><a href=\"" . $php_self . "?function=".$_GET['function']."&sort_by=color\">Color</a></th>
							  <th><a href=\"" . $php_self . "?function=".$_GET['function']."&sort_by=description\">Description</a></th>
							  <th>Min QTY</th>
							  <th>Restock To...</th>
							  <th>Item Source</th>
							  <th>Note</th>
							  <th>&nbsp;</th>
							  <th>&nbsp;</th>
							  <th>Date Modified</th>
						  </tr>\n";
						  
	$table_headers_bulk_issued = "<tr><th>&nbsp;</th>
							  <th>QTY</th>
							  <th><a href=\"" . $php_self . "?sort_by=item_type\">Item Type</a></th>
							  <th><a href=\"" . $php_self . "?sort_by=size\">Size</a></th>
							  <th><a href=\"" . $php_self . "?sort_by=color\">Color</a></th>
							  <th><a href=\"" . $php_self . "?sort_by=description\">Description</a></th>
							  <th><a href=\"" . $php_self . "?sort_by=checked_out_to\">Checked Out To</a></th>
							  <th><a href=\"" . $php_self . "?sort_by=item_condition\">Condition</a></th>
							  <th><a href=\"" . $php_self . "?sort_by=usable\">Usable</a></th>
							  <th>Note</th>
							  <th>&nbsp;</th>
							  <th>&nbsp;</th>
							  <th>Date Issued</th>
						  </tr>\n";

	$table_footer = "<tr><th colspan=2>Total</th><th colspan=11>&nbsp;</th></tr>\n";
	$category_qty = 0; //This tracks the qty of items in this category (sum of all bulk, bulk_issued & accountable items)
	

	$categories = array();
	while($row = $result->fetch_assoc()) {
		$row_count["all"]++;

		$cur_cat_title = htmlentities($row[$sort_by]);
		if(strtolower($last_cat_title) != strtolower($cur_cat_title)) {

			if($row_count["bulk"] > 0) $output .= $bulk_item_table;			//We have just started a new category, so push the bulk table from the previous category into $output.
			if($row_count["bulk_issued"] > 0) $output .= $bulk_issued_item_table;	//Push the table of issued bulk items from the previous category into $output.
			if($row_count["accountable"] > 0) $output .= $accountable_item_table;	//Push the table of accountable items from the previous category into $output.
			
			//Display a "Total" field at the end of the category that shows a cumulative sum of all items in this category
			if($row_count["all"] > 1) {
				$output .= $table_footer;
				$output .= "<tr><td></td>"
					.  "<td>".($category_qty)."</td>"
					.  "<td colspan=11>&nbsp;</td></tr>\n";	
			}

			//Display the new category title			
			$output .= "<tr class=\"new_cat_row\"><td class=\"new_cat_cell\" colspan=\"13\"><a name=".urlencode($cur_cat_title).">$cur_cat_title</a></td></tr>\n";
			
			$row_count["bulk"] = 0;
			$row_count["bulk_issued"] = 0;
			$row_count["accountable"] = 0;
			$category_qty = 0; //This tracks the qty of items in this category (sum of all bulk, bulk_issued & accountable items)
			
			$bulk_item_table = $table_headers_bulk;
			$bulk_issued_item_table = $table_headers_bulk_issued;
			$accountable_item_table = $table_headers_accountable;
			
			$categories[] = $cur_cat_title;
		}
		$last_cat_title = $cur_cat_title;
				
		if(($row['serial_no'] == "") && ($row['checked_out_to_id'] == -1)) {
			// ('checked_out_to_id' == -1) denotes that this item is not checked out to anybody
			$item_type = "bulk";
		}
		elseif(($row['serial_no'] == "") && ($row['checked_out_to_id'] != -1)) {
			$item_type = "bulk_issued";
		}
		else {
			$item_type = "accountable";
			$serial_no = htmlentities($row['serial_no']);
		}
		$row_count[$item_type]++;

		
		// Shorten notes to 25 characters to save space on screen
		$full_note = htmlentities($row['note']);
		$short_note = substr($full_note,0,25);
		if(strlen($full_note) > strlen($short_note)) $short_note .= "...";
		
		// Shorten item_source to 25 characters to save space on screen
		$full_item_source = htmlentities($row['item_source']);
		$short_item_source = substr($full_item_source,0,25);
		if(strlen($full_item_source) > strlen($short_item_source)) $short_item_source .= "...";
		
		// Color code alternating rows
		if($item_type == "bulk") {
			if($row['quantity'] <= $row['restock_trigger']) $bulk_item_table .= "<tr id=\"item_".$row['id']."_row\" class=\"low_quantity_item\">";
			elseif($row_count[$item_type] % 2 == 0) $bulk_item_table .= "<tr id=\"item_".$row['id']."_row\" class=\"evn\">";
			else $bulk_item_table .= "<tr id=\"item_".$row['id']."_row\" class=\"odd\">";
			$category_qty += $row['quantity'];
		}
		elseif($item_type == "bulk_issued") {
			if($row_count[$item_type] % 2 == 0) $bulk_issued_item_table .= "<tr id=\"item_".$row['id']."_row\" class=\"evn\">";
			else $bulk_issued_item_table .= "<tr id=\"item_".$row['id']."_row\" class=\"odd\">";
			$category_qty += $row['quantity'];	
		}
		else {
			// Item is 'Accountable' (has a serial number)
			if($row['usable'] == 0) $accountable_item_table .= "<tr class=\"unusable\">";
			elseif($row_count[$item_type] % 2 == 0) $accountable_item_table .= "<tr id=\"item_".$row['id']."_row\" class=\"evn\">";
			else $accountable_item_table .= "<tr id=\"item_".$row['id']."_row\" class=\"odd\">";
			$category_qty += 1;
		}
		
		if($row['usable'] == 1) $usable = 'Yes';
		else $usable = 'No';
		
		

		// Display row contents (different output depending on whether this item is a BULK item, BULK_ISSUED item, or an ACCOUNTABLE ITEM (with a serial number)
		if($item_type == "bulk") {
			$bulk_item_table .= "<td><a href=\"".$php_self."?function=edit_item&id=".$row['id']."\"><img src=\"images/magnifying_glass.png\"></a></td>
						<td id=\"item_".$row['id']."_qty\">".$row['quantity']."</td>
						<td>".htmlentities($row['item_type'])."</td>
						<td>".htmlentities($row['size'])."</td>
						<td>".htmlentities($row['color'])."</td>
						<td>".htmlentities($row['description'])."</td>
						<td id=\"item_".$row['id']."_restock_trigger\">".$row['restock_trigger']."</td>
						<td>".$row['restock_to_level']."</td>
						<td style=\"text-transform:none;\">".$short_item_source."</td>
						<td style=\"text-transform:none;\">".$short_note."</td>";
			
			if($row['quantity'] > 0) $minus_sign_vis = 'visible';
			else $minus_sign_vis = 'hidden';
			
			$bulk_item_table .= "<td style=\"text-align:center;\">";
			$bulk_item_table .= "<img src=\"images/minus_sign.png\" style=\"visibility:".$minus_sign_vis."\" id=\"item_".$row['id']."_minus_img\" title=\"DECREASE this quantity by 1\" onClick=\"changeItemQty('".$row['id']."',-1);\">";
			$bulk_item_table .= "</td>";
			
			$bulk_item_table .= "<td style=\"text-align:center;\"><img src=\"images/plus_sign.png\" title=\"INCREASE this quantity by 1\" onClick=\"changeItemQty('".$row['id']."',1);\"></td>";
				
			$bulk_item_table .= "<td>".date("m/d/Y",$row['date'])."</td></tr>\n";

		}
		elseif($item_type == "bulk_issued") {
			$bulk_issued_item_table .= "<td></td>
						<td id=\"item_".$row['id']."_qty\">".$row['quantity']."</td>
						<td>".htmlentities($row['item_type'])."</td>
						<td>".htmlentities($row['size'])."</td>
						<td>".htmlentities($row['color'])."</td>
						<td>".htmlentities($row['description'])."</td>";
			if($row['checked_out_to_id'] == -2) {
				$bulk_issued_item_table.="<td title=\"".$row['vip_name']."\n".$row['vip_contact']."\">".$row['vip_name']."</td>\n";
			}
			else	$bulk_issued_item_table.="<td><a href=\"".$php_self."?function=personal_gear_list&id=".$row['checked_out_to_id']."\">".$row['checked_out_to']."</a></td>\n";
			$bulk_issued_item_table .= "<td>".htmlentities($row['item_condition'])."</td>
						<td>".htmlentities($usable)."</td>
						<td style=\"text-transform:none;\">".$short_note."</td>";
			$bulk_issued_item_table .= "<td><a href=\"".$php_self."?function=check_out_bulk&id=".$row['id']."\" style=\"width:10px;height:8px;\"><img src=\"images/check_out.png\" style=\"width:10px;height:8px;\" alt=\"Check Out 1 More\" title=\"Check Out 1 More\"></a></td>\n";
			$bulk_issued_item_table .= "<td><a href=\"".$php_self."?function=check_in_bulk&id=".$row['id']."\" style=\"width:10px;height:8px;\"><img src=\"images/check_in.png\" style=\"width:10px;height:8px;\" alt=\"Check In 1\" title=\"Check In 1\"></a></td>";
				
			$bulk_issued_item_table .= "<td>".date("m/d/Y",$row['date'])."</td>	</tr>\n";

		}
		else {
			$accountable_item_table .= "<td><a href=\"".$php_self."?function=edit_item&id=".$row['id']."\"><img src=\"images/magnifying_glass.png\"></a></td>
						<td>".$serial_no."</td>
						<td>".htmlentities($row['item_type'])."</td>
						<td>".htmlentities($row['size'])."</td>
						<td>".htmlentities($row['color'])."</td>
						<td>".htmlentities($row['description'])."</td>";
			if($row['checked_out_to_id'] == -2) $accountable_item_table.="<td>".$row['vip_name']."</td>\n";
			else	$accountable_item_table.="<td><a href=\"".$php_self."?function=personal_gear_list&id=".$row['checked_out_to_id']."\">".$row['checked_out_to']."</a></td>\n";
			$accountable_item_table .= "<td>".htmlentities($row['item_condition'])."</td>
						<td>".htmlentities($usable)."</td>
						<td style=\"text-transform:none;\">".$short_note."</td>";
			if($row['checked_out_to_id'] == "-1") $accountable_item_table .= "<td><a href=\"".$php_self."?function=check_out&id=".$row['id']."\" style=\"width:10px;height:8px;\"><img src=\"images/check_out.png\" style=\"width:10px;height:8px;\" alt=\"Check Out\" title=\"Check Out\"></a></td> \n <td></td>";
			else $accountable_item_table .= "<td></td> \n <td><a href=\"".$php_self."?function=check_in&id=".$row['id']."\" style=\"width:10px;height:8px;\"><img src=\"images/check_in.png\" style=\"width:10px;height:8px;\" alt=\"Check In\" title=\"Check In\"></a></td>";
				
			$accountable_item_table .= "<td>".date("m/d/Y",$row['date'])."</td>	</tr>\n";

		}
	} //END while($row = $result->fetch_assoc())
	
	if($row_count["bulk"] > 0) $output .= $bulk_item_table;			//We have just started a new category, so push the bulk table from the previous category into $output.
	if($row_count["bulk_issued"] > 0) $output .= $bulk_issued_item_table;	//Push the bulk_issued table from the previous category into $output.
	if($row_count["accountable"] > 0) $output .= $accountable_item_table;	//Push the table of accountable items from the previous category into $output.

	//Display a "Total" field at the end of the category that shows a cumulative sum of all items in this category
	$output .= $table_footer;
	$output .= "<tr><td></td>"
		.  "<td>".($category_qty)."</td>"
		.  "<td colspan=11>&nbsp;</td></tr>\n";
	
	$output .= "</table>";
	
	echo "<div style=\"float:left; width:15%; margin-right:1em;\">\n"; //Use floating div to put category menu alongside item list
	echo generate_category_menu($php_self);
	echo "</div>\n"
		."<div style=\"float:left; width:80%;\">\n";
	echo $output;
	echo "</div>\n<br style=\"clear:both;\">\n";
}
//==========================================================================================================
function generate_category_menu($php_self) {
	//This function returns an HTML formatted list of all the categories in the database.  A category can be clicked on
	//to view the items within that category.
	
	$query = "SELECT DISTINCT ucase(inventory.item_type) as capital_item_type, inventory.item_type as item_type FROM inventory WHERE 1 GROUP BY capital_item_type ORDER BY item_type";
	$result = mydb::cxn()->query($query) or die("dB query failed (Retrieving inventory category list): " . mydb::cxn()->error);

	$sort_by = $_SESSION['sort_view_by'];
	$categories = array();
	$row_count = 0;
	$last_cat_title = "~`$(*#)*@(!-_~}}";

$output = "<br /><strong>Jump to Category:</strong><br />\n";
	while($row = $result->fetch_assoc()) {
		/*
		$row_count++;

		$cur_cat_title = htmlentities($row[$sort_by]);
		if(strtolower($last_cat_title) != strtolower($cur_cat_title)) {
			$categories[] = $cur_cat_title;
		}
		$last_cat_title = $cur_cat_title;
		*/
		$output .= "<a href=\"".$php_self."?function=get_inv&category=".urlencode($row['item_type'])."\">".$row['item_type']."</a><br>\n";
	}

	//Generate category menu
	/*
	foreach($categories as $cat) {
		$output .= "<a href=\"".$php_self."?function=get_inv&category=".urlencode($cat)."\">".$cat."</a><br>\n";
	}
	*/
	return $output;
	
} //End function display_category_menu()

//==========================================================================================================
function add_item_form($php_self) {
	// Display 2 different forms:
	//	One form will add an ACCOUNTABLE ITEM (with serial number) to the inventory
	//	One form will add a BULK ITEM (with a quantity-in-stock) to the inventory
	
	$accountable_form = '';
	$bulk_form = '';
	$error_msg = '';
	
	//********** DISPLAY ACCOUNTABLE_ITEM FORM *****************//
	//Get current roster
	try {
	  $result = mydb::cxn()->query("SELECT concat(crewmembers.firstname, ' ', crewmembers.lastname) as name, crewmembers.id as id
									FROM crewmembers inner join roster
									ON crewmembers.id = roster.id
									WHERE roster.year like '" . date('Y') . "'");
	  
	  $option_menu = "<option value=\"-1\" SELECTED>[None]</option>\n"
					."<option value=\"-2\">[VIP]</option>\n";
					  
	  if(mydb::cxn()->error != '') throw new Exception('There was a problem retrieving your crew roster! You will be unable to check out this item to a crewmember.');
	  while($row = $result->fetch_assoc()) {
		  $option_menu .= "<option value=\"" . $row['id'] . "\">" . htmlentities($row['name']) . "</option>\n";
	  }
	} catch (Exception $e) {
		$error_msg = $e->getMessage();
	}
	
	//Build dropdown for 'usable'
	$usable_menu = "<option value=\"0\">No</option>\n
					<option value=\"1\" SELECTED>Yes</option>";
					
	$table_headers = "	<tr><td colspan=\"9\" style=\"font-size:2em; text-align:left;\">Add Accountable Property</td></tr>
						<tr><th>Serial #</th>
							<th>Item Type</th>
							<th>Size</th>
							<th>Color</th>
							<th>Description</th>
							<th>Checked Out To</th>
							<th>Condition</th>
							<th>Usable</th>
							<th>Note</th>
						</tr>\n";
	
	$accountable_form .= "	<form action=\"". $php_self . "?function=add_item\" method=\"post\" name=\"item_info_form\">
			<table>\n"
		. $table_headers
		."<tr>	<td class=\"form\" style=\"width:auto;\"><input type=\"text\" name=\"serial_no\" class=\"entry_cell\" style=\"width:auto;\"></td>
				<td class=\"form\" style=\"width:100px;\"><input type=\"text\" name=\"item_type\" class=\"entry_cell\" style=\"width:100px;\"></td>
				<td class=\"form\" style=\"width:60px;\"><input type=\"text\" name=\"size\" class=\"entry_cell\" style=\"width:60px;\"></td>
				<td class=\"form\" style=\"width:50px;\"><input type=\"text\" name=\"color\" class=\"entry_cell\" style=\"width:50px;\"></td>
				<td class=\"form\" style=\"width:200px;\"><input type=\"text\" name=\"description\" class=\"entry_cell\" style=\"width:200px;\"></td>
				<td class=\"form\" style=\"width:150px;\"><select name=\"checked_out_to_id\"  onchange=\"add_vip_menu_if_needed('add')\" class=\"entry_cell\" style=\"width:150px;\">\n"
				.$option_menu
			. "	</select></td>
				<td class=\"form\" style=\"width:75px;\"><input type=\"text\" name=\"item_condition\" class=\"entry_cell\" style=\"width:75px;\"></td>
				<td class=\"form\" style=\"width:50px;\"><select name=\"usable\" class=\"entry_cell\" style=\"width:50px;\">\n"
				.$usable_menu
			. " </select></td>
				<td class=\"form\" style=\"width:150px;\"><input type=\"text\" name=\"note\" class=\"entry_cell\" style=\"width:150px;\"></td>
			</tr>\n";
	
	$accountable_form .= "<tr><td colspan=\"5\"></td><td class=\"form\" id=\"vip_menu_div\" colspan=\"4\"></td></tr>\n";
	
	$accountable_form .= "<tr>	<td class=\"form\" ><input type=\"submit\" value=\"Add Item\" style=\"font-size:9px;width:100px;border:2px solid #666;font-family:Verdana, Arial, Helvetica, sans-serif;\"></td>
				<td class=\"form\" colspan=\"8\"></td>
			</tr>
			</table>
			<input type=\"hidden\" name=\"status\" value=\"insert\">
			</form>\n";
	
	$accountable_form .= "	<br><br>
			Enter as much information as possible<br>
			Try to be consistent with Sizes, Item Types, Descriptions, and Condition (...don't use both 'Large' and 'Big' to refer to the same sized item)<br /><br />
			<hr style=\"width:100%; height:2px; color:#666; background-color:#666;\"><br /><br />\n";
	
	
	//********** DISPLAY BULK_ITEM FORM *****************//
	$table_headers = "	<tr><td colspan=\"9\" style=\"font-size:2em; text-align:left;\">Add Bulk Property</td></tr>
							<tr><th>Quantity In Stock</th>
							<th>Restock Trigger</th>
							<th>Restock-To Level</th>
							<th>Item Type</th>
							<th>Size</th>
							<th>Color</th>
							<th>Description</th>
							<th>Note</th>
							<th>Item Source</th>
						</tr>\n";
	
	$bulk_form .= "	<form action=\"". $php_self . "?function=add_item\" method=\"post\" name=\"bulk_item_info_form\">
			<table>\n"
		. $table_headers
		."<tr>	<td class=\"form\" style=\"width:auto;\"><input type=\"text\" name=\"quantity\" class=\"entry_cell\" style=\"width:auto;\"></td>
				<td class=\"form\" style=\"width:auto;\"><input type=\"text\" name=\"restock_trigger\" class=\"entry_cell\" style=\"width:auto;\"></td>
				<td class=\"form\" style=\"width:auto;\"><input type=\"text\" name=\"restock_to_level\" class=\"entry_cell\" style=\"width:auto;\"></td>
				<td class=\"form\" style=\"width:100px;\"><input type=\"text\" name=\"item_type\" class=\"entry_cell\" style=\"width:100px;\"></td>
				<td class=\"form\" style=\"width:60px;\"><input type=\"text\" name=\"size\" class=\"entry_cell\" style=\"width:60px;\"></td>
				<td class=\"form\" style=\"width:50px;\"><input type=\"text\" name=\"color\" class=\"entry_cell\" style=\"width:50px;\"></td>
				<td class=\"form\" style=\"width:200px;\"><input type=\"text\" name=\"description\" class=\"entry_cell\" style=\"width:200px;\"></td>
				<td class=\"form\" style=\"width:150px;\"><input type=\"text\" name=\"note\" class=\"entry_cell\" style=\"width:150px;\"></td>
				<td class=\"form\" style=\"width:200px;\"><input type=\"text\" name=\"item_source\" class=\"entry_cell\" style=\"width:200px;\"></td>
			</tr>\n";
	
	$bulk_form .= "<tr>	<td class=\"form\" ><input type=\"submit\" value=\"Add Item\" style=\"font-size:9px;width:100px;border:2px solid #666;font-family:Verdana, Arial, Helvetica, sans-serif;\"></td>
				<td class=\"form\" colspan=\"8\"></td>
			</tr>
			</table>
			<input type=\"hidden\" name=\"status\" value=\"insert\">
			</form><br /><br />
			
			Field Description:<br />";
			
	$bulk_form .= "<table>	<tr><td>Quantity In Stock:</td><td>Enter the quantity of this item that you currently have in stock</td></tr>
							<tr><td>Restock Trigger:</td><td>Enter the minimum stock level for this item.  When the Quantity In Stock reaches this number, you should order more.</td></tr>
							<tr><td>Restock-To Level:</td><td>Enter the quantity you would like to have in stock after a resupply order.</td></tr>
							<tr><td>Item Type:</td><td>Enter the category that this item falls into (i.e. 'Leather Gloves', 'Nomex Pants', 'MRE', etc)</td></tr>
							<tr><td>Size:</td><td>(Optional) Enter the size if applicable. This is mainly for clothing items or gear that has multiple size options.</td></tr>
							<tr><td>Color:</td><td>(Optional) Enter the color of this item for identification purposes.</td></tr>
							<tr><td>Description:</td><td>(Optional) Enter a brief description of this item</td></tr>
							<tr><td>Note:</td><td>(Optional) Enter a brief note about this item</td></tr>
							<tr><td>Item Source:</td><td>(Optional) Enter the purchase source for this item (i.e. NFES #, GSA #, web address, etc)</td></tr>
					</table>\n\n";
	
	/**************** DISPLAY THE PAGE CONTENTS **********************/
	echo $error_msg;
	echo $accountable_form;
	echo $bulk_form;
	
	
}

//==========================================================================================================
function add_item() {
	
	$error_msg = "";
	try {
		if(trim($_POST['item_type']) == '') $error_msg = "<span class=\"highlight1\" style=\"display:block\">You must specify an ITEM TYPE</span><br />";
		
		//If item is being checked out to a VIP, make sure that contact info is supplied
		if(((trim($_POST['vip_name']) == '') || (trim($_POST['vip_contact']) == '')) && ($_POST['checked_out_to_id'] == -2)) {
			$error_msg = "<span class=\"highlight1\" style=\"display:block\">You must provide a name & contact info for the VIP</span><br />";
		}
		$note = mydb::cxn()->real_escape_string(trim($_POST['note']));
		
		if(trim($_POST['serial_no']) != '') {
			// This is an ACCOUNTABLE ITEM (with a serial number)
			mydb::cxn()->query("insert into inventory (serial_no, item_type, color, size, description, item_condition, checked_out_to_id, note, usable, item_source)
								values ('".mydb::cxn()->real_escape_string(trim($_POST['serial_no']))."',
										'".mydb::cxn()->real_escape_string(trim($_POST['item_type']))."',
										'".mydb::cxn()->real_escape_string(trim($_POST['color']))."',
										'".mydb::cxn()->real_escape_string(trim($_POST['size']))."',
										'".mydb::cxn()->real_escape_string(trim($_POST['description']))."',
										'".mydb::cxn()->real_escape_string(trim($_POST['item_condition']))."',
										'".$_POST['checked_out_to_id']."',
										'".$note."',
										'".$_POST['usable']."',
										'".$_POST['item_source']."')");
			
			if(mydb::cxn()->error != '') throw new Exception("There was an error adding your item to the inventory: " . mydb::cxn()->error);
			$item_id = mydb::cxn()->insert_id;
			
			if($_POST['checked_out_to_id'] == -2) {
				mydb::cxn()->query("insert into vip(item_id, name, contact)
							values(".$item_id.",
									'".mydb::cxn()->real_escape_string(trim($_POST['vip_name']))."',
									'".mydb::cxn()->real_escape_string(trim($_POST['vip_contact']))."')
							on duplicate key update name	= '".mydb::cxn()->real_escape_string(trim($_POST['vip_name']))."',
													contact	= '".mydb::cxn()->real_escape_string(trim($_POST['vip_contact']))."'") or die("Error adding VIP info for this item: " . mydb::cxn()->error);
			}
		}
		elseif(isset($_POST['checked_out_to_id']) && ($_POST['checked_out_to_id'] != -1)) {
			// This is a BULK ISSUED ITEM (with a quantity AND a 'checked_out_to_id' value)
			// The only way a new item can be created as a BULK ISSUED item is if a BULK ITEM is edited and the "Issue 1 To:" field
			// populated.  The item being created here is of the same type as the BULK ITEM.
			
			//if($_POST['quantity'] == '') $quantity = 0;
			//else $quantity = mydb::cxn()->real_escape_string(trim($_POST['quantity']));
			
			mydb::cxn()->query("insert into inventory (quantity, item_type, color, size, description, item_condition, note, checked_out_to_id, item_source)
								values (1,
										'".mydb::cxn()->real_escape_string(trim($_POST['item_type']))."',
										'".mydb::cxn()->real_escape_string(trim($_POST['color']))."',
										'".mydb::cxn()->real_escape_string(trim($_POST['size']))."',
										'".mydb::cxn()->real_escape_string(trim($_POST['description']))."',
										'".mydb::cxn()->real_escape_string(trim($_POST['item_condition']))."',
										'".$note."',
										'".mydb::cxn()->real_escape_string(trim($_POST['checked_out_to_id']))."',
										'".mydb::cxn()->real_escape_string(trim($_POST['item_source']))."')");
			
			if(mydb::cxn()->error != '') throw new Exception("There was an error adding your item to the inventory: " . mydb::cxn()->error);
			$item_id = mydb::cxn()->insert_id;
			
			if($_POST['checked_out_to_id'] == -2) {
				mydb::cxn()->query("insert into vip(item_id, name, contact)
							values(".$item_id.",
									'".mydb::cxn()->real_escape_string(trim($_POST['vip_name']))."',
									'".mydb::cxn()->real_escape_string(trim($_POST['vip_contact']))."')
							on duplicate key update name	= '".mydb::cxn()->real_escape_string(trim($_POST['vip_name']))."',
													contact	= '".mydb::cxn()->real_escape_string(trim($_POST['vip_contact']))."'") or die("Error adding VIP info for this item: " . mydb::cxn()->error);
			}
		}
		else {
			// This is a BULK ITEM (with a quantity)
			if($_POST['quantity'] == '') $quantity = 0;
			else $quantity = mydb::cxn()->real_escape_string(trim($_POST['quantity']));
			
			mydb::cxn()->query("insert into inventory (quantity, item_type, color, size, description, restock_trigger, restock_to_level, item_source, note)
								values (".$quantity.",
										'".mydb::cxn()->real_escape_string(trim($_POST['item_type']))."',
										'".mydb::cxn()->real_escape_string(trim($_POST['color']))."',
										'".mydb::cxn()->real_escape_string(trim($_POST['size']))."',
										'".mydb::cxn()->real_escape_string(trim($_POST['description']))."',
										'".mydb::cxn()->real_escape_string(trim($_POST['restock_trigger']))."',
										'".mydb::cxn()->real_escape_string(trim($_POST['restock_to_level']))."',
										'".mydb::cxn()->real_escape_string(trim($_POST['item_source']))."',
										'".$note."')");
			
			if(mydb::cxn()->error != '') throw new Exception("There was an error adding your item to the inventory: " . mydb::cxn()->error);
			$item_id = mydb::cxn()->insert_id;
		}
		
		echo "<span class=\"highlight1\" style=\"display:block\">Item successfully added</span><br />";
		
		update_item_history($item_id, 'created', '', '');
		if(($_POST['checked_out_to_id'] != -1) && ($_POST['serial_no'] != '')) {
			if($_POST['checked_out_to_id'] != -2) update_item_history($item_id, 'checked_out_to_id', -1, $_POST['checked_out_to_id']);
			else update_item_history($item_id, 'checked_out_to_name', -1, $_POST['vip_name']);
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}
} // END function add_item()

//==========================================================================================================
function rm_item_form($id, $php_self) {
	
	$output = '';
	try {
		//Get the requested item info (1 item to be deleted)
		$query = "	SELECT
						inventory.id,
						inventory.serial_no,
						inventory.quantity,
						inventory.restock_trigger,
						inventory.restock_to_level,
						inventory.item_source,
						inventory.item_type,
						inventory.color,
						inventory.size,
						inventory.description,
						inventory.item_condition,
						inventory.checked_out_to_id,
						concat(crewmembers.firstname,' ', crewmembers.lastname) as checked_out_to,
						inventory.note,
						inventory.usable
					FROM inventory LEFT OUTER JOIN crewmembers
					ON inventory.checked_out_to_id = crewmembers.id
					WHERE inventory.id like '".$id."'";
					
		$result = mydb::cxn()->query($query);
		if(mydb::cxn()->error != '') throw new Exception("There was an error verifying which item to delete (Nothing has been deleted): " . mydb::cxn()->error);
		$row = $result->fetch_assoc();
		
		// Shorten notes to 25 characters to save space on screen
		$full_note = htmlentities($row['note']);
		$short_note = substr($full_note,0,25);
		if(strlen($full_note) > strlen($short_note)) $short_note .= "...";
		
		// Shorten item_source to 25 characters to save space on screen
		$full_item_source = htmlentities($row['item_source']);
		$short_item_source = substr($full_item_source,0,25);
		if(strlen($full_item_source) > strlen($short_item_source)) $short_item_source .= "...";
		
		if($row['serial_no'] != '') {
			// This is an ACCOUNTABLE ITEM (with a serial number)
			
			if($row['usable']) $usable = "Yes";
			else $usable = "No";
				
			$table_headers = "	<tr><th>Serial #</th>
									<th>Item Type</th>
									<th>Size</th>
									<th>Color</th>
									<th>Description</th>
									<th>Checked Out To</th>
									<th>Condition</th>
									<th>Usable</th>
									<th>Note</th>
								</tr>\n";
		
			$output .= "	<form action=\"".$php_self."?function=rm_item\" method=\"POST\">
							<table>\n";
			
			$output .= $table_headers;
			
			$output .= "<tr class=\"odd\">
							<td>".htmlentities($row['serial_no'])."</td>
							<td>".htmlentities($row['item_type'])."</td>
							<td>".htmlentities($row['size'])."</td>
							<td>".htmlentities($row['color'])."</td>
							<td>".htmlentities($row['description'])."</td>
							<td>".htmlentities($row['checked_out_to'])."</td>
							<td>".htmlentities($row['item_condition'])."</td>
							<td>".$usable."</td>
							<td style=\"text-transform:none;\">".$short_note."</td>
						</tr>";
		}
		else {
			// This is a BULK ITEM (with a quantity)
			$table_headers = "	<tr><th>Quantity In Stock</th>
									<th>Restock Trigger</th>
									<th>Restock To...</th>
									<th>Item Type</th>
									<th>Size</th>
									<th>Color</th>
									<th>Description</th>
									<th>Note</th>
									<th>Item Source</th>
								</tr>\n";
		
			$output .= "	<form action=\"".$php_self."?function=rm_item\" method=\"POST\">
							<table>\n";
			
			$output .= $table_headers;
			
			$output .= "<tr class=\"odd\"><td>".htmlentities($row['quantity'])."</td>
							<td>".htmlentities($row['restock_trigger'])."</td>
							<td>".htmlentities($row['restock_to_level'])."</td>
							<td>".htmlentities($row['item_type'])."</td>
							<td>".htmlentities($row['size'])."</td>
							<td>".htmlentities($row['color'])."</td>
							<td>".htmlentities($row['description'])."</td>
							<td style=\"text-transform:none;\">".$short_note."</td>
							<td>".htmlentities($row['item_source'])."</td>
						</tr>";
			
		}
		
		$output .= "<tr><td colspan=\"9\" style=\"text-align:left;\">"
							."<input type=\"submit\" value=\"Delete This Item\" style=\"font-weight:bold; font-size:2em;width:10em;height:2em;background-color:#c33;border:2px solid #666;\">"
					."</td></tr>
				</table>
				<input type=\"hidden\" name=\"status\" value=\"remove\">
				<input type=\"hidden\" name=\"id\" value=\"".$id."\">
				</form>\n";
		
		$output .= "	<br><br>
						This operation cannot be undone!<br>
						Please make sure that you are deleting the correct item.";
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
	echo $output;
}

//==========================================================================================================
function rm_item() {
	$result = mydb::cxn()->query("DELETE from inventory WHERE id like '".$_POST['id']."'") or die("Error removing item from inventory: " . mydb::cxn()->error);
	$result = mydb::cxn()->query("DELETE from inventory_history WHERE item_id like '".$_POST['id']."'") or die("Error removing item from inventory history: " . mydb::cxn()->error);
	
	echo "<span class=\"highlight1\" style=\"display:block\">Item Removed.</span><br />";
}

//==========================================================================================================
function edit_item_form($id, $php_self) {
	$output = '';
	try {
		//Get current item info
		$query = "	SELECT
						inventory.id,
						inventory.serial_no,
						inventory.quantity,
						inventory.item_type,
						inventory.color,
						inventory.size,
						inventory.description,
						inventory.restock_trigger,
						inventory.restock_to_level,
						inventory.item_source,
						inventory.item_condition,
						inventory.checked_out_to_id,
						inventory.note,
						inventory.usable,
						vip.name as vip_name,
						vip.contact as vip_contact
				FROM			inventory
				LEFT OUTER JOIN	vip
					ON (inventory.id = vip.item_id)
				WHERE inventory.id like '".$id."'";
				
		$result = mydb::cxn()->query($query);
		if(mydb::cxn()->error != '') throw new Exception("There was an error retrieving the item for you to edit: " . mydb::cxn()->error);
		
		$row = $result->fetch_assoc();
		if($row['serial_no'] != '') $item_is_bulk = 0;
		else $item_is_bulk = 1;
		
		//Get current roster
		$query = "	SELECT
						concat(crewmembers.firstname, ' ', crewmembers.lastname) as name,
						crewmembers.id as id
					FROM		crewmembers
					INNER JOIN	roster
					ON crewmembers.id = roster.id
					WHERE roster.year like '" . date('Y') . "'
					ORDER BY crewmembers.firstname,crewmembers.lastname";
					
		$result = mydb::cxn()->query($query);
		
		$option_menu =	"<option value=\"-1\">[None]</option>\n"
					.	"<option value=\"-2\"";
		if($row['checked_out_to_id'] == -2) $option_menu .= " SELECTED";
		$option_menu .=	">[VIP]</option>\n";
		
		$checked_out_to_name = "";
		while($data = $result->fetch_assoc()) {
			if($row['checked_out_to_id'] == $data['id']) {
				$option_menu .= "<option value=\"" . $data['id'] . "\" SELECTED>" . htmlentities($data['name']) . "</option>\n";
				$checked_out_to_name = htmlentities($data['name']);
			}
			else $option_menu .= "<option value=\"" . $data['id'] . "\">" . htmlentities($data['name']) . "</option>\n";
		}
		
		if(!$item_is_bulk) {
			// This is an ACCOUNTABLE ITEM (with a serial number)
		
			//Build dropdown for 'usable'
			if($row['usable'] == 0) $usable_menu = "<option value=\"0\" SELECTED>No</option>\n
													<option value=\"1\">Yes</option>";
			else $usable_menu = "<option value=\"0\">No</option>\n
								<option value=\"1\" SELECTED>Yes</option>";
			
			$table_headers = "	<tr><th>Serial #</th>
									<th>Item Type</th>
									<th>Size</th>
									<th>Color</th>
									<th>Description</th>
									<th>Checked Out To</th>
									<th>Condition</th>
									<th>Usable</th>
									<th>Note</th>
								</tr>\n";
			
			$output .= "	<form action=\"". $php_self . "?function=edit_item&id=".$id."\" method=\"post\" name=\"item_info_form\">
							<input type=\"hidden\" name=\"hidden_vip_name\" value=\"".$row['vip_name']."\">
							<input type=\"hidden\" name=\"hidden_vip_contact\" value=\"".htmlentities($row['vip_contact'])."\">
							<table>
							<tr><td colspan=\"3\"><a href=\"".$php_self."?function=rm_item&id=".$id."\">Remove Item From Inventory</a></td><td colspan=\"5\"></td>
							<td><a href=\"../inventory/blm_receipt_for_property.php?"
							."serial_no=".urlencode(htmlentities($row['serial_no']))
							."&description=".urlencode(htmlentities($row['description']))
							."&item_type=".urlencode(htmlentities($row['item_type']))
							."&checked_out_to=".urlencode($checked_out_to_name)
							."&checked_out_by=".urlencode($_SESSION['user_real_name'])."\">Print BLM Property Form</a><td></tr>"
						. $table_headers
						."<tr>	<td class=\"form\"><input type=\"text\" name=\"serial_no\" value=\"".htmlentities($row['serial_no'])."\" style=\"width:75px;\" class=\"entry_cell\"></td>
								<td class=\"form\"><input type=\"text\" name=\"item_type\" value=\"".htmlentities($row['item_type'])."\" style=\"width:100px;\" class=\"entry_cell\"></td>
								<td class=\"form\"><input type=\"text\" name=\"size\" value=\"".htmlentities($row['size'])."\" style=\"width:60px;\" class=\"entry_cell\"></td>
								<td class=\"form\"><input type=\"text\" name=\"color\" value=\"".htmlentities($row['color'])."\" style=\"width:50px;\" class=\"entry_cell\"></td>
								<td class=\"form\"><input type=\"text\" name=\"description\" value=\"".htmlentities($row['description'])."\" style=\"width:200px;\" class=\"entry_cell\"></td>
								<td class=\"form\"><select name=\"checked_out_to_id\" onchange=\"add_vip_menu_if_needed('update')\" style=\"width:100px;\" class=\"entry_cell\">\n"
								. $option_menu
							. "	</select></td>
								<td class=\"form\"><input type=\"text\" name=\"item_condition\" value=\"".htmlentities($row['item_condition'])."\" style=\"width:75px;\" class=\"entry_cell\"></td>
								<td class=\"form\"><select name=\"usable\" style=\"width:50px; font-size:9px;\">\n"
								.$usable_menu
							. " </select></td>
								<td class=\"form\"><input type=\"text\" name=\"note\" value=\"".htmlentities($row['note'])."\" style=\"width:150px;\" class=\"entry_cell\"></td>
							</tr>\n";
				
			$output .= "<tr><td colspan=\"5\"></td><td class=\"form\" id=\"vip_menu_div\" colspan=\"4\"></td></tr>\n";
			
		}
		else {
			// This is a BULK ITEM (with a quantity)
			$table_headers = "	<tr><th>Quantity In Stock</th>
									<th>Restock Trigger</th>
									<th>Restock-To Level</th>
									<th>Item Type</th>
									<th>Size</th>
									<th>Color</th>
									<th>Description</th>
									<th>Note</th>
									<th>Issue 1 To:</th>
									<th>Item Source</th>
								</tr>\n";
			
			$output .= "	<form action=\"". $php_self . "?function=edit_item&id=".$id."\" method=\"post\" name=\"item_info_form\">"
							."<input type=\"hidden\" name=\"hidden_vip_name\" value=\"".$row['vip_name']."\">\n"
							."<input type=\"hidden\" name=\"hidden_vip_contact\" value=\"".htmlentities($row['vip_contact'])."\">\n"
							."<table>\n"
							."<tr>	<td colspan=\"9\" style=\"text-align:left\"><a href=\"".$php_self."?function=rm_item&id=".$id."\">Remove Item From Inventory</a></td></tr>\n"
							. $table_headers
							."<tr>	<td class=\"form\" style=\"width:auto;\"><input type=\"text\" name=\"quantity\" value=\"".htmlentities($row['quantity'])."\" class=\"entry_cell\" style=\"width:auto;\"></td>
									<td class=\"form\" style=\"width:auto;\"><input type=\"text\" name=\"restock_trigger\" value=\"".htmlentities($row['restock_trigger'])."\" class=\"entry_cell\" style=\"width:auto;\"></td>
									<td class=\"form\" style=\"width:auto;\"><input type=\"text\" name=\"restock_to_level\" value=\"".htmlentities($row['restock_to_level'])."\" class=\"entry_cell\" style=\"width:auto;\"></td>
									<td class=\"form\" style=\"width:100px;\"><input type=\"text\" name=\"item_type\" value=\"".htmlentities($row['item_type'])."\" class=\"entry_cell\" style=\"width:100px;\"></td>
									<td class=\"form\" style=\"width:60px;\"><input type=\"text\" name=\"size\" value=\"".htmlentities($row['size'])."\" class=\"entry_cell\" style=\"width:60px;\"></td>
									<td class=\"form\" style=\"width:50px;\"><input type=\"text\" name=\"color\" value=\"".htmlentities($row['color'])."\" class=\"entry_cell\" style=\"width:50px;\"></td>
									<td class=\"form\" style=\"width:200px;\"><input type=\"text\" name=\"description\" value=\"".htmlentities($row['description'])."\" class=\"entry_cell\" style=\"width:200px;\"></td>
									<td class=\"form\" style=\"width:150px;\"><input type=\"text\" name=\"note\" value=\"".htmlentities($row['note'])."\" class=\"entry_cell\" style=\"width:150px;\"></td>
									<td class=\"form\"><select name=\"checked_out_to_id\" onchange=\"add_vip_menu_if_needed('update')\" style=\"width:100px;\" class=\"entry_cell\">\n"
								. $option_menu
							. "	</select></td>
									<td class=\"form\" style=\"width:200px;\"><input type=\"text\" name=\"item_source\" value=\"".htmlentities($row['item_source'])."\" class=\"entry_cell\" style=\"width:200px;\"></td>
							</tr>\n";
			$output .= "<tr><td colspan=\"8\"></td><td class=\"form\" id=\"vip_menu_div\" colspan=\"2\"></td></tr>\n";
		}
		
		$output .= "<tr>
						<td colspan=\"9\" style=\"text-align:left;\">
							<input type=\"submit\" value=\"Save Changes\" style=\"font-size:1.5em;width:10em;border:2px solid #666;font-family:Verdana, Arial, Helvetica, sans-serif;\">
							<input type=\"button\" value=\"Cancel\" style=\"font-size:1.5em;width:10em;border:2px solid #666;font-family:Verdana, Arial, Helvetica, sans-serif;\" onClick=\"window.location='".$php_self."?function=get_inv&category=".htmlentities($row['item_type'])."';\"><br>
						</td>
					</tr></table>
					<input type=\"hidden\" name=\"status\" value=\"update\">
					<input type=\"hidden\" name=\"id\" value=\"".$id."\">
					</form>\n";
							
		if($item_is_bulk) {
			$output .= "<br /><br />Field Description:<br />
							<table>	<tr><td>Quantity In Stock:</td><td>Enter the quantity of this item that you currently have in stock</td></tr>
									<tr><td>Restock Trigger:</td><td>Enter the minimum stock level for this item.  When the Quantity In Stock reaches this number, you should order more.</td></tr>
									<tr><td>Restock-To Level:</td><td>Enter the quantity you would like to have in stock after a resupply order.</td></tr>
									<tr><td>Item Type:</td><td>Enter the category that this item falls into (i.e. 'Leather Gloves', 'Nomex Pants', 'MRE', etc)</td></tr>
									<tr><td>Size:</td><td>(Optional) Enter the size if applicable. This is mainly for clothing items or gear that has multiple size options.</td></tr>
									<tr><td>Color:</td><td>(Optional) Enter the color of this item for identification purposes.</td></tr>
									<tr><td>Description:</td><td>(Optional) Enter a brief description of this item</td></tr>
									<tr><td>Note:</td><td>(Optional) Enter a brief note about this item</td></tr>
									<tr><td>Item Source:</td><td>(Optional) Enter the purchase source for this item (i.e. NFES #, GSA #, web address, etc)</td></tr>
							</table>\n\n";
		}
		
		$output .= "	<br><br><span style=\"font-weight:bold\">Item History</span> (Last 75 entries)<br>\n";
		$output .= display_item_history($id);
		
		if($row['checked_out_to_id'] == -2) $output .= "<script type=\"text/javascript\">window.onload(add_vip_menu_if_needed('update'));</script>\n";

	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
	echo $output;
}
//==========================================================================================================
function edit_item() {
	try {
		$fields_updated = 0;
		$skip_vip_updates = 0;
		$vip_name_instead_of_id = 0;
		$success = 1;
		$note = mydb::cxn()->real_escape_string(trim($_POST['note']));
		$id = mydb::cxn()->real_escape_string($_POST['id']);
		
		//Get current item info (before update)
		$query = "	SELECT
						inventory.id,
						inventory.serial_no,
						inventory.quantity,
						inventory.item_type,
						inventory.color,
						inventory.size,
						inventory.description,
						inventory.restock_trigger,
						inventory.restock_to_level,
						inventory.item_source,
						inventory.item_condition,
						inventory.checked_out_to_id,
						inventory.note,
						inventory.usable,
						vip.name as vip_name,
						vip.contact as vip_contact
				FROM			inventory
				LEFT OUTER JOIN	vip
					ON (inventory.id = vip.item_id)
				WHERE inventory.id like '".$id."'";
		
		$result = mydb::cxn()->query($query);
		if(mydb::cxn()->error != '') throw new Exception('There was an error updating that item (no changes have been made):'.mydb::cxn()->error);
		
		$row = $result->fetch_assoc();
		
		if(($row['checked_out_to_id'] == -2) && ($_POST['checked_out_to_id'] != -2)) $skip_vip_updates = 1;
		if(($row['checked_out_to_id'] != -2) && ($_POST['checked_out_to_id'] == -2)) $vip_name_instead_of_id = 1;
		
		
		
		$attribute = array('serial_no','quantity','restock_trigger','restock_to_level','item_source','item_type','color','size','description','item_condition','checked_out_to_id','note','usable','vip_name','vip_contact');
		for($i=0;$i<(count($attribute)-1-(2*$skip_vip_updates));$i++) {
			if($row[$attribute[$i]] != $_POST[$attribute[$i]]) {
				
				if($attribute[$i]=='vip_name'){
					if($vip_name_instead_of_id) {
						update_item_history($_POST['id'], 'checked_out_to_id', mydb::cxn()->real_escape_string($row['checked_out_to_id']), mydb::cxn()->real_escape_string(trim($_POST['vip_name'])));
					}
					else update_item_history($_POST['id'], 'checked_out_to_name', mydb::cxn()->real_escape_string($row['vip_name']), mydb::cxn()->real_escape_string(trim($_POST['vip_name'])));
				}
				elseif(($attribute[$i] != 'checked_out_to_id') || !$vip_name_instead_of_id) {
					if(($attribute[$i] == 'checked_out_to_id') && ($skip_vip_updates)) update_item_history($_POST['id'], 'checked_out_to_id', mydb::cxn()->real_escape_string($row['vip_name']), mydb::cxn()->real_escape_string(trim($_POST['checked_out_to_id'])));
					else {
						if($row['serial_no'] == '' && ($attribute[$i] == 'checked_out_to_id' || $attribute[$i] == 'usable')) {/* This is a BULK ITEM, ignore certain fields */}
						else update_item_history($_POST['id'], $attribute[$i], mydb::cxn()->real_escape_string($row[$attribute[$i]]), mydb::cxn()->real_escape_string(trim($_POST[$attribute[$i]])));
					}
				}
				$fields_updated++;
			}
		}
		
		//If item is being checked out to a VIP, make sure that contact info is supplied
		if(((trim($_POST['vip_name']) == '') || (trim($_POST['vip_contact']) == '')) && ($_POST['checked_out_to_id'] == -2)) $missing_info = 1;
		else $missing_info = 0;
		
		if(($fields_updated > 0) && !$missing_info) {
			
			if(trim($_POST['serial_no']) == ''){
				// This is a BULK ITEM (with a quantity)
				
				$quantity = (int)(mydb::cxn()->real_escape_string(trim($_POST['quantity'])));
				
				if($_POST['checked_out_to_id'] != -1) {
					//This is a BULK ITEM where the "checked_out_to" field has been set.  This means that a new item of this
					//type should be created with a QTY of 1 and the QTY of THIS item should be decremented by 1.
					$quantity--;
					$item_source = $_POST['item_source'];
					$_POST['item_source'] = $_POST['id']; // The 'source' is set to the id of the 'parent' item
					add_item();
					$_POST['item_source'] = $item_source;
				}
				$query = "	UPDATE inventory
							SET	serial_no = '',
								quantity = '".$quantity."',
								item_type = '".mydb::cxn()->real_escape_string(trim($_POST['item_type']))."',
								color = '".mydb::cxn()->real_escape_string(trim($_POST['color']))."',
								size = '".mydb::cxn()->real_escape_string(trim($_POST['size']))."',
								description = '".mydb::cxn()->real_escape_string(trim($_POST['description']))."',
								item_condition = '',
								restock_trigger = '".mydb::cxn()->real_escape_string(trim($_POST['restock_trigger']))."',
								restock_to_level = '".mydb::cxn()->real_escape_string(trim($_POST['restock_to_level']))."',
								item_source = '".mydb::cxn()->real_escape_string(trim($_POST['item_source']))."',
								note = '".$note."',
								usable = '1'
							WHERE id like '".$_POST['id']."'";
			}
			else {
				// This is an ACCOUNTABLE ITEM (with a serial number)
				$query = "	UPDATE inventory
							SET	serial_no = '".mydb::cxn()->real_escape_string(trim($_POST['serial_no']))."',
								quantity = NULL,
								item_type = '".mydb::cxn()->real_escape_string(trim($_POST['item_type']))."',
								color = '".mydb::cxn()->real_escape_string(trim($_POST['color']))."',
								size = '".mydb::cxn()->real_escape_string(trim($_POST['size']))."',
								description = '".mydb::cxn()->real_escape_string(trim($_POST['description']))."',
								item_condition = '".mydb::cxn()->real_escape_string(trim($_POST['item_condition']))."',
								restock_trigger = NULL,
								restock_to_level = NULL,
								item_source = NULL,
								checked_out_to_id = ".$_POST['checked_out_to_id'].",
								note = '".$note."',
								usable = '".$_POST['usable']."'
							WHERE id like '".$_POST['id']."'";
			}		
			mydb::cxn()->query($query);
			if(mydb::cxn()->error != '') throw new Exception("There was an error updating your item in the inventory: " . mydb::cxn()->error);
			
			if($_POST['checked_out_to_id'] == -2) {
				mydb::cxn()->query("insert into vip(item_id, name, contact)
							values(".$_POST['id'].",
									'".mydb::cxn()->real_escape_string(trim($_POST['vip_name']))."',
									'".mydb::cxn()->real_escape_string(trim($_POST['vip_contact']))."')
							on duplicate key update name	= '".mydb::cxn()->real_escape_string(trim($_POST['vip_name']))."',
													contact	= '".mydb::cxn()->real_escape_string(trim($_POST['vip_contact']))."'") or die("Error updating VIP info for this item: " . mydb::cxn()->error);
			}
			else {
				//Item is NOT checked out to a VIP, delete vip entry for this item (if it exists)
				mydb::cxn()->query("DELETE FROM vip WHERE vip.item_id like '".$_POST['id']."'") or die("Error removing VIP entry for this item: " . mydb::cxn()->error);
			}
			
			echo "<span class=\"highlight1\" style=\"display:block\">Item successfully updated</span><br />";
		}
		else {
			if($missing_info) throw new Exception("<span class=\"highlight1\" style=\"display:block\">You must provide contact info or a name for the VIP</span><br />\n");
			if($fields_updated <= 0) throw new Exception("<span class=\"highlight1\" style=\"display:block\">No fields were updated</span><br />");
		}
		
	} catch (Exception $e) {
		echo $e->getMessage();
		$success = 0;
	}
	
	return $success;
}

//==========================================================================================================
function check_in($id) {
	$result = mydb::cxn()->query("SELECT checked_out_to_id FROM inventory WHERE id like '".$id."'") or die("Error during item check-in: " . mydb::cxn()->error);
	$row = $result->fetch_assoc();
	$old_value = $row['checked_out_to_id'];
	
	if($old_value == -2) {
		$result = mydb::cxn()->query("SELECT name FROM vip WHERE item_id like '".$id."'") or die("Error during VIP item check-in: " . mydb::cxn()->error);
		$row = $result->fetch_assoc();
		$old_value = $row['name'];
		
		mydb::cxn()->query("	UPDATE inventory
						SET checked_out_to_id = -1
						WHERE id like '".$id."'") or die("Error during VIP item check-in: " . mydb::cxn()->error);
						
		mydb::cxn()->query("	DELETE FROM vip
						WHERE item_id like '".$id."'") or die("Error during VIP item check-in: " . mydb::cxn()->error);
		
		update_item_history($id, 'checked_out_to_name', $old_value, '-1');
	}
	else {
		mydb::cxn()->query("	UPDATE inventory
						SET checked_out_to_id = -1
						WHERE id like '".$id."'") or die("Error during item check-in: " . mydb::cxn()->error);
		
		update_item_history($id, 'checked_out_to_id', $old_value, '-1');
	}
}

//==========================================================================================================
function check_in_bulk($id) {
	// $id is the id of an item that already has a 'checked_out_to' value assigned.
	// This function will decrement the QTY of this item, and increment the QTY of the parent item (who's ID is stored in the 'item_source' field
	// If this item's QTY becomes 0, the item will be destroyed from the inventory
	$query = "SELECT quantity, item_source from inventory WHERE id = '".$id."'";
	$result = mydb::cxn()->query($query);
	$row = $result->fetch_assoc();
	$issued_qty = $row['quantity'];
	$parent_id = $row['item_source'];
	
	$query = "SELECT quantity from inventory WHERE id = '".$parent_id."'";
	$result = mydb::cxn()->query($query);
	$row = $result->fetch_assoc();
	$parent_qty = $row['quantity'];
	
	$new_issued_qty = (int)$issued_qty - 1;
	$new_parent_qty = (int)$parent_qty + 1;
	
	$query = "";
	if($new_issued_qty <= 0) {
		$result = mydb::cxn()->query("DELETE from inventory WHERE id = '".$id."'");
	}
	else {
		$result = mydb::cxn()->query("UPDATE inventory SET quantity = ".$new_issued_qty." WHERE id = ".$id);
	}
	$result = mydb::cxn()->query("UPDATE inventory SET quantity = ".$new_parent_qty." WHERE id = ".$parent_id);
	
	
} // END function check_in_bulk
//==========================================================================================================
function check_out_bulk($id) {
	// $id is the id of an item that already has a 'checked_out_to' value assigned.
	// This function will increment the QTY of this item, and decrement the QTY of the parent item (who's ID is stored in the 'item_source' field
	$query = "SELECT quantity, item_source from inventory WHERE id = '".$id."'";
	$result = mydb::cxn()->query($query);
	$row = $result->fetch_assoc();
	$issued_qty = $row['quantity'];
	$parent_id = $row['item_source'];
	
	$query = "SELECT quantity from inventory WHERE id = '".$parent_id."'";
	$result = mydb::cxn()->query($query);
	$row = $result->fetch_assoc();
	$parent_qty = $row['quantity'];
	
	$new_issued_qty = (int)$issued_qty + 1;
	$new_parent_qty = (int)$parent_qty - 1;
	
	$query = "";
	if($new_parent_qty >= 0) {
		$result = mydb::cxn()->query("UPDATE inventory SET quantity = ".$new_issued_qty." WHERE id = '".$id."'");
		$result = mydb::cxn()->query("UPDATE inventory SET quantity = ".$new_parent_qty." WHERE id = '".$parent_id."'");
	}
	
} // END function check_out_bulk
//==========================================================================================================
function personal_gear_list($id, $php_self) {
	
	//Get crewmember name from ID
	$query = "SELECT CONCAT(firstname,' ',lastname) as name FROM crewmembers WHERE id = ".$id;
	$result = mydb::cxn()->query($query);
	$row = $result->fetch_assoc();
	$name = $row['name'];
	
	$table_headers = "	<tr><th>Serial #</th>
							<th>Item Type</th>
							<th>Size</th>
							<th>Color</th>
							<th>Description</th>
							<th>Condition</th>
							<th></th>
						</tr>\n";
	
	$query = "	SELECT inventory.id, inventory.serial_no, inventory.item_type, inventory.color, inventory.size, description, item_condition
				FROM inventory LEFT OUTER JOIN crewmembers
				ON inventory.checked_out_to_id = crewmembers.id
				WHERE crewmembers.id like '".$id."'
				ORDER BY item_type, description, size, color";
	
	$result = mydb::cxn()->query($query) or die("Error generating personal gear list: " . mydb::cxn()->error);

	$row_count = 0;
	while($row = $result->fetch_assoc()) {
		$row_count++;
		
		//Build arrays to POST to PDF generator
		$pg_array[] = array($row['serial_no'],
							ucwords($row['item_type']),
							ucwords($row['size']),
							ucwords($row['color']),
							ucwords($row['description']),
							ucwords($row['item_condition']));
		$pg_header = array("Serial #","Item Type","Size","Color","Description","Condition");
							
		// Color code alternating rows
		if($row_count % 2 == 0) $table .= "<tr class=\"evn\" id=\"item_".$row['id']."_row\">";
		else $table .= "<tr class=\"odd\" id=\"item_".$row['id']."_row\">";
		
		$table .= "	<td><a href=\"".$php_self."?function=edit_item&id=".$row['id']."\">".htmlentities($row['serial_no'])."</a></td>
				<td>".htmlentities($row['item_type'])."</td>
				<td>".htmlentities($row['size'])."</td>
				<td>".htmlentities($row['color'])."</td>
				<td>".htmlentities($row['description'])."</td>
				<td>".htmlentities($row['item_condition'])."</td>"
				//."<td><a href=\"".$php_self."?function=check_in&id=".$row['id']."&redirect=self\" style=\"width:10px;height:8px;\"><img src=\"images/check_in.png\" style=\"width:10px;height:8px;\" alt=\"Check In\" title=\"Check In\"></a></td></tr>\n";
				."<td id=\"item_".$row['id']."_button\"><img src=\"images/check_in.png\" style=\"width:10px;height:8px;\" onClick=\"checkIn(".$row['id'].");\" alt=\"Check In\" title=\"Check In\"></td></tr>\n";
	}
	
	$_SESSION['pg_array'] = $pg_array;
	$_SESSION['pg_header']= $pg_header;
	$_SESSION['pg_crewmember_name'] = $name;
	
	echo "<table>\n";
	echo "<tr class=\"new_cat_row\"><td class=\"new_cat_cell\" colspan=\"5\">$name : Personal Gear List</td>
			<td style=\"vertical-align:bottom\"><a href=\"http://" . $_SERVER['HTTP_HOST'] ."/personal_gear_list.php\">[Printer Friendly View]</a></td></tr>\n";
			
	echo $table_headers;
	echo $table;
	echo "</table>\n";
}

//==========================================================================================================
function update_item_history($item_id, $attribute, $old_value, $new_value) {
	//$time = time() - (date("I") * 3600); //Helitack server is on MOUNTAIN TIME, correct by one hour for PACIFIC TIME if daylight savings
	$time = time();
	
	if($attribute == "checked_out_to_name") {
		if($old_value == -1) $old_value = "unassigned";
		if($new_value == -1) $new_value = "unassigned";
	}
	//If the attribute is "checked_out_to_id", look up the name (so as not to display a meaningless crewmember id number)
	if($attribute == 'checked_out_to_id') {
		$attribute = "checked_out_to_name";
		if($old_value == -1 || $old_value == '') $old_value = "unassigned";
		elseif(is_numeric($old_value)) {
			$result = mydb::cxn()->query("SELECT concat(firstname,' ',lastname) as name from crewmembers WHERE id like '".$old_value."'");
			$row = $result->fetch_assoc();
			$old_value = $row['name'];
		}

		if($new_value == -1 || $new_value == '') $new_value = "unassigned";
		elseif(is_numeric($new_value)) {
			$result = mydb::cxn()->query("SELECT concat(firstname,' ',lastname) as name from crewmembers WHERE id like '".$new_value."'");
			$row = $result->fetch_assoc();
			$new_value = $row['name'];
		}
	}

	$query = "	INSERT INTO inventory_history (item_id, attribute, old_value, new_value, changed_by, date)
				values(".$item_id.",'".$attribute."','".$old_value."','".$new_value."','".$_SESSION['username']."',from_unixtime(".$time."))";
	
	
	mydb::cxn()->query($query);
	if(mydb::cxn()->error != '') echo "Error updating item history: " . mydb::cxn()->error . "<br />Query:<br />".$query;

}

//==========================================================================================================
function display_item_history($item_id) {
	$output = '';
	$query = "	SELECT attribute, old_value, new_value, changed_by, unix_timestamp(date) as unix_date
				FROM inventory_history
				WHERE item_id like '".$item_id."'
				ORDER BY date desc
				LIMIT 75";
	
	$result = mydb::cxn()->query($query);
	if(mydb::cxn()->error != '') $output = "There was an error retrieving this item's history: " . mydb::cxn()->error;
	else {
		while($row = $result->fetch_assoc()) {
			$output .= build_plaintext($row['attribute'], htmlentities($row['old_value']), htmlentities($row['new_value']), htmlentities($row['changed_by']), $row['unix_date'])."<br>\n";
		}
	}
	return $output;
}

//==========================================================================================================
function build_plaintext($attribute, $old_value, $new_value, $changed_by, $date) {
	switch($attribute) {
	case serial_no:
		$string = date('d-M-Y H:i',$date) . "\t" . "Serial # changed from '" . $old_value . "' to '" . $new_value . "' by " . $changed_by;
		break;
	case item_type:
		$string = date('d-M-Y H:i',$date) . "\t" . "Item Type changed from '" . $old_value . "' to '" . $new_value . "' by " . $changed_by;
		break;
	case quantity:
		$string = date('d-M-Y H:i',$date) . "\t" . "Quantity changed from '" . $old_value . "' to '" . $new_value . "' by " . $changed_by;
		break;
	case restock_trigger:
		$string = date('d-M-Y H:i',$date) . "\t" . "Restock Trigger changed from '" . $old_value . "' to '" . $new_value . "' by " . $changed_by;
		break;
	case restock_to_level:
		$string = date('d-M-Y H:i',$date) . "\t" . "Restock-To Level changed from '" . $old_value . "' to '" . $new_value . "' by " . $changed_by;
		break;
	case item_source:
		$string = date('d-M-Y H:i',$date) . "\t" . "Item Source changed from '" . $old_value . "' to '" . $new_value . "' by " . $changed_by;
		break;
	case size:
		$string = date('d-M-Y H:i',$date) . "\t" . "Size changed from '" . $old_value . "' to '" . $new_value . "' by " . $changed_by;
		break;
	case color:
		$string = date('d-M-Y H:i',$date) . "\t" . "Color changed from '" . $old_value . "' to '" . $new_value . "' by " . $changed_by;
		break;
	case description:
		$string = date('d-M-Y H:i',$date) . "\t" . "Description changed from '" . $old_value . "' to '" . $new_value . "' by " . $changed_by;
		break;
	case checked_out_to_name:
	case checked_out_to_id:
	case checked_out_to:
		if(($old_value == 'unassigned') && ($new_value != 'unassigned')) $string = date('d-M-Y H:i',$date) . "\t" . "<span class=\"check_out\">Checked out to '" . $new_value . "' by " . $changed_by . "</span>";
		elseif(($old_value != 'unassigned') && ($new_value != 'unassigned')) $string = date('d-M-Y H:i',$date) . "\t" . "<span class=\"check_out\">Checked out to '" . $new_value . "' by " . $changed_by . "</span><br>\n"
																					. date('d-M-Y H:i',$date) . "\t" . "<span class=\"check_in\">Checked in from '" . $old_value . "' by " . $changed_by . "</span>";
		else $string = date('d-M-Y H:i',$date) . "\t" . "<span class=\"check_in\">Checked in from '" . $old_value . "' by " . $changed_by . "</span>";
		break;
	case item_condition:
		$string = date('d-M-Y H:i',$date) . "\t" . "Item Condition changed from '" . $old_value . "' to '" . $new_value . "' by " . $changed_by;
		break;
	case usable:
		if(($old_value == 0) && ($new_value == 1)) $string = date('d-M-Y H:i',$date) . "\t" . "Item marked USABLE by " . $changed_by;
		elseif(($old_value == 1) && ($new_value == 0)) $string = date('d-M-Y H:i',$date) . "\t" . "Item marked UNUSABLE by " . $changed_by;
		break;
	case note:
		$old_value = old_value;
		$new_value = $new_value;
		if(($old_value == '') && ($new_value != '')) $string = date('d-M-Y H:i',$date) . "\t" . "Note added: '" . $new_value . "' by " . $changed_by;
		elseif(($new_value == '') && ($old_value != '')) $string = date('d-M-Y H:i',$date) . "\t" . "Note removed: '" . $old_value . "' by " . $changed_by;
		else $string = date('d-M-Y H:i',$date) . "\t" . "Note changed from '" . $old_value . "' to '" . $new_value . "' by " . $changed_by;
		break;
	case created:
		$string = date('d-M-Y H:i',$date) . "\t" . "Added to inventory by " . $changed_by;
		break;
	case vip_contact:
		$string = date('d-M-Y H:i',$date) . "\t" . "VIP contact info changed from '".$old_value."' to '".$new_value."' by " . $changed_by;
		break;
	default:
		break;
	}//END switch
	
	return $string;
}

function my_is_int($var) {
	return (is_numeric($var)&&(intval($var)==floatval($var)));
}
?>
