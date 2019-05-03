<?php
	session_start();
	require_once("../includes/auth_functions.php");
	
	if(($_SESSION['logged_in'] == 1) && check_access("order_apparel")) {
		require_once("../classes/mydb_class.php");
	}
	else {
		if($_SESSION['logged_in'] != 1) $_SESSION['intended_location'] = $_SERVER['PHP_SELF'];
		header('location: http://tools.siskiyourappellers.com/admin/index.php');
	}
	//-----------------------------------------------------------------------------
	$mail_recipient = "apparel@siskiyourappellers.com";
	//$mail_recipient = "evanhsu@gmail.com";
	
	$exclude_from_free_column_1 = array("Flex Fit Crew Hat", "Mesh-Back Trucker Hat", "Black Screen-Printed Crew Hoody", "Black Embroidered Crew Hoody", "Black Embroidered Beanie (short)", "Black Embroidered Beanie (cuffed)");
	$exclude_from_free_column_2 = array("Flex Fit Crew Hat", "Mesh-Back Trucker Hat", "Black Embroidered Beanie (short)", "Black Embroidered Beanie (cuffed)");
	
	$ids = array(); //[product_id, quantity]
	$qtys= array();
	if(isset($_POST['grand_total1'])) {
		$order_number = $_SESSION['username'].date("Y-M-d-His");
		foreach($_POST as $key=>$val) {
			if((substr($key,0,3) == "qty") && !is_null($val) && ($val != "")) {
				$ids[] = substr($key,4);
				$qtys[substr($key,4)] = $val;
			}
		}
		$query = "SELECT id, name, type, size, price, description FROM apparel WHERE id IN(".implode(",",$ids).") ORDER BY id";
		$result = mydb::cxn()->query($query);

		$td_style = "font-size: 1.2em;border: 1px solid #98BF21;padding: 3px 7px 2px 7px;background-color: #A7C942;color: white;";
		$td_alt_style = "font-size:1.2em; border:1px solid #98BF21; padding:3px 7px 2px 7px; background-color:#EAF2D3; color:black;";
		$th_style = "font-size: 1.2em;border: 1px solid #98BF21;padding: 3px 7px 2px 7px;background-color: #666666;color: white;";
		$table_style = "border:1px solid #000000; border-collapse:collapse;";

		$insert_query = "INSERT INTO apparel_orders (order_number, customer_name, item_name, item_description, item_type, item_size, item_price, item_qty, qty_for_free) VALUES ";
		$msg = "<html>\n"
				."<body>\n"
				."<h3>SRC Crew Apparel Order</h3>\n"
				."<table style=\"".$table_style."\"><tr>\n"
				."	<td style=\"".$td_alt_style."text-align:right;\">Order Date:</td>"
				."	<td style=\"".$td_alt_style."text-align:left;\">".date("m/d/Y")." ".date("g:ia")."</td></tr>\n"
				."<tr>\n"
				."	<td style=\"".$td_alt_style."text-align:right;\">Order #:</td>"
				."	<td style=\"".$td_alt_style."text-align:left;\">".$order_number."</td></tr>\n"
				."<tr>\n"
				."	<td style=\"".$td_alt_style."text-align:right;\">Purchased by:</td>"
				."	<td style=\"".$td_alt_style."text-align:left;\">".$_POST['purchased_by']."</td></tr>\n"
				."<tr>\n"
				."	<td style=\"".$td_alt_style."text-align:right;\">Grand Total:</td>"
				."	<td style=\"".$td_alt_style."text-align:left;\">$".$_POST['grand_total1']."</td></tr>\n"
				."</table><br />\n"
				."<table id=\"order\"><tr>"
				."	<th style=\"text-align:left;".$th_style."width:20em;\">Item</th>"
				."	<th style=\"".$th_style."width:7em;text-align:left;\">Type</th>"
				."	<th style=\"".$th_style."width:8em;\">Size</th>"
				."	<th style=\"".$th_style."\">Quantity</th>"
				."	<th style=\"".$th_style."\">Price</th>"
				."	<th style=\"".$th_style."\">Subtotal</th>"
				."	<th style=\"".$th_style."\">Free?</th></tr>\n";
		$row_count = 0;
		while($row = $result->fetch_assoc()) {
			$free_or_not = "";
			if($row_count++ % 2 == 0) $row_style = $td_style;
			else $row_style = $td_alt_style;
			if(isset($_POST['free_item_1']) && ($_POST['free_item_1'] == $row['id'])) $free_or_not .= "X";
			if(isset($_POST['free_item_2']) && ($_POST['free_item_2'] == $row['id'])) $free_or_not .= "X";
			$msg .= "<tr>"
					."<td style=\"".$row_style."text-align:left;\">".$row['name']."</td>"
					."<td style=\"".$row_style."text-align:left;\">".$row['type']."</td>"
					."<td style=\"".$row_style."\">".$row['size']."</td>"
					."<td style=\"".$row_style."\">".$qtys[$row['id']]."</td>"
					."<td style=\"".$row_style."\">$".number_format($row['price'],2)."</td>"
					."<td style=\"".$row_style."text-align:right;\">$".number_format($qtys[$row['id']] * $row['price'],2)."</td>"
					."<td style=\"".$row_style."\">".$free_or_not."</td></tr>\n";
			
			if($row_count > 1) $insert_query .= ", ";
			$insert_query .= "('"	.$order_number."','"
									.$_SESSION['user_real_name']."','"
									.$row['name']."','"
									.$row['description']."','"
									.$row['type']."','"
									.$row['size']."',"
									.number_format($row['price'],2).","
									.$qtys[$row['id']].","
									.strlen($free_or_not).")";
		}
		$insert_query .= ";";
		$msg .=  "<tr><td colspan=\"5\" style=\"text-align:right;\">Subtotal:</td><td style=\"text-align:right;\">$".$_POST['final_subtotal1']."</td></tr>\n"
				."<tr><td colspan=\"5\" style=\"text-align:right;\">Free Item #1:</td><td style=\"text-align:right;\">$".$_POST['free_item_1_1']."</td></tr>\n"
				."<tr><td colspan=\"5\" style=\"text-align:right;\">Free Item #2:</td><td style=\"text-align:right;\">$".$_POST['free_item_2_1']."</td></tr>\n"
				."<tr><td colspan=\"5\" style=\"text-align:right;\">Grand Total:</td><td style=\"text-align:right;\">$".$_POST['grand_total1']."</td></tr>\n";
		$msg .= "</table>\n</body>\n</html>\n";
		
		$result = mydb::cxn()->query($insert_query);
		if($r = mail_order($mail_recipient,$msg,$order_number) && (mydb::cxn()->error == "")) {
			$alert = "<div style=\"color:#33dd33; font-weight:bold; font-size:2em;\">Your order has been submitted!</div>";
		}
		else $alert = "<div style=\"color:#dd3333; font-weight:bold;\">There was a problem sending your order.</div>";
	}

	function mail_order($to,$msg,$order_number) {
		$subject = "SRC Apparel Order - ".$_POST['purchased_by']." - Order #".$order_number;

		$headers = "From: SRC-Apparel@siskiyourappellers.com\r\n";
		$headers .= "Reply-To: donotreply@siskiyourappellers.com\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		$result = mail($to, $subject, $msg, $headers);
		return $result;
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Apparel :: Siskiyou Rappel Crew</title>

<?php include("../includes/basehref.html"); ?>

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="apparel, tshirt, t-shirt, t-shirts, shirts, clothing, clothes, hats, hat, crew shirts, logo, fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
<meta name="Description" content="Order crew apparel from the Siskiyou Rappel Crew" />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />

<script type="text/javascript" language="javascript">
function update_subtotals(id) {		//Update the subtotal on an individual line when the quantity is changed
	var form_name = "apparel_order_form";
	var price_field = document.forms[form_name].elements['price_'+id]
	var qty_field = document.forms[form_name].elements['qty_'+id]
	var subtotal_field = document.forms[form_name].elements['subtotal_'+id]

	var price = price_field.value;
	var qty = qty_field.value;

	subtotal_field.value = (price * qty).toFixed(2);
	update_grand_total();
}

function update_grand_total() {
	//Count the number of 'subtotal' fields on the page
	var form_name = "apparel_order_form";
	var grand_total_field1 = document.forms[form_name].elements['grand_total1']
	var grand_total_field2 = document.forms[form_name].elements['grand_total2']
	var final_subtotal_field1 = document.forms[form_name].elements['final_subtotal1']
	var final_subtotal_field2 = document.forms[form_name].elements['final_subtotal2']
	var free_item_1_deduction_field_1 = document.forms[form_name].elements['free_item_1_1']
	var free_item_1_deduction_field_2 = document.forms[form_name].elements['free_item_1_2']
	var free_item_2_deduction_field_1 = document.forms[form_name].elements['free_item_2_1']
	var free_item_2_deduction_field_2 = document.forms[form_name].elements['free_item_2_2']
	var free_item_1_id = getSelectedRadioValue('free_item_1');
	var free_item_2_id = getSelectedRadioValue('free_item_2');
	var list_of_fields = document.forms[form_name].elements
	var subtotal_fields = Array();
	var final_subtotal = 0;
	var item_id = '';
	var qty = '';
	var tmp;

	for(var i = 0; i < list_of_fields.length; i++)
	{
		if(list_of_fields[i].name.match(/subtotal_/i) != null) {
			item_id = list_of_fields[i].name.split('_')[1];
			qty = document.forms[form_name].elements['qty_'+item_id].value;
			subtotal_fields.push(list_of_fields[i]);

			if(free_item_1_id == item_id) {
				if(qty > 0) {
					free_item_1_deduction_field_1.value = (document.forms[form_name].elements['price_'+item_id].value * -1).toFixed(2);
					free_item_1_deduction_field_2.value = free_item_1_deduction_field_1.value;
					qty--;
				}
				else {
					free_item_1_deduction_field_1.value = (0).toFixed(2);
					free_item_1_deduction_field_2.value = (0).toFixed(2);
				}
			}
		  	if(free_item_2_id == item_id) {
				if(qty > 0) {
					free_item_2_deduction_field_1.value = (document.forms[form_name].elements['price_'+item_id].value * -1).toFixed(2);
					free_item_2_deduction_field_2.value = free_item_2_deduction_field_1.value;
				}
				else {
					free_item_2_deduction_field_1.value = (0).toFixed(2);
					free_item_2_deduction_field_2.value = (0).toFixed(2);
				}
		 	}
		}
	}

	for(var i=0; i<subtotal_fields.length; i++) {
		final_subtotal += parseFloat(subtotal_fields[i].value);
	}

	final_subtotal_field1.value = final_subtotal.toFixed(2);
	final_subtotal_field2.value = final_subtotal.toFixed(2);

	grand_total_field1.value = Math.max(final_subtotal + parseFloat(free_item_1_deduction_field_1.value) + parseFloat(free_item_2_deduction_field_1.value),0).toFixed(2);
	grand_total_field2.value = Math.max(final_subtotal + parseFloat(free_item_1_deduction_field_1.value) + parseFloat(free_item_2_deduction_field_1.value),0).toFixed(2);
}

function getSelectedRadioValue(buttonGroup) {
   // returns the value of the selected radio button or "" if no button is selected
   buttonGroup = document.forms['apparel_order_form'].elements[buttonGroup];
   var i = getSelectedRadio(buttonGroup);
   if (i == -1) {
      return "";
   } else {
      if (buttonGroup[i]) { // Make sure the button group is an array (not just one button)
         return buttonGroup[i].value;
      } else { // The button group is just the one button, and it is checked
         return buttonGroup.value;
      }
   }
} // Ends the "getSelectedRadioValue" function
function getSelectedRadio(buttonGroup) {

   // returns the array number of the selected radio button or -1 if no button is selected
   if (buttonGroup[0]) { // if the button group is an array (one button is not an array)
      for (var i=0; i<buttonGroup.length; i++) {
         if (buttonGroup[i].checked) {
            return i
         }
      }
   } else {
      if (buttonGroup.checked) { return 0; } // if the one button is checked, return zero
   }
   // if we get to this point, no radio button is selected
   return -1;
} // Ends the "getSelectedRadio" function
</script>

<style type="text/css">
table {
	border-collapse: collapse;
}

table th, td {
	margin:0;
	border:none;
}

input {
	border:none;

}

.apparel_qty_input_field {
	width: 2em;
	text-align: right;
	border: 1px solid #FC3;
}

</style>

</head>

<body>
<div id="wrapper">
	<div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none" alt="Scroll down..." /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Siskiyou Rappel Crew - Order Crew Apparel</div>
    </div>

	<?php include("../includes/menu.php"); ?>

    <div id="content" style="text-align:center">
	    <br />
        <div id="alert"><?php echo $alert; ?></div>
        <div style="width:700px; margin:0 auto 10px auto; text-align:justify;">
        	<div style="width:330px; margin:0 5px 0 auto; padding:5px; display:inline-block; background-color:#aaaaaa; border: 1px solid #555555;">
                <span style="font-size:1.5em;font-weight:bold;">You MUST have:</span><br />
                1 Orange PT Shirt and<br />
                1 Black Crew Shirt<br />
                <br />
                Additional apparel is recommended but not required, although you will be expected to wear a crew shirt daily following rookie training and maintain a clean and professional appearance.
            </div>
            <div style="width:330px; margin:0 auto 0 5px; padding:5px; display:inline-block; background-color:#aaaaaa; border: 1px solid #555555;">
                <span style="font-size:1.5em;font-weight:bold;">Two of your items will be free.</span><br />
                Use the buttons in the 'free' column to apply your free credit to an item.<br />
                Not all items are eligible for free credit.<br />
                <br />
                <span style="font-size:1.5em;font-weight:bold;">Bring payment</span> with you on your first day.<br />
                Cash, check and PayPal will be accepted.  Make checks payable to: <strong>Andrew Larrimore</strong>
                <br /><br />
                <span style="font-size:1.5em;font-weight:bold;">If you want to change your order later</span>, just submit a completely new order and it will replace your previous order.
            </div>
        </div>

    	<form name="apparel_order_form" id="apparel_order_form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <table style="margin:0 auto 0 auto; width:85%;">
			<tr style="background-color:#aaaaaa; height:1em;">
				<td style="width:150px;">&nbsp;</td>
                <td style="width:100px;">&nbsp;</td>
                <td style="width:100px;">&nbsp;</td>
                <td style="width:75px;">&nbsp;</td>
                <td style="width:50px;">&nbsp;</td>
                <td style="width:80px;">&nbsp;</td>
                <td style="width:45px;">&nbsp;</td>
            </tr>
            <tr style="background-color:#aaaaaa; height:1em;"><td colspan="7">&nbsp;</td></tr>

            <tr><td colspan="5" style="text-align:right;font-size:1.5em;font-weight:bold;">Subtotal:</td>
            	<td colspan="2" style="font-size:1.5em;font-weight:bold;text-align:right;">$<input type="text" id="final_subtotal1" name="final_subtotal1" style="width:4em;right;font-size:1.5em;font-weight:bold;text-align:right;" value="0.00" READONLY /></td></tr>

            <tr><td colspan="5" style="text-align:right;font-size:1.5em;font-weight:bold;">Free Item #1:</td>
            	<td colspan="2" style="font-size:1.5em;font-weight:bold;text-align:right;">$<input type="text" id="free_item_1_1" name="free_item_1_1" style="width:4em;right;font-size:1.5em;font-weight:bold;text-align:right;" value="0.00" READONLY /></td></tr>

            <tr><td colspan="5" style="text-align:right;font-size:1.5em;font-weight:bold;">Free Item #2:</td>
            	<td colspan="2" style="font-size:1.5em;font-weight:bold;text-align:right;">$<input type="text" id="free_item_2_1" name="free_item_2_1" style="width:4em;right;font-size:1.5em;font-weight:bold;text-align:right;" value="0.00" READONLY /></td></tr>

            <tr><td colspan="5" style="text-align:right;font-size:1.5em;font-weight:bold;">Grand Total:</td>
            	<td colspan="2" style="font-size:1.5em;font-weight:bold;text-align:right;">$<input type="text" id="grand_total1" name="grand_total1" style="width:4em;right;font-size:1.5em;font-weight:bold;text-align:right;" value="0.00" READONLY />
                <input type="hidden" name="purchased_by" value="<?php echo $_SESSION['user_real_name']; ?>" /></td></tr>
<?php
$query = "SELECT id, name, image_filename, image_thumb_filename, description, size, apparel.type, price FROM apparel WHERE 1 ORDER BY id";
$result = mydb::cxn()->query($query);
$last_product = '';
$item_count = 0;

$table_headers = "<tr style=\"background:#aaaaaa;\">
            	<th>Item</th>
            	<th>Size</th>
                <th style=\"text-align:left;\">Type</th>
                <th style=\"text-align:left;\">Price</th>
                <th>QTY</th>
                <th>Subtotal</th>
				<th>Free</th></tr>\n";

while($row = $result->fetch_assoc()) {
	$query = "select count(id) as num_rows from apparel where name like \"".$row['name']."\" group by name order by id";
	$result2 = mydb::cxn()->query($query);
	$row2 = $result2->fetch_assoc();
	$rows_per_image = $row2['num_rows'] + 1;

	$item_count++;
	if(($last_product != $row['name']) && ($item_count > 1)) {
		// Start a New Product Listing
		echo "<tr><td colspan=\"5\">&nbsp;</td></tr>"
			.$table_headers."
			  <tr>
				<td rowspan=\"".$rows_per_image."\"><span style=\"font-size:1.25em; font-weight:bold;\">".$row['name']."</span><br />"
					."<a href=\"".$row['image_filename']."\"><img src=\"".$row['image_thumb_filename']."\" /></a><br />"
					.$row['description']."</td>\n";
	}
	elseif(($last_product != $row['name']) && ($item_count == 1)) {
		echo $table_headers."
			  <tr>
				<td rowspan=\"".$rows_per_image."\"><span style=\"font-size:1.25em; font-weight:bold;\">".$row['name']."</span><br />"
					."<a href=\"".$row['image_filename']."\"><img src=\"".$row['image_thumb_filename']."\" /></a><br />"
					.$row['description']."</td>\n";
	}
	else echo "<tr>\n";

	echo "	<td>".$row['size']."</td>
			<td style=\"text-align:left;\">".$row['type']."</td>
			<td>$<input type=\"text\" id=\"price_".$row['id']."\" name=\"price_".$row['id']."\" value=\"".number_format($row['price'],2)."\" style=\"width:4em;\" READONLY /></td>
			<td><input type=\"text\" id=\"qty_".$row['id']."\" name=\"qty_".$row['id']."\" size=\"2\" class=\"apparel_qty_input_field\" onKeyUp=\"update_subtotals(".$row['id'].")\" onChange=\"update_subtotals(".$row['id'].")\" /></td>
            <td style=\"text-align:center;\">$<input type=\"text\" id=\"subtotal_".$row['id']."\" name=\"subtotal_".$row['id']."\" value=\"0.00\" style=\"width:5em;margin:0 auto 0 auto;\" READONLY /></td>
			<td>";
	//if(!in_array($row['name'],$exclude_from_free_column_1)) echo "<input type=\"radio\" name=\"free_item_1\" value=\"".$row['id']."\">";
	//if(!in_array($row['name'],$exclude_from_free_column_2)) echo "<input type=\"radio\" name=\"free_item_2\" value=\"".$row['id']."\">";

	echo "<input type=\"radio\" name=\"free_item_1\" value=\"".$row['id']."\" onChange=\"update_subtotals(".$row['id'].")\"";
	if(in_array($row['name'],$exclude_from_free_column_1)) echo " disabled=\"disabled\"";
	echo ">";

	echo "<input type=\"radio\" name=\"free_item_2\" value=\"".$row['id']."\" onChange=\"update_subtotals(".$row['id'].")\"";
	if(in_array($row['name'],$exclude_from_free_column_2)) echo " disabled=\"disabled\"";
	echo ">";

	echo "</td>
		</tr>\n";

	$last_product = $row['name'];
}// END while()

?>
			<tr><td colspan="6">&nbsp;</td></tr>
            <tr style="background-color:#aaaaaa; height:1em;"><td colspan="8">&nbsp;</td></tr>

            <tr><td colspan="5" style="text-align:right;font-size:1.5em;font-weight:bold;">Subtotal:</td>
            	<td colspan="2" style="font-size:1.5em;font-weight:bold;text-align:right;">$<input type="text" id="final_subtotal2" name="final_subtotal2" style="width:4em;right;font-size:1.5em;font-weight:bold;text-align:right;" value="0.00" READONLY /></td></tr>

            <tr><td colspan="5" style="text-align:right;font-size:1.5em;font-weight:bold;">Free Item #1:</td>
            	<td colspan="2" style="font-size:1.5em;font-weight:bold;text-align:right;">$<input type="text" id="free_item_1_2" name="free_item_1_2" style="width:4em;right;font-size:1.5em;font-weight:bold;text-align:right;" value="0.00" READONLY /></td></tr>

            <tr><td colspan="5" style="text-align:right;font-size:1.5em;font-weight:bold;">Free Item #2:</td>
            	<td colspan="2" style="font-size:1.5em;font-weight:bold;text-align:right;">$<input type="text" id="free_item_2_2" name="free_item_2_2" style="width:4em;right;font-size:1.5em;font-weight:bold;text-align:right;" value="0.00" READONLY /></td></tr>

            <tr><td colspan="5" style="text-align:right;font-size:1.5em;font-weight:bold;">Grand Total:</td>
            	<td colspan="2" style="font-size:1.5em;font-weight:bold;text-align:right;">$<input type="text" id="grand_total2" name="grand_total2" style="width:4em;right;font-size:1.5em;font-weight:bold;text-align:right;" value="0.00" READONLY /></td></tr>

            <tr><td colspan="7"><input type="submit" value="Submit Order" style="font-size:1.5em; font-weight:bold;border:2px solid #CC0;" /></td></tr>
          </table>
      	</form>
    </div> <!-- End 'content' -->
</div><!-- end 'wrapper'-->

<?php include("../includes/footer.html"); ?>

</body>
</html>

