<?php
	//require_once("classes/mydb_class.php");
	/*
	$address_list = array();
	$address_list[] = "http://tools.siskiyourappellers.com/max_weather.php";
	$address_list[] = "http://r6status.centraloregonhelitack.com/summary.php";
	$address_list[] = "http://tools.siskiyourappellers.com/current.php";
	$address_list[] = "http://radar.srh.noaa.gov/fire/?lat=42.4390069&lon=-123.3283925&zoom=9";
	*/
	
	$interval = $_GET["interval"];
	if($interval == "") $interval = 30000;
	else $interval = $interval * 1000; // Convert to milliseconds
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Intel Kiosk:: Siskiyou Rappel Crew</title>
<?php include("includes/basehref.html"); ?>
<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, fire management, oregon, helitack, hecm, crew" />
<meta name="Description" content="Intelligence Ticker: a pageflip kiosk view that scrolls through several intel sources." />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />

<script type="text/javascript">
var page_list=[];
var nextItem = "";
var t;
var i=0;
var timer_is_on=1;
var direction_to_rotate = 1; //1 = forward, -1 = backwards

page_list.push("http://tools.siskiyourappellers.com/max_weather.php");
page_list.push("http://staffing.natrap.com");
//page_list.push("http://tools.siskiyourappellers.com/current.php");
page_list.push("http://ormic.org/intel/intelreport.shtml");
page_list.push("https://lightning.nifc.gov/");
nextItem = page_list[0];

function rotatePages(direction_to_rotate) {
	clearTimeout(t);
	if(direction_to_rotate !== 1 && direction_to_rotate !== -1) {
		i = i + 1;
	}
	else {
		i = i + direction_to_rotate;
	}
	
	if(i >= page_list.length) {
		i=0;
	}
	else if(i < 0) {
		i = page_list.length - 1;
	}
	nextItem = page_list[i];
	document.getElementById('ticker_window').src = nextItem;
	
	t = setTimeout("rotatePages(1)",<?php print $interval; ?>);
}
  
function toggleTimer() {
	if(!timer_is_on) {
		timer_is_on=1;
		document.getElementById('pause_button').innerHTML = "Pause";
		rotatePages();
	}
	else {
		timer_is_on=0;
		document.getElementById('pause_button').innerHTML = "Resume";
		clearTimeout(t);
	}

function nextItem() {
	rotatePages();
	}

}
</script>

</head>


<body style="text-align:center;height:100%;" onload="rotatePages();">
	<form name="interval_form" action="<?php $_SERVER['PHP_SELF']; ?>" method="get">
    	Interval (Seconds):<input name="interval" type="text" value="<?php print ($interval/1000);?>" style="width:2em; font-face:verdana;" />
        <input type="submit" value="Set" style="width:3em;" />
    </form>
    <a id="prev_button" name="prev_button" style="cursor:pointer;" onclick="rotatePages(-1)">Previous</a>&nbsp; |
	<a id="pause_button" name="pause_button" style="cursor:pointer;" onclick="toggleTimer()">Pause</a> |&nbsp;
    <a id="next_button" name="next_button" style="cursor:pointer;" onclick="rotatePages()">Next</a><br />
	<iframe id="ticker_window" name="ticker_window" style="background-color:#fff; width:100%; height:100%; min-height:100%; margin:0; padding:0; border:none;" src=""></iframe>

<?php include("includes/google_analytics.html"); ?>
</body>
</html>
