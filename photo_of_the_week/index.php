<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Fire Photo of the Week</title>
<?php
	include("../includes/basehref.html");
	require_once("../classes/mydb_class.php");

?>

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="photo, pic, picture, of the week, fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, oregon, helitack, hecm, crew" />
<meta name="Description" content="Check out the Wildland Fire Photo of the Week and order a print.  Proceeds support the Wildland Firefighters' Foundation." />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />
<link rel="stylesheet" type="text/css" href="styles/potw.css" />

</head>


<body>
<div id="wrapper">
	<div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none" alt="" /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Photo of the Week</div>
    </div>
	<?php include("../includes/menu.php");?>

    <div id="content">
    	<div id="potw-explanation">
        	The photo of the week is a community effort, hosted by the Siskiyou Rappel Crew, to support and unite the greater community of wildland firefighters nationwide.
            All proceeds from the Photo of the Week go to the <a href="http://www.wffoundation.org/" target="_blank">Wildland Firefighter Foundation</a>.
        </div>
        <div id="potw-image">
            <?php
			$query = "SELECT id FROM photo_of_the_week ORDER BY last_date_used,id LIMIT 1";
			$result = mydb::cxn()->query($query);
			$row = $result->fetch_assoc();
			$photo['id'] = $row['id'];
			
			
			$query = "SELECT id, path, photographer, location, description FROM photo_of_the_week WHERE id = ".$photo['id'];
			$result = mydb::cxn()->query($query);
			while($row = $result->fetch_assoc()) {
				$photo['path'] = $row['path'];
				$photo['photographer'] = $row['photographer'];
				$photo['location'] = $row['location'];
				$photo['description'] = $row['description'];
			}
			?>
           <img src="<?php echo $photo['path'];?>" />
        </div>
        <div id="potw-credits">
        	<br />
        	<table>
            <tr><td colspan="2" style="font-weight:bold">This week's photo:</td></tr>
            <tr><td>Photographer:</td><td><?php echo $photo['photographer'];?></td></tr>
            <tr><td>Location:</td><td><?php echo $photo['location'];?></td></tr>
            <tr><td>Description:</td><td><?php echo $photo['description'];?></td></tr>
            <tr><td>Price:</td><td>$15.00</td></tr>
            <tr><td>Order Details:</td><td>When you order a Photo of the Week, you will receive an 8" x 10" print that is ready to be framed (matting and frame are not included).<br />
            Ordering is processed securely through Paypal.  Please be sure to provide your shipping address.
            </td></tr>
            </table>
            
        </div>
        <div id="potw-paypal-button">
            <!-- PAYPAL PAYMENT BUTTON -->
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="business" value="orders@siskiyourappellers.com">
            <input type="hidden" name="lc" value="US">
            <input type="hidden" name="item_name" value="Photo of the Week">
            <input type="hidden" name="item_number" value="123">
            <input type="hidden" name="amount" value="15.00">
            <input type="hidden" name="currency_code" value="USD">
            <input type="hidden" name="button_subtype" value="products">
            <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
            <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>

            <!-- END PAYPAL BUTTON -->
        </div>
        
        <br /><br />

    </div><!-- end 'content' -->
</div><!-- end 'wrapper'-->

<?php
include("../includes/footer.html");
//include ("../includes/google_analytics.html");
?>

</body>
</html>