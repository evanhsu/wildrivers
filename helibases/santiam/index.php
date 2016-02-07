<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">


<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Santiam Helibase Info</title>

<?php include("../includes/basehref.html"); ?>

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="intelligence, intel, weather, SIT, forecast, info, information, fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
<meta name="Description" content="Flying into the Santiam Helibase on the Willamette National Forest? Find airport information, callup frequencies and facility maps here." />


<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />

<style>
th.infobox {
background-color:#aabb99;
font-weight: bold;
font-size: 1.5em;
text-align: left;
padding: 2px 0 5px 5px;
border-bottom: 2px solid #888888;
}

</style>
</head>

<body>

<div id="wrapper">
	<div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none;" /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Santiam Helibase - Willamette National Forest</div>
    </div>

	<?php include("../includes/menu.php"); ?>

	<div id="content">
    	<br />
        <div style="border: 2px solid #888888; overflow: hidden; width:400px; height:500px; float:left; margin:10px;">
          <table style="width:100%;height:100%">
            <tr><th class="infobox" style="height:30px;">Sectional (<a href="https://skyvector.com/?ll=44.434555556,-121.942277778&chart=301&zoom=4" target="_blank">Full Map</a>)</th></tr>
            <tr><td style="text-align:left;">
            <embed src="https://skyvector.com/?ll=44.434555556,-121.942277778&chart=301&zoom=4" style="width:100%;height:100%"></embed>
            </td></tr>
          </table>
        </div>
        <div style="border: 2px solid #888888; overflow: hidden; width:400px; float:left; margin:10px;">
          <table style="width:100%;height:100%">
            <tr><th class="infobox" style="height:30px;">Initial Callup & Approach</th></tr>
            <tr><td style="text-align:left;">
            	<ol><li>Advise "Santiam Traffic" of your approach on CTAF.</li>
                	<li>Call "Santiam Helibase" on the TOLC frequency for your pad assignment.</li>
                    <li>Monitor CTAF and A/A for airspace deconfliction with active aircraft.</li>
                    <li>The dirt runway is extremely dusty, but the gravel is better. Avoid hovering over the dirt areas as you approach your pad. Recommend final approach from the south, over the trees to minimize dustout.</li>
                </ol>
            </td></tr>
          </table>
        </div>
        <div style="border: 2px solid #888888; overflow: hidden; width:400px; float:left; margin:10px;">
          <table style="width:100%;height:100%">
            <tr><th class="infobox" style="height:30px;">Frequencies</th></tr>
            <tr><td style="text-align:left;">
            	<table style="width:100%">	<tr><th style="width:150px">Name</th><th>Rx</th><th>Tone</th><th>Tx</th><th>Tone</th></tr>
                		<tr style="background-color:#eeeeee;"><td>Command (Bingham Fire)</td><td>170.0125</td><td>123.0</td><td>165.2500</td><td>123.0</td></tr>
                        <tr style="background-color:#eeeeee;"><td>Coffin Mtn. (Eugene Dispatch)</td><td>164.9125</td><td>103.5</td><td>164.1000</td><td>131.8</td></tr>
                        <tr style="background-color:#eeeeee;"><td>National Flight Following</td><td>168.6500</td><td>110.9</td><td>168.6500</td><td>110.9</td></tr>
                        <tr style="background-color:#eeeeee;"><td>TOLC</td><td>168.5625</td><td>----</td><td>168.5625</td><td>----</td></tr>
                        <tr style="background-color:#eeeeee;"><td>DECK</td><td>163.1000</td><td>----</td><td>163.1000</td><td>----</td></tr>
                        <tr style="background-color:#eeeeee;"><td>A/G Primary</td><td>169.1500</td><td>----</td><td>169.1500</td><td>----</td></tr>
                        <tr style="background-color:#eeeeee;"><td>CTAF</td><td>122.9</td><td>----</td><td>122.9</td><td>----</td></tr>
                        <tr style="background-color:#eeeeee;"><td>A/A</td><td>120.0250</td><td>----</td><td>120.0250</td><td>----</td></tr>
                </table>
            </td></tr>
          </table>
        </div>
        <div style="border: 2px solid #888888; overflow: hidden; width:400px; float:left; margin:10px;">
          <table style="width:100%;height:100%">
            <tr><th class="infobox" style="height:30px;">Phone List</th></tr>
            <tr><td style="text-align:left;">
            	<table style="width:100%">	<tr><th style="width:150px">Name</th><th>Number</th></tr>
                		<tr style="background-color:#eeeeee;"><td>Santiam Helibase</td><td>541-214-5832</td></tr>
                        <tr style="background-color:#eeeeee;"><td>Eugene Dispatch Aircraft</td><td>541-225-6400</td></tr>
                </table>
            </td></tr>
          </table>
        </div>
        <div style="border: 2px solid #888888; overflow: hidden; width:825px; float:left; margin:10px;">
          <table style="width:100%;height:100%">
            <tr><th class="infobox" style="">Helibase Deck Layout</th></tr>
            <tr><td style="text-align:center;">
            	<img src="santiam/santiamdeckdiagram.png" />
            </td></tr>
          </table>
        </div>
        <br style="clear:both;" />
	</div><!-- end 'content'-->
</div><!-- end 'wrapper'-->

<?php include("../includes/footer.html"); ?>
<?php include("../includes/google_analytics.html"); ?>
</body>
</html>