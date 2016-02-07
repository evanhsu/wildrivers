<?php

	session_start();
	require_once("../classes/mydb_class.php");

	$result = mydb::cxn()->query("select id, path, thumbpath, photographer, location, description from photo_of_the_week ORDER BY id")
					or die("dB query failed: " . mydb::cxn()->error);
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>Photo of the Week Archive</title>

<?php include("../includes/basehref.html"); ?>

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
<meta name="Description" content="Photo of the Week Archive" />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />

</head>


<body>
<div id="wrapper">
	<div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none" alt="Scroll down..." /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Photo of the Week Archive</div>
    </div>

	<?php include("../includes/menu.html"); ?>

    <div id="content" style="text-align:center">
	    <br />
        <table style="border:none; margin:0 auto 0 auto; width:800px;">
        <?php

				$col_count = 0; //Count number of thumbnails in each row
				$total_count=0; //Count total number of thumbs on the page

				while($row = $result->fetch_assoc()) {
					if($col_count % 6 == 0) echo "<tr>\n";
					echo "		<td class=\"thumb\"><a href=\"enlarge.php?image=".urlencode($row['path'])."&caption=".urlencode(stripslashes("#".$row['id'].": ".$row['description']))."\"><img src=\"".$row['thumbpath']."\"></a><br>#"
								.stripslashes($row['id'])."<br></td>\n";

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