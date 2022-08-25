<?php
	if(isset($_GET['session_id'])) session_id($_GET['session_id']);
	session_start();
	require_once(__DIR__ . "/../includes/auth_functions.php");
	require_once(__DIR__ . "/../classes/mydb_class.php");
    require_once(__DIR__ . "/../classes/Config.php");


// if(substr(strtolower($_SERVER['PHP_SELF']),1,9) == "inventory") header('location: http://inventory.siskiyourappellers.com');
//	$php_self = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    $php_self = ConfigService::getConfig()->app_url . $_SERVER['PHP_SELF'];
	$php_self_with_query = $php_self . "?" . $_SERVER['QUERY_STRING'];
	$_SESSION['last_page'] = $_SESSION['this_page'];
	$_SESSION['this_page'] = $php_self_with_query;
	
	if(isset($_GET['logout']) && ($_GET['logout'] == 1)) {
		session_destroy();
		session_start();
	}

	if(($_SESSION['logged_in'] == 1) && check_access("inventory")) {
		include("../includes/inv_functions.php"); //Contains functions: add_item, rm_item, get_inv
		
		if(isset($_GET['sort_by'])) $_SESSION['sort_view_by'] = $_GET['sort_by'];
		elseif (!isset($_SESSION['sort_view_by'])) $_SESSION['sort_view_by'] = "item_type";
		
		if(isset($_GET['category'])) $_SESSION['inventory_category_view'] = $_GET['category'];
		
		if(!isset($_SESSION['year']) || ($_SESSION['year'] == '')) $_SESSION['year'] = date("Y");
		if(isset($_GET['year'])) $_SESSION['year'] = $_GET['year'];
		
	}//END if($_SESSION['logged_in'] == 1)
	else {
        header('location: ' . ConfigService::getConfig()->app_url . '/admin/index.php');
    }

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Inventory :: Wild Rivers Ranger District</title>

<?php include_once(__DIR__ . "/../classes/Config.php"); ?>
<base href="<?php echo ConfigService::getConfig()->app_url ?>" />

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="inventory, ims, tracking, records, items, gear, equipment, helitack, hecm, crew, prineville" />
<meta name="Description" content="View & update crew gear inventory" />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/inventory.css" />
<style type="text/css">
.entry_cell {
	margin:0px; padding:1px;
	font-size:1em;
	font-family:Arial, Helvetica, sans-serif
}
td.form {
	vertical-align: top;
	padding:0;
	font-size: 11px;
	font-weight: normal;
	text-transform: none;
}
</style>

<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script language="javascript" src="inventory/inventory_ajax/inventory_ajax.js"></script>
<script type="text/javascript" src="scripts/inv_scripts.js"></script>
<script type="text/javascript">
<!--
function redirect(){
    window.location = "<?php print $_SESSION['last_page']; ?>"
}
//-->
</script>
</head>

<?php
	if(isset($_GET['redirect']) && ($_GET['redirect'] != "")) print "<body onload=\"redirect()\">\n";
	else print "<body>\n";
?>
<div id="wrapper" style="height:75px; min-height:75px; width:900px;">
	<div id="banner">
        <a href="/index.php" style="display:block; width:900px; height:75px; padding:0;"><img src="inventory/inv_banner.jpg" style="border:none" alt="Scroll down..." /></a>
    </div>
</div>
<div id="wrapper" style="width:95%;">
	<div id="content">
    	

<?php
	if($_SESSION['logged_in'] == 1) {

		echo "<div id=\"inv_menu\">
		<div style=\"float:left\">Logged in as: ".$_SESSION['username']."</div>\n
				<a href=\"" . $php_self . "?function=get_inv&category=\" class=\"menulink\">Browse Entire Inventory</a> |
				<a href=\"" . $php_self . "?function=restock_list\" class=\"menulink\">Restock List</a> |
				<a href=\"" . $php_self . "?function=add_item\" class=\"menulink\">Add New Item</a> |
				<a href=\"/admin/index.php\" class=\"menulink\">Admin Home</a> |
				<a href=\"/admin/index.php?logout=1\" class=\"menulink\">Logout</a>
			</div><br style=\"clear:left;\">\n";

		switch($_GET['function']) {
		case "add_item":
			if($_POST['status'] == "insert") {
				add_item();
				add_item_form($php_self);
			}
			else add_item_form($php_self);
			break;
			
		case "rm_item":
			if($_POST['status'] == "remove") {
				rm_item();
				$get_inv_result = get_inv();
				display_inv($get_inv_result, $php_self);
			}
			else rm_item_form($_GET['id'], $php_self);
			break;
			
		case "check_in":
			check_in($_GET['id']);
			$get_inv_result = get_inv();
			display_inv($get_inv_result, $php_self);
			break;
			
		case "check_out":
			if($_POST['status'] == "update") {
				edit_item();
				$get_inv_result = get_inv();
				display_inv($get_inv_result, $php_self);
			}
			else edit_item_form($_GET['id'], $php_self);
			break;
			
		case "check_in_bulk":
			// $_GET['id'] is the id of an item that already has a 'checked_out_to' value assigned.
			// This function will decrement the QTY of this item, and increment the QTY of the parent item (who's ID is stored in the 'item_source' field
			// If this item's QTY becomes 0, the item will be destroyed from the inventory
			check_in_bulk($_GET['id']);
			$get_inv_result = get_inv();
			display_inv($get_inv_result, $php_self);
			break;
			
		case "check_out_bulk":
			// $_GET['id'] is the id of an item that already has a 'checked_out_to' value assigned.
			// This function will increment the QTY of this item, and decrement the QTY of the parent item (who's ID is stored in the 'item_source' field
			check_out_bulk($_GET['id']);
			$get_inv_result = get_inv();
			display_inv($get_inv_result, $php_self);
			break;
			
		case "edit_item":
			if(($_POST['status'] == "update") && edit_item()) {
				$get_inv_result = get_inv();
				display_inv($get_inv_result, $php_self);
			}
			else edit_item_form($_GET['id'], $php_self);
			break;
		
		case "personal_gear_list":
			personal_gear_list($_GET['id'], $php_self);
			break;
		
		case "restock_list":
			$restock_result = get_restock_list();
			display_inv($restock_result, $php_self);
			break;
		
		case "get_inv":
		default:
			$get_inv_result = get_inv();
			display_inv($get_inv_result, $php_self);
			break;
		}//END Switch()
	}//End if($_SESSION['logged_in'] == 1)

?>
	</div> <!-- END "content" -->
</div> <!-- END "wrapper" -->

</body>
</html>
