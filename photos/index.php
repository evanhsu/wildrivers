<?php

	session_start();
	require_once("../classes/mydb_class.php");


	if(isset($_GET['year'])) $photoyear = $_GET['year'];
	else $photoyear = date("Y");	//Display photos from the current year if no year is specified

	if($photoyear == 0) {
		$result = mydb::cxn()->query("select path, thumbpath, caption, year, id from photos where year NOT BETWEEN 2006 and ".date("Y")." order by id");
	}
	else {
		$result = mydb::cxn()->query("select path, thumbpath, caption, year, id from photos where year like '". $photoyear ."' order by id");
	}

	if(mydb::cxn()->error != '') {
		throw new Exception('There was a problem retrieving photos from the database.<br />\n'.mydb::cxn()->error);
	}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>Photos :: Siskiyou Rappellers</title>

<?php include("../includes/basehref.html"); ?>

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
<meta name="Description" content="Photo Album - see what we've been up to." />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />

</head>


<body>
<div id="wrapper">
	<div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none" alt="Scroll down..." /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Siskiyou Rappellers - Photos</div>
    </div>

	<?php include("../includes/menu.php"); ?>

    <div id="content" style="text-align:center">
	    <br />
        <table style="border:none; margin:0 auto 0 auto; width:800px;">
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

				while($row = $result->fetch_assoc()) {
					if($col_count % 6 == 0) echo "<tr>\n";
					echo "		<td class=\"thumb\"><a href=\"./enlarge.php?image=".urlencode($row['path'])."&caption=".urlencode(stripslashes($row['caption']))."\"><img src=\"".$row['thumbpath']."\"></a><br>"
								.stripslashes($row['caption'])."<br></td>\n";

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
<?php include("../includes/google_analytics.html"); ?>

</body>
</html>