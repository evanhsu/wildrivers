<?php
	session_start();
	require_once("../includes/auth_functions.php");

	if(($_SESSION['logged_in'] == 1) && check_access("update_jobs")) {
		require_once("../classes/mydb_class.php");
	}
	else {
		if($_SESSION['logged_in'] != 1) $_SESSION['intended_location'] = $_SERVER['PHP_SELF'];
		header('location: http://tools.siskiyourappellers.com/admin/index.php');
	}

	//-------------------------------------------------------------------------------------------	
	if(isset($_POST['content_text'])) { 
		//$current_text = nl2br(htmlentities($_POST['update_text'], ENT_QUOTES));
		//$current_sticky=nl2br(htmlentities($_POST['sticky_text'], ENT_QUOTES));
		
		$query = "INSERT INTO job_vacancies (name, date, text) VALUES('".$_POST['name']."', NOW(), '".$_POST['content_text']."')";
		mydb::cxn()->query($query);
		
		//exit();
	}
	//---------------------------------------------------------------------------------------------------
	$query = "SELECT name, date, text FROM job_vacancies ORDER BY date DESC LIMIT 1";
	if($result = mydb::cxn()->query($query)) {
		$row = $result->fetch_assoc();
	}
	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Update Jobs::Siskiyou Rappel Crew</title>

<?php include("../includes/basehref.html"); ?>

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
<meta name="Description" content="Modify the current job vacancy listings."


<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<script type="text/javascript" src="scripts/jhtmlarea/scripts/jquery-1.3.2.js"></script>

<script type="text/javascript" src="scripts/jhtmlarea/scripts/jquery-ui-1.7.2.custom.min.js"></script>
<link rel="Stylesheet" type="text/css" href="scripts/jhtmlarea/style/jqueryui/ui-lightness/jquery-ui-1.7.2.custom.css" />


<script type="text/javascript" src="scripts/jhtmlarea/scripts/jHtmlArea-0.8.js"></script>
<link rel="Stylesheet" type="text/css" href="scripts/jhtmlarea/style/jHtmlArea.css" />
    
<link rel="stylesheet" type="text/css" href="styles/menu.css" />
<style>
html {
margin:0; padding:0;
width:100%; height:100%;
background-color:#b05122;
text-align:center;
}

body {
margin:0; padding:0;
height:auto;
}

#wrapper {
width:900px;
margin:10px auto 0px auto;
padding:0;
border:2px solid #555;
background-color:#fff;
font-family: Verdana, Arial, Helvetica, sans-serif;
font-size:11px;
text-align:left;
min-height:600px;
display:block;
}

#banner {
margin:0; padding:0;
height:202px;
/*border-top:2px solid #555;*/
text-align:left;
}

#banner img {
display:block;
}

#banner_text_bg {
width:890px; height:26px;
margin:0; padding:2px 5px;
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:18px;
color:#fff;
}
#bottom_links {
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size: 11px;
letter-spacing:2px;
color:#e94;
width:900px;
background:transparent;
margin:0 auto 0 auto; padding:0 0 25px 0;
display:block;
}
#editorForm {
margin: 0 auto 10px auto;
width: 100%;
text-align: center;
}

.jHtmlArea {
margin: 0 auto 0 auto;
}
</style>

<script type="text/javascript">

$(function() {
            $("#textEditor").htmlarea({
                // Specify the Toolbar buttons to show
                toolbar: [
                    ["bold", "italic", "underline"],
                    ["h1", "h2", "h3", "h4", "h5", "h6"],
                    ["link", "unlink"],                    
                    ["unorderedList", "horizontalrule"]
		]});
                // alert('SAVE!\n\n' + this.toHtmlString());
/*
		$('#saveBtn').click(function() {
			
			document.getElementById("editorForm").submit();
		});
*/
});
</script>

<?php
	if($_SESSION['mobile'] == 1) {
		echo "<style type=\"text/css\">\n"
			."	body {text-align:center; width:100%;}\n"
 			."	#wrapper {width:100%; min-height:100px; height:auto; margin: 5px auto 0 auto; text-align:center;}\n"
 			."	#content {width:100%; font-family:Verdana; font-size:0.8em; margin:0 auto 0 auto; text-align:left;}\n"
 			."	form .textentry {font-family:Verdana; font-size:8px; width:100%; margin:0 auto 0 auto;}\n"
 			."	td {font-family:Verdana; font-size:0.8em;}\n"
 			."	#bottom_links {font-size:0.5em; width:100%;}\n"
			."	textarea {font-family:Verdana, Arial; font-size:0.8em;}\n"
			."</style>";
	}
	else {
		echo "<style type=\"text/css\">\n"
			."	textarea {font-family:Verdana, Arial; font-size:11px;}\n"
			."</style>\n";
	}
?>

</head>

<body>
<div id="wrapper">
	<?php if($_SESSION['mobile'] != 1) {
		echo "<div id=\"banner\">
        	<a href=\"index.php\"><img src=\"images/banner_index2.jpg\" style=\"border:none\" /></a>
        	<div id=\"banner_text_bg\" style=\"background: url(images/banner_text_bg2.jpg) no-repeat;\">Siskiyou Rappel Crew - Job Vacancy Update Form</div>
    	</div>";

		include("../includes/menu.php");
		}
	?>

    <div id="content" style="text-align:center;">
    
        <?php 	if($_SESSION['mobile'] == 1) echo "<form id=\"editorForm\" action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\">"
												."<span style=\"font-size:0.5em\">Last update by <b>" .$row['name']. "</b> on " . $row['date'] . "</span><br>\n";
				else echo "<br>| <a href=\"admin/index.php\" style=\"font-weight:bold\">Admin Home</a> |"
					."<form id=\"editorForm\" action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\">"
					."Last update by <b>" . $row['name'] . "</b> on " . $row['date']. "<br>\n";
		?>
			<br />

            <?php
            	if($_SESSION['mobile'] == 1) {
			echo "<span>Current Jobs:</span><br />";
            		echo "<textarea id=\"textEditor\" name=\"content_text\" cols=\"25\" rows=\"5\">" . $row['text']. "</textarea><br />\n";
		}
		else {
			echo "<span>Current Jobs:</span><br />";
			echo "<textarea id=\"textEditor\" name=\"content_text\" cols=\"100\" rows=\"50\">" . $row['text']. "</textarea><br /><br />\n";
		}
				
		echo "<input type=\"hidden\" name=\"name\" value=\"".$_SESSION['username']."\">\n";
	?>
<input type="submit" id="saveBtn" value="Save" />
        </form>

    

    </div> <!-- End 'content' -->
</div><!-- end 'wrapper'-->

<?php 
	if($_SESSION['mobile'] != 1) include("../includes/footer.html");
	else include("../includes/footer_mobile.html");
?>

</body>
</html>

