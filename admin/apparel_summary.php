<?php
	session_start();
	require_once("../includes/auth_functions.php");
	
	if(($_SESSION['logged_in'] == 1) && check_access("manage_apparel")) {
		require_once("../classes/mydb_class.php");
	}
	else {
		if($_SESSION['logged_in'] != 1) $_SESSION['intended_location'] = $_SERVER['PHP_SELF'];
		header('location: https://wildrivers.firecrew.us/admin/index.php');
	}
	//-----------------------------------------------------------------------------
	if(preg_match('/^(20)\d{2}$/',$_GET['year']) == 1) $year = $_GET['year']; //Check for 4-digit number between 2000 - 2099
	else $year = date('Y');

	$query = 	 "SELECT id, created_at, order_number, customer_name "
				."FROM ("
				."SELECT * FROM apparel_orders WHERE year(created_at) = '".$year."' ORDER BY created_at DESC) as s "
				."GROUP BY customer_name";
	$result = mydb::cxn()->query($query);
	$order_numbers = array();
	while($row = $result->fetch_assoc()) {
		$order_numbers[] = "'".$row['order_number']."'";
	}
	$query = "SELECT DISTINCT YEAR(created_at) as year FROM apparel_orders ORDER BY created_at DESC";
	$result = mydb::cxn()->query($query);
	while($row = $result->fetch_assoc()) {
		$available_years[] = $row['year'];
	}

	$last_customer = "";
	$row_count = 0;
	$rows_this_customer = 0;
	
	print "<html>\n"
			."<style>\n"
			."	@media screen {\n"
			."		table {border-collapse:collapse;}\n"
			."		th {font-size: 1.2em;border: 1px solid #98BF21;padding: 3px 7px 2px 7px;background-color: #666666;color: white;}\n"
			."		td.even {font-size: 1.2em;border: 1px solid #98BF21;padding: 3px 7px 2px 7px;background-color: #A7C942;color: white;}\n"
			."		td.odd {font-size:1.2em; border:1px solid #98BF21; padding:3px 7px 2px 7px; background-color:#EAF2D3; color:black;}\n"
			."		td.summary {font-size:1.2em; border:none; background-color:white; color:black;}\n"
			."	}\n"
			."	@media print {\n"
			."		table {border-collapse:collapse;}\n"
			."		th {font-size: 1em;border: 1px solid #98BF21;padding: 3px 7px 2px 7px;background-color: #666666;color: white;}\n"
			."		td.even {font-size: 10pt;border: 1px solid #98BF21;padding: 3px 7px 2px 7px;background-color: #A7C942;color: white;}\n"
			."		td.odd {font-size:10pt; border:1px solid #98BF21; padding:3px 7px 2px 7px; background-color:#EAF2D3; color:black;}\n"
			."		td.summary {font-size:10pt; border:none; background-color:white; color:black;}\n"
			."	}\n"
			."</style>\n"
			."<body>\n";
	
	print "<div style=\"width:100%;background-color:#dddddd;padding:3px;\">\n"
		. "<a href=\"index.php\">Admin Home</a> | \n"
		. "<a href=\"".$_SERVER['PHP_SELF']."?view=by_items\">View by Items</a> | \n"
		. "<a href=\"".$_SERVER['PHP_SELF']."?view=by_people\">View by People</a> | \n"
		. "<a href=\"".$_SERVER['PHP_SELF']."?view=by_design\">View by Design</a> | \n"
		. "<a href=\"".$_SERVER['PHP_SELF']."?view=by_payment\">View by Payment Method</a>\n"
		. "</div>\n";
	
	print "<h3 style=\"display: inline;\">SRC Crew Apparel Orders by ";
	switch($_GET['view']) {
		case 'by_items':
			print "Item";
			break;
		case 'by_design':
			print "Design";
			break;
		case 'by_payment':
			print "Payment Method";
			break;
		case 'by_person':
		default:
			print "Person";
			break;
	}
	print " - ".$year."</h3>\n"
			."<form action=\"".$_SERVER['PHP_SELF']."\" method=\"get\">\n"
			."Change Year: <select name=\"year\">\n";
	foreach($available_years as $y) {
		print "<option value=\"".$y."\">".$y."</option>\n";
	}
	print "</select><br />\n"
			.count($order_numbers)." people have placed orders this year.<br /><br />\n";



	switch($_GET['view']) {
		case 'by_design':
	//*************************** VIEW: by_design *****************************
	//************************************************************************
	$query = "SELECT item_description, item_name, item_type, item_size, sum(item_qty) as qty "
			."FROM apparel_orders "
			."WHERE order_number in(".implode(",",$order_numbers).") "
			."GROUP BY item_name, item_type, item_size "
			."ORDER BY item_name, item_type, item_size";
	$result = mydb::cxn()->query($query);
		
	$output_screen_printed =  "<h3 style=\"display: inline;\">Screen-Printed Items</h3><br />\n"
			."<table id=\"screen_print_table\"><tr>"
			."	<th style=\"text-align:left;width:20em;\">Item Name</th>"
			."	<th style=\"width:5em;text-align:left;\">Color</th>"
			."	<th style=\"width:7em;text-align:left;\">Type/Style</th>"
			."	<th style=\"width:8em;\">Size</th>"
			."	<th style=\"\">Quantity</th></tr>";
	
	$output_embroidered =  "<h3 style=\"display: inline;\">Embroidered Items</h3><br />\n"
			."<table id=\"embroidered_table\"><tr>"
			."	<th style=\"text-align:left;width:20em;\">Item Name</th>"
			."	<th style=\"width:5em;text-align:left;\">Color</th>"
			."	<th style=\"width:7em;text-align:left;\">Type/Style</th>"
			."	<th style=\"width:8em;\">Size</th>"
			."	<th style=\"\">Quantity</th></tr>";
			
	$rows = 0;
	$rows_embroidered = 0;
	$rows_screen_printed =0;
	$last_item_description_embroidered = "";
	$last_item_description_screen_printed = "";
	$row_style_embroidered = "odd";
	$row_style_screen_printed = "odd";
	$color = "";
	
	while($row = $result->fetch_assoc()) {
		$rows++;
		//Create 2 separate tables - one for embroidered items and one for screen-printed items.  Color code by item within each table.
		if((strripos($row['item_name'],'screen-printed') === false) && (strripos($row['item_name'],'screen printed') === false)) {
			//This is an EMBROIDERED item
			$rows_embroidered++;
			if($row['item_description'] != $last_item_description_embroidered) {
				$row_style_embroidered = toggle($row_style_embroidered);
			}
			
			if(strpos(strtolower($row['item_name']), "orange") !== false) $color = "Orange";
			elseif(strpos(strtolower($row['item_name']), "black") !== false) $color = "Black";
			else $color = "";
	
			$output_embroidered .= "<tr><td class=\"".$row_style_embroidered."\">".$row['item_description']."</td>\n"
				."<td class=\"".$row_style_embroidered."\">".$color."</td>\n"
				."<td class=\"".$row_style_embroidered."\">".$row['item_type']."</td>\n"
				."<td class=\"".$row_style_embroidered."\">".$row['item_size']."</td>\n"
				."<td class=\"".$row_style_embroidered."\">".$row['qty']."</td></tr>\n";
			$last_item_description_embroidered = $row['item_description'];
		}
		else {
			//This is a SCREEN-PRINTED item
			$rows_screen_printed++;
			if($row['item_description'] != $last_item_description_screen_printed) {
				$row_style_screen_printed = toggle($row_style_screen_printed);
			}
			$output_screen_printed .= "<tr><td class=\"".$row_style_screen_printed."\">".$row['item_description']."</td>\n"
				."<td class=\"".$row_style_screen_printed."\">".$color."</td>\n"
				."<td class=\"".$row_style_screen_printed."\">".$row['item_type']."</td>\n"
				."<td class=\"".$row_style_screen_printed."\">".$row['item_size']."</td>\n"
				."<td class=\"".$row_style_screen_printed."\">".$row['qty']."</td></tr>\n";
			$last_item_description_screen_printed = $row['item_description'];
		}
		
	}
	$output_embroidered .= "</table>\n";
	$output_screen_printed .="</table>\n";
	
	print 	$output_embroidered
			."<br />\n"
			.$output_screen_printed
			."<br />\n";
	
			break; // End: case 'by_design'
		case 'by_items':
	//*************************** VIEW: by_items *****************************
	//************************************************************************
	$query = "SELECT item_description, item_name, item_type, item_size, sum(item_qty) as qty "
			."FROM apparel_orders "
			."WHERE order_number in(".implode(",",$order_numbers).") "
			."GROUP BY item_description, item_type, item_size "
			."ORDER BY item_description, item_type, item_size";
	$result = mydb::cxn()->query($query);
		
	print "<table id=\"order\"><tr>"
		  ."	<th style=\"text-align:left;width:20em;\">Item Name</th>"
		  ."	<th style=\"width:7em;text-align:left;\">Color</th>"
		  ."	<th style=\"width:7em;text-align:left;\">Type/Style</th>"
		  ."	<th style=\"width:8em;\">Size</th>"
		  ."	<th style=\"\">Quantity</th></tr>";
	
	$rows = 0;
	$row_style = "odd";
	$last_item_description = "";
	$color = "";
	while($row = $result->fetch_assoc()) {
		$rows++;
		if($row['item_description'] != $last_item_description) $row_style = toggle($row_style);
		
		if(strpos(strtolower($row['item_name']), "orange") !== false) $color = "Orange";
		elseif(strpos(strtolower($row['item_name']), "black") !== false) $color = "Black";
		else $color = "";
		
		print "<tr><td class=\"".$row_style."\">".$row['item_description']."</td>\n"
				."<td class=\"".$row_style."\">".$color."</td>\n"
				."<td class=\"".$row_style."\">".$row['item_type']."</td>\n"
				."<td class=\"".$row_style."\">".$row['item_size']."</td>\n"
				."<td class=\"".$row_style."\">".$row['qty']."</td></tr>\n";
		$last_item_description = $row['item_description'];
	}
	print "</table>\n<br />\n";
	
	
			break; // End: case 'by_items'


		case 'by_payment':
	//*************************** VIEW: by_payment *****************************
	//************************************************************************
	$query_crew_fund = "SELECT item_description, item_name, item_type, item_size, item_price, qty_for_free, sum(item_qty) as qty "
			."FROM apparel_orders "
			."WHERE order_number in(".implode(",",$order_numbers).") AND qty_for_free = 0 "
			."GROUP BY item_description, item_name, item_type, item_size  "
			."ORDER BY item_description, item_name, item_type, item_size";
	$result_crew_fund = mydb::cxn()->query($query_crew_fund);
	
	$query_purchase_card = "SELECT item_description, item_name, item_type, item_size, item_price, qty_for_free, sum(item_qty) as qty "
			."FROM apparel_orders "
			."WHERE order_number in(".implode(",",$order_numbers).") AND qty_for_free > 0 "
			."GROUP BY item_description, item_name, item_type, item_size  "
			."ORDER BY item_description, item_name, item_type, item_size";
	$result_purchase_card = mydb::cxn()->query($query_purchase_card);
	
	$output_purchase_card =  "<h3 style=\"display: inline;\">Paid for with Purchase Card</h3><br />\n"
			."<table id=\"screen_print_table\"><tr>"
			."	<th style=\"text-align:left;width:20em;\">Item Name</th>"
			."	<th style=\"width:5em;text-align:left;\">Artwork</th>"
			."	<th style=\"width:5em;text-align:left;\">Color</th>"
			."	<th style=\"width:7em;text-align:left;\">Type/Style</th>"
			."	<th style=\"width:8em;\">Size</th>"
			."	<th style=\"\">Quantity</th></tr>";
	
	$output_crew_fund =  "<h3 style=\"display: inline;\">Paid for with Crew Fund</h3><br />\n"
			."<table id=\"embroidered_table\"><tr>"
			."	<th style=\"text-align:left;width:20em;\">Item Name</th>"
			."	<th style=\"width:5em;text-align:left;\">Artwork</th>"
			."	<th style=\"width:5em;text-align:left;\">Color</th>"
			."	<th style=\"width:7em;text-align:left;\">Type/Style</th>"
			."	<th style=\"width:8em;\">Size</th>"
			."	<th style=\"\">Quantity</th></tr>";
			
	$rows = 0;
	$rows_crew_fund = 0;
	$rows_purchase_card =0;
	$last_item_name_crew_fund = "";
	$last_item_name_purchase_card = "";
	$row_style_crew_fund = "odd";
	$row_style_purchase_card = "odd";
	$color = "";
	$artwork = "";
	
	while($row = $result_crew_fund->fetch_assoc()) {
		//This item is paid for out of the CREW FUND
		$rows_crew_fund++;
		if((strripos($row['item_name'],'screen-printed') === false) && (strripos($row['item_name'],'screen printed') === false)) {
			$artwork = "Embroidered";
		}
		else {
			$artwork = "Screen-print";
		}
		
		if($row['item_name'] != $last_item_name_crew_fund) {
			$row_style_crew_fund = toggle($row_style_crew_fund);
		}
		
		if(strpos(strtolower($row['item_name']), "orange") !== false) $color = "Orange";
		elseif(strpos(strtolower($row['item_name']), "black") !== false) $color = "Black";
		else $color = "";

		$output_crew_fund .= "<tr><td class=\"".$row_style_crew_fund."\">".$row['item_description']."</td>\n"
			."<td class=\"".$row_style_crew_fund."\">".$artwork."</td>\n"
			."<td class=\"".$row_style_crew_fund."\">".$color."</td>\n"
			."<td class=\"".$row_style_crew_fund."\">".$row['item_type']."</td>\n"
			."<td class=\"".$row_style_crew_fund."\">".$row['item_size']."</td>\n"
			."<td class=\"".$row_style_crew_fund."\">".$row['qty']."</td></tr>\n";
		$last_item_name_crew_fund = $row['item_name'];
	}
	
	while($row = $result_purchase_card->fetch_assoc()) {
		//This item is paid for with a PURCHASE CARD
		$rows_purchase_card++;
		if((strripos($row['item_name'],'screen-printed') === false) && (strripos($row['item_name'],'screen printed') === false)) {
			$artwork = "Embroidered";
		}
		else {
			$artwork = "Screen-print";
		}
		
		if($row['item_name'] != $last_item_name_purchase_card) {
			$row_style_purchase_card = toggle($row_style_purchase_card);
		}
		
		if(strpos(strtolower($row['item_name']), "orange") !== false) $color = "Orange";
		elseif(strpos(strtolower($row['item_name']), "black") !== false) $color = "Black";
		else $color = "";
		
		$output_purchase_card .= "<tr><td class=\"".$row_style_purchase_card."\">".$row['item_description']."</td>\n"
			."<td class=\"".$row_style_purchase_card."\">".$artwork."</td>\n"
			."<td class=\"".$row_style_purchase_card."\">".$color."</td>\n"
			."<td class=\"".$row_style_purchase_card."\">".$row['item_type']."</td>\n"
			."<td class=\"".$row_style_purchase_card."\">".$row['item_size']."</td>\n"
			."<td class=\"".$row_style_purchase_card."\">".$row['qty']."</td></tr>\n";
		$last_item_name_purchase_card = $row['item_name'];
	}
		
	$output_crew_fund .= "</table>\n";
	$output_purchase_card .="</table>\n";
	
	print 	$output_crew_fund
			."<br />\n"
			.$output_purchase_card
			."<br />\n";
	break;
			
			
		case 'by_people':
		default:
			
	//*************************** VIEW: by_people *****************************
	//*************************************************************************
	$query = "SELECT id, order_number, created_at, customer_name, item_name, item_type, item_size, item_price, item_qty, qty_for_free "
			."FROM apparel_orders "
			."WHERE order_number in(".implode(",",$order_numbers).") "
			."ORDER BY customer_name, item_name, item_type, item_size";

	$result = mydb::cxn()->query($query);
	
	while($row = $result->fetch_assoc()) {
		$row_count++;
		$item_subtotal = 0;
		if($row['customer_name'] != $last_customer) {
			if($row_count > 1) {
				// This is NOT the beginning of the first customer - print summary from previous customer
				while(sizeof($free_item_prices) < 2) $free_item_prices[] = 0;
				$order_grand_total = $order_subtotal - $free_item_prices[0] - $free_item_prices[1];
	
				print  	 "<tr><td colspan=\"5\" style=\"text-align:right;\">Subtotal:</td>"
							."<td class=\"summary\" style=\"text-align:right;\">$".number_format($order_subtotal,2)."</td><td></td></tr>\n"
						."<tr><td colspan=\"5\" style=\"text-align:right;\">Free Item #1:</td>"
							."<td class=\"summary\" style=\"text-align:right;\">-$".$free_item_prices[0]."</td><td></td></tr>\n"
						."<tr><td colspan=\"5\" style=\"text-align:right;\">Free Item #2:</td>"
							."<td class=\"summary\" style=\"text-align:right;\">-$".$free_item_prices[1]."</td><td></td></tr>\n"
						."<tr><td colspan=\"5\" style=\"text-align:right;\">Grand Total:</td>"
							."<td class=\"summary\" style=\"text-align:right;\">$".number_format($order_grand_total,2)."</td><td></td></tr>\n"
						."</table><br />\n"
						."<hr />\n";
			}
			$rows_this_customer = 0;
			$order_subtotal = 0;
			$order_grand_total = 0;
			$free_item_prices = array();
			
			print "<table><tr>\n"
				."	<td class=\"odd\" style=\"text-align:right;\">Order Date:</td>"
				."	<td class=\"odd\" style=\"text-align:left;\">".date("m/d/Y", strtotime($row['created_at']))." ".date("g:ia", strtotime($row['created_at']))."</td></tr>\n"
				."<tr>\n"
				."	<td class=\"odd\" style=\"text-align:right;\">Order #:</td>"
				."	<td class=\"odd\" style=\"text-align:left;\">".$row['order_number']."</td></tr>\n"
				."<tr>\n"
				."	<td class=\"odd\" style=\"text-align:right;\">Purchased by:</td>"
				."	<td class=\"odd\" style=\"text-align:left;\">".$row['customer_name']."</td></tr>\n"
				."</table><br />\n"
				."<table id=\"order\"><tr>"
				."	<th style=\"text-align:left;width:20em;\">Item</th>"
				."	<th style=\"width:7em;text-align:left;\">Type</th>"
				."	<th style=\"width:8em;\">Size</th>"
				."	<th style=\"\">Quantity</th>"
				."	<th style=\"\">Price</th>"
				."	<th style=\"\">Subtotal</th>"
				."	<th style=\"\">Free?</th></tr>\n";
		} // End: if($row['customer_name'] != $last_customer)
		$rows_this_customer++;
		if($rows_this_customer % 2 == 0) $row_style = "even";
		else $row_style = "odd";
		
		$free_or_not = "";
		for($i=0;$i<(int)$row['qty_for_free'];$i++) {
			$free_or_not .= "X";
			$free_item_prices[] = number_format($row['item_price'],2);
		}
		
		$item_subtotal = $row['item_qty'] * $row['item_price'];
		$order_subtotal += $item_subtotal;
		
		print "<tr>"
				."<td class=\"".$row_style." style=\"text-align:left;\">".$row['item_name']."</td>"
				."<td class=\"".$row_style." style=\"text-align:left;\">".$row['item_type']."</td>"
				."<td class=\"".$row_style."\">".$row['item_size']."</td>"
				."<td class=\"".$row_style."\">".$row['item_qty']."</td>"
				."<td class=\"".$row_style."\">$".number_format($row['item_price'],2)."</td>"
				."<td class=\"".$row_style." style=\"text-align:right;\">$".number_format($item_subtotal,2)."</td>"
				."<td class=\"".$row_style."\">".$free_or_not."</td></tr>\n";

		$last_customer = $row['customer_name'];
	}
	
	while(sizeof($free_item_prices) < 2) $free_item_prices[] = 0;
	$order_grand_total = $order_subtotal - $free_item_prices[0] - $free_item_prices[1];

	print  	 "<tr><td colspan=\"5\" style=\"text-align:right;\">Subtotal:</td>"
				."<td class=\"summary\" style=\"text-align:right;\">$".number_format($order_subtotal,2)."</td><td></td></tr>\n"
			."<tr><td colspan=\"5\" style=\"text-align:right;\">Free Item #1:</td>"
				."<td class=\"summary\" style=\"text-align:right;\">-$".$free_item_prices[0]."</td><td></td></tr>\n"
			."<tr><td colspan=\"5\" style=\"text-align:right;\">Free Item #2:</td>"
				."<td class=\"summary\" style=\"text-align:right;\">-$".$free_item_prices[1]."</td><td></td></tr>\n"
			."<tr><td colspan=\"5\" style=\"text-align:right;\">Grand Total:</td>"
				."<td class=\"summary\" style=\"text-align:right;\">$".number_format($order_grand_total,2)."</td><td></td></tr>\n"
			."</table><br />\n";
	
	
	break; // End: case 'by_people'
	} // End: switch($_GET['view']) {
	
	print "<div style=\"width:100%;background-color:#dddddd;padding:3px;\">\n"
		. "<a href=\"index.php\">Admin Home</a> | \n"
		. "<a href=\"".$_SERVER['PHP_SELF']."?view=by_items\">View by Items</a> | \n"
		. "<a href=\"".$_SERVER['PHP_SELF']."?view=by_people\">View by People</a> | \n"
		. "<a href=\"".$_SERVER['PHP_SELF']."?view=by_design\">View by Design</a>\n"
		. "</div>\n";
		
	print "</body></html>\n";
	
	function toggle($style) {
		if($style == "even") return "odd";
		else return "even";
	}
?>