<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Flight Hours :: Siskiyou Rappel Crew</title>
<?php include("includes/basehref.html"); ?>
<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
<meta name="Description" content="Information about the Siskiyou Rappel Crew (Merlin, OR)" />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />

</head>


<body>
<div id="wrapper">
	<div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none" /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Siskiyou Rappel Crew - Flight Hours</div>
    </div>
	<?php include("includes/menu.php"); ?>

    <div id="content" style="text-align:center">
		<div class="highlight1">Flight Hours for our Exclusive-Use Helicopter</div>
        <br />

        <?php
			//include charts.php to access the InsertChart function
			include "includes/charts/charts.php";
			echo InsertChart ( "includes/charts/charts.swf", "includes/charts/charts_library", "flighthour_data.php", 650, 350, "949e7c" );
		?>

    </div><!-- end 'content' -->
</div><!-- end 'wrapper'-->
<?php include("includes/footer.html"); ?>

<?php include("includes/google_analytics.html"); ?>
</body>
</html>