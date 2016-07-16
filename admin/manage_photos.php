<?php
	session_start();
	require("../includes/auth_functions.php");
	
	if(($_SESSION['logged_in'] == 1) && check_access("photos")) {
		// Allow access
	}
	else {
		if($_SESSION['logged_in'] != 1) $_SESSION['intended_location'] = $_SERVER['PHP_SELF'];
		header('location: http://www.siskiyourappellers.com/admin/index.php');
	}
	//------
	
	$error = 0; //Initialize error flag

	if(isset($_GET['delete'])) {
		$result = mydb::cxn()->query("select id,path,thumbpath from photos where id = ".$_GET['delete']); //Referenced with unique file identifier, returns 1 row
		$row = $result->fetch_assoc();
		
		$path = "../" . $row['path'];
		$thumbpath = "../" . $row['thumbpath'];

		//Delete the photo
		if(!@unlink($path)) {
			$error = 1;
			$error_msg = "Unable to delete ".$row['path'];
		}

		//Delete the thumbnail
		if(!@unlink($thumbpath)) {
			$error = 1;
			$error_msg = "Unable to delete ".$row['thumbpath'];
		}
		
		//Remove from dB
		$result = mydb::cxn()->query("delete from photos where id = " . $_GET['delete']);
			
	}//end if
	elseif (isset($_POST['caption'])) {
		mydb::cxn()->query("SET AUTOCOMMIT=0;");
		//mydb::cxn()->query("BEGIN"); // Begin a compound database query (2 queries)
		$query = "UPDATE photos SET caption = \"".mydb::cxn()->real_escape_string($_POST['caption'])."\" WHERE id = ".$_POST['id'];
		$result = mydb::cxn()->query($query);
		if(mydb::cxn()->error != '') {
			die("dB caption update failed: " . mydb::cxn()->error . "<br>\n".$query);
		}
		
		$query = "UPDATE photos SET year = \"".$_POST['year']."\" WHERE id = ".$_POST['id'];
		$result = mydb::cxn()->query($query);
		if(mydb::cxn()->error != '') {
			die("dB year update failed: " . mydb::cxn()->error . "<br>\n".$query));
		}
		//mydb::cxn()->query("END");
		mydb::cxn()->query("COMMIT");
		mydb::cxn()->query("SET AUTOCOMMIT=1;");
	}

	// Fetch all current photos from database
	if(isset($_GET['year'])) $photoyear = $_GET['year'];
	else $photoyear = date("Y");	//Display photos from the current year if no year is specified
	
	if($photoyear == 0) {

		$query = "select path, thumbpath, caption, year, id from photos where year NOT BETWEEN 2006 and ".date("Y")." order by id";
		$result = mydb::cxn()->query($query);
		if(mydb::cxn()->error != '') {
			die("dB query failed: " . mydb::cxn()->error . "<br>\n".$query);
		}

	}
	else {
		$query = "select path, thumbpath, caption, year, id from photos where year like '". $photoyear ."' order by id";
		$result = mydb::cxn()->query($query);
		if(mydb::cxn()->error != '') {
			die("dB select failed: " . mydb::cxn()->error . "<br>\n".$query);
		}
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Manage Photos :: Siskiyou Rappel Crew</title>

<?php include("../includes/basehref.html"); ?>

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
<meta name="Description" content="Manage Photos - Change Captions and Delete Unwanted Photos" />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />

<style type="text/css">
	table {width:800px;}
	td {
		width:125px;
		vertical-align:top;
		overflow:hidden;
	}
</style>

</head>

<body>
<div id="wrapper">
	<div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none" alt="Scroll down..." /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Siskiyou Rappel Crew - Photo Management</div>
    </div>

	<?php include("../includes/menu.php"); ?>

    <div id="content" style="text-align:center">
	    <br />
        <?php
				//Display 'year' menu
				echo "<div style=\"width:500px\">\n";
				for($i=date("Y"); $i >= 2006; $i--) {
					echo "<a href=\"".$_SERVER['PHP_SELF']."?year=".$i."\">";
					if($i == $photoyear) echo "<b>";
					echo $i;
					if($i == $photoyear) echo "</b>";
					echo "</a>\n ";
				} // end 'for'
				echo "<a href=\"".$_SERVER['PHP_SELF']."?year=0\">";
				if($photoyear == 0) echo "<b>";
				echo "Other";
				if($photoyear == 0) echo "</b>";
				echo "</a>\n ";
				
				echo "</div> <!-- End 'year' menu -->\n<br>"
					."<hr style=\"width:100%\">\n\n";
				
				$col_count = 0; //Count number of thumbnails in each row
				$total_count=0; //Count total number of thumbs on the page
		?>		
		<table style="border:none; margin:0 auto 0 auto; width:800px;">
        <?php	
				while($row = $result->fetch_assoc()) {
					if($col_count % 6 == 0) echo "<tr>\n";
					$caption = htmlentities($row['caption']);
					echo "		<td class=\"thumb\">"
								."<form action=\"".$_SERVER['PHP_SELF']."?year=".$photoyear."\" method=\"POST\">"
								."<input type=\"text\" name=\"year\" value=\"".$row['year']."\" style=\"width:40px;font-size:9px\"><br>"
								."<a href=\"../enlarge.php?image=".$row['path']."&caption=".$caption."\"><img src=\"".$row['thumbpath']."\"></a><br>"
								."<input type=\"hidden\" name=\"id\" value=\"".$row['id']."\" >\n"
								."<input type=\"text\" name=\"caption\" value=\"".$caption."\" style=\"width:100px;font-size:9px\"><br>"
								."<input type=\"submit\" value=\"Update\" style=\"font-size:9px;width:50px:background-color:#396;border:2px solid #666;\"><br>"
								."<a href=\"".$_SERVER['PHP_SELF']."?delete=".$row['id']."\">Delete Photo</a>"
								."</form></td>\n";
								
					$col_count = $col_count + 1;;
					$total_count = $total_count + 1;
					
					if($col_count % 6 == 0) {
						echo "</tr>\n";
						$col_count = 0;
					}
				}//end 'while'
				
				if($col_count != 0) echo "</tr>\n";
		?>

        </table>

    </div> <!-- End 'content' -->
</div><!-- end 'wrapper'-->

<?php include("../includes/footer.html"); ?>

</body>
</html>

