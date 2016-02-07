<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Photo :: Siskiyou Rappel Crew</title>

<?php include("includes/basehref.html"); ?>

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
<meta name="Description" content="View enlarged images." />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />
</head>

<body>

<div id="wrapper">

	<div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none" /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Siskiyou Rappel Crew</div>
    </div>

	<?php include("includes/menu.php"); ?>

    <div id="content" style="text-align:center;">

    	<?php
			if(!isset($_GET['image'])) {
				echo "Photos can be found in <a href=\"photos/index.shtml\" alt=\"Photos\">The Photo Section</a>";
			}
			else {
				echo "<a href=\"javascript:history.go(-1)\" style=\"font-size:15px;font-weight:bold;\">[ Back ]</a><br>"
					."<div id=\"big_caption\">".stripslashes($_GET['caption'])."</div><br>"
					."<img src=\"" . urldecode($_GET['image']) . "\" alt=\"".$_GET['caption']."\" class=\"enlarged_image\"><br>\n"
					."<div style=\"font-size:10px; color:#bbb;\">Copyright &copy; ".date('Y')." siskiyourappellers.com<br>\n"
					."Commercial use is strictly prohibited.</div>\n";
			}
		?>

    </div><!-- end 'content' -->
</div><!-- end 'wrapper'-->

<?php include("includes/footer.html"); ?>
<?php include("includes/google_analytics.html"); ?>

</body>



</html>

