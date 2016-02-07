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
	
	// Make sure there is no active session
//	session_destroy();
	session_name('raprec');
	session_start();
	
	require("includes/constants.php");	// Force 'constants.php' to load, even if it has been previously included by one of the classes above.  Must set SESSION vars AFTER the session_start() declaration.
	require_once("includes/auth_functions.php");
/*
	if(isset($_POST['mobile'])) $_SESSION['mobile'] = $_POST['mobile'];
	elseif(isset($_GET['mobile'])) $_SESSION['mobile'] = $_GET['mobile'];
	
	if(isset($_POST['username']) && isset($_POST['passwd'])) $login_result = login($_POST['username'], $_POST['passwd']);
	elseif(!isset($_SESSION['logged_in']) && !isset($login_result)) $login_result = array(-1,"No login attempt made yet");
*/	
	include("includes/make_menu.php");
	$_SESSION['location_bar'] = "Location: <a href=\"index.php\">Home</a> / Email Confirmation\n";
	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Confirm Request</title>

<link rel="Shortcut Icon" href="favicon.ico">
<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, rappel, rappelling, rappeller, rapel, rapell, rapeller, repeller, repelling, records, history" />
<meta name="Description" content="The National Rappel Record Website. This site is used to record & view all of the helicopter rappels that are performed by the US Forest Service." />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />
<?php if(isset($_GET['mobile']) && $_GET['mobile'] == 1) echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/mobile.css\" />\n"; ?>

</head>

<body>
    <div id="banner_left"><a href="index.php"><img src="images/raprec_banner_left.jpg" style="border:none" alt="RapRec Central Logo" /></a></div>
    <div id="banner_right"><a href="index.php"><img src="images/raprec_banner_right.jpg" style="border:none" alt="RapRec Central" /></a></div>
	
    <div id="left_sidebar">
		<?php make_menu(); ?>
    </div>
	
    <div id="location_bar"><?php echo $_SESSION['location_bar']; ?></div>
    
    <div id="content" style="font-size:16px; font-weight:bold;">
    	<br />
        <?php
		if(!isset($_GET['verification'])) echo "<div class=\"error_msg\" style=\"margin:0 auto 0 auto;\">You will only need to visit this page if you receive an email link that directs you here.</div>\n";
		else {
			$query = "SELECT query, unix_timestamp(creation_date) as creation_date, (days_until_expiration * 24 * 3600) as exp_interval FROM confirmation WHERE code = '".mydb::cxn()->real_escape_string($_GET['verification'])."'";
			$result = mydb::cxn()->query($query);
			$row = $result->fetch_assoc();
			
			if($row['query'] == "") echo "That confirmation code is invalid<br>\n";
			elseif(($row['creation_date'] + $row['exp_interval']) < time()) {
				echo "<div class=\"error_msg\" style=\"margin:0 auto 0 auto;\">That confirmation code has expired!</div>\n";
				$query = "DELETE from confirmation WHERE code = '".mydb::cxn()->real_escape_string($_GET['verification'])."'";
				$result = mydb::cxn()->query($query);
			}
			else {
				if(!mydb::cxn()->multi_query($row['query'])) echo "<div class=\"error_msg\" style=\"margin:0 auto 0 auto;\">There was a problem confirming your request.</div>\n";
				else {
					while(mydb::cxn()->next_result()) mydb::cxn()->store_result(); //Clear the buffer from the dB multi_query
					echo "<div class=\"error_msg\" style=\"margin:0 auto 0 auto;\">Your request has been confirmed.</div>";
					$query = "DELETE from confirmation WHERE code = '".mydb::cxn()->real_escape_string($_GET['verification'])."'";
					$result = mydb::cxn()->query($query);
					echo mydb::cxn()->error;
				}
			}
		}
		?>
    </div> <!-- End 'content' -->
   	
<div style="clear:both; display:block; visibility:hidden;"></div>
</body>
</html>
