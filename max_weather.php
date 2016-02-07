<?php
	include_once("includes/briefing_functions.php");
	//include_once("classes/mydb_class.php");

	function get_header(&$document) {
		//$start = strpos($document,"FIRE WEATHER",50);
		$start = strpos($document,"FORECAST","25");
		$end = strpos($document, ".DISCUSSION",$start);
		return str_replace("\n","<br>\n",substr($document,$start,$end-$start-1));
	}

	function get_discussion(&$document) {
		$discussion_pos = strpos($document,".DISCUSSION...");
		$discussion_end = strpos($document,"\n\n",$discussion_pos);
		$discussion_length = $discussion_end - $discussion_pos - 14;
		return substr($document,$discussion_pos+14,$discussion_length);
	}

	function get_zone_num(&$document,$zone_offset) {
		//Zone Numbers are formatted in one of the following two ways:
		//ORZ617-121130-
		//ORZ615-618-121130-
		$eol = strpos($document,"\n",$zone_offset);
		$zone_num_strlen = ($eol - 7) - $zone_offset;
		$zone_num = substr($document,$zone_offset-1,$zone_num_strlen);
		
		//If zone number is formatted as "ORZ615-618", display as:
		//ORZ615 
		//ORZ618
		$zone_num = str_replace("-","<br />ORZ",$zone_num);
		return $zone_num;
	}

	function get_zone_name(&$document,$zone_offset) {
		$zone_name_pos = strpos($document,"\n",$zone_offset)+1;
		$zone_paragraph_end = strpos($document,"\n\n",$zone_name_pos);
		$last_break = $zone_name_pos;
		while(strpos($document,"\n",$last_break) < $zone_paragraph_end) {
			$zone_name_end = strpos($document,"\n",$last_break);
			$last_break = $zone_name_end+1;
		}
		$zone_name_length = $zone_name_end - $zone_name_pos - 2;
		return str_replace("-","<br>",substr($document,$zone_name_pos,$zone_name_length));
	}

	function get_max_temp(&$document,$zone_offset,&$sources) {
		//return array("50F",0,3);
		//Collect all numeric values between the phrase "* MAX TEMPERATURE..." and the next line break, then return the largest of those numbers.
		$start = strpos($document,"* MAX TEMPERATURE...",$zone_offset);
		$end = strpos($document,"\n",$start);
		
		$sentence = substr($document,$start,$end-$start);
		preg_match_all("/[0-9]+/",$sentence,$matches); // Grab all numbers from this line of text
		$flat_array = flatten_array($matches); 
		$max_temp = max($flat_array);
		$sources[max_temp] = substr($document,$start-25,100); //Grab 100 characters surrounding the max temp	

		return(array($max_temp,$start,$end-$start));
	}

	function get_min_humid(&$document,$zone_offset,&$sources) {
		//return array("50%",0,3);
		$start = strpos($document,"* MIN HUMIDITY.",$zone_offset);
		$end = strpos($document,"\n",$start); //Find a line break followed by a '*'

		$sentence = substr($document,$start,$end-$start);
		preg_match_all("/[0-9]+/",$sentence,$matches); // Grab all numbers from this line of text
		$flat_array = flatten_array($matches); 
		$min_humid = min($flat_array);
	
		$sources[min_humid] = substr($document,$start-25,100); //Grab 100 characters surrounding the min humidity
		return array($min_humid,$start,$end-$start);
	}

	function get_wind2(&$document,$zone_offset,&$sources) {
		//return array('spd'=>25,'dir'=>"NORTH");
		
		$start = strpos($document,"VALLEYS/LWR SLOPES...",$zone_offset);
		if(!$start) $start = strpos($document,"INLAND....",$zone_offset);
		//$end = strpos($document,"HAINES INDEX...",$start);
		$end1 = strpos($document,"\n\n",$start);
		$end2 = strpos($document,"* HAINES INDEX",$start);
		$end = min($end1,$end2);
		$string = substr($document,$start,$end-$start);

		preg_match_all("/(\bNORTH\b|NORTHEAST|\bEAST\b|SOUTHEAST|\bSOUTH\b|SOUTHWEST|\bWEST\b|NORTHWEST)/",$string,$directions,PREG_OFFSET_CAPTURE);
		preg_match_all("/[0-9]+/",$string,$speeds,PREG_OFFSET_CAPTURE);

		$spd = array(-1,0);							//$spd[0] = wind speed,	$spd[1] = string offset
		foreach($speeds[0] as $instance=>$info) {
			if($info[0] > $spd[0]) $spd = $info;	//$info[0] = wind speed,$info[1] = string offset
		}

		$dir = array('none',-1);					//$dir[0] = wind direction,	$dir[1] = string offset
		foreach($directions[0] as $instance=>$info) {
			if(($info[1] > $dir[1]) && ($info[1] < $spd[1])) $dir = $info;
		}

		$sources[wind] = $string;
		return array('spd'=>trim($spd[0]), 'dir'=>trim($dir[0]));
		
	}

	function get_lal(&$document,$zone_offset,&$sources) {
		$lal_pos = strpos($document,"LAL...",$zone_offset);
		if(!$lal_pos) return array("N/A",0,3);
		else {
			$start = $lal_pos;
			while(!is_numeric($document[$start])) $start++;

			$sources[lal] = substr($document,$start-25,100);
			return array(substr($document,$start,1),$start,1);
		}
	}

	function get_haines(&$document,$zone_offset,&$sources) {
		$haines_pos = strpos($document,"HAINES INDEX...",$zone_offset);
		if(!$haines_pos) return array("N/A",0,3);
		else {
			$start = $haines_pos;
			while(!is_numeric($document[$start])) $start++;

			$sources[haines] = substr($document,$start-25,100);
			return array(substr($document,$start,1),$start,1);
		}
	}
/*
	function flatten_array(array $array) {
		//Credit: github kohnmd
                $flattened_array = array();
                array_walk_recursive($array, function($a) use (&$flattened_array) { $flattened_array[] = $a; });
                return $flattened_array;
        }
*/	
	function flatten_array($array) {
	    $return = array();
	    foreach ($array as $key => $value) {
		if (is_array($value)){
		    $return = array_merge($return, flatten_array($value));
		} else {
		    $return[$key] = $value;
		}
	    }

	    return $return;
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Grab entire weather forecast
	$weather_url = "http://www.nws.noaa.gov/view/validProds.php?prod=FWF&node=KMFR";	//Southern Oregon
	$curl_handle=curl_init();
	curl_setopt($curl_handle,CURLOPT_URL,$weather_url);
	curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,20);
	curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
	$document = curl_exec($curl_handle);
	curl_close($curl_handle);
	
?>


<html>
<head>
<title>SRC Morning Briefing</title>
<style type="text/css">
	body {text-align: center; font-family:Verdana, Arial, Helvetica, sans-serif;}
	table {width:650px; margin: 0 auto 0 auto;}
	th {background-color:#6ae; font-size:14px; font-weight:bold;}
	td {font-size:12px; vertical-align:top; background-color:#fff;}
	.max {background-color:#d01; text-align:center; font-weight:bold;}
	.even {background-color:#4ae}
	.odd {background-color:#4cf}
	.highlight {background-color:#f03; font-weight: bold;}
</style>
<script type="text/javascript">
function change_image(image_id, new_path) {
	document.getElementById('image_id').src=new_path;
}
</script>
</head>

<body>
	<table>
    	<tr><td><b>Siskiyou Rappel Crew</b><br>
        		Briefing<br>
				<hr>
   				<b>Weather Extremes</b>
            </td>
        </tr>
    </table>
<?php
	$zone_offset = 0;	// Mark the start of each zone ("ORZ609 East Slopes of North Oregon" etc)
	$count = 0;
	$extremes = array(temperature=>-999,temperature_source=>"",humidity=>999,humidity_source=>"",lal=>-1,lal_source=>"",haines=>-1,haines_source=>"",windspd=>-1,windspd_source=>"",wind=>"DIR -100 MPH",wind_source=>"");

	$header = get_header($document);
	$discussion = get_discussion($document);
	$civil_twilight = get_civil_twilight();
	
	print "<table><tr><td><b>SOURCE</b><br><a href=\"$weather_url\">$weather_url</a><br>"
		. $header . "<br></td></tr>\n\n"
		. "<tr><td><b>DISCUSSION</b><br>\n" . $discussion . "<br><br></td></tr>\n"
		. "</table>\n\n";

	print "<table>"
	 ."<tr><td style=\"text-align:left;\"><span style=\"font-weight:bold;\">Civil Twilight:</span> ".$civil_twilight."</td></tr>\n"
     ."</table><br>\n\n";

	//$zones = array("ORZ609","ORZ610","ORZ611","ORZ630","ORZ631");	//Central Oregon Helitack
	$zones = array("ORZ619","ORZ620-622","ORZ621-623","ORZ624"); //Siskiyou Rappel Crew
	$sources = array(max_temp=>"",min_humid=>"",wind=>"",lal=>"",haines=>"");//This array stores the entire sentence from which the extremes were extracted.  Used for debugging when weird values show up in the Weather Extremes table.
	
	foreach($zones as $idx=>$zone_text)
	{
		try {
		  $zone_pos = strpos($document,$zone_text,$zone_offset);
		  $count++;
		  if($zone_pos === false) throw new Exception('Weather zone \''.$zone_text.'\' was not found in the weather report at '.$weather_url.'.');
  
		  $zone_offset = $zone_pos+1;
		  $zone_num = get_zone_num($document,$zone_offset);
		  $info[$count-1][zone_num][value] = $zone_num;
		  $zone_name = get_zone_name($document,$zone_offset);
		  $info[$count-1][zone_name][value] = $zone_name;
  
		  $max_temp = get_max_temp($document,$zone_offset,$sources);
		  $info[$count-1][temperature][value] = $max_temp[0];
		$info[$count-1][temperature][source] = $sources[max_temp];//Copy the entire sentence containing the max temperature from the weather report. 

		  if($max_temp[0] > $extremes[temperature]) {
			  $extremes[temperature] = $max_temp[0];
			  $red_pos[] = $max_temp[1];
			  $red_len[] = $max_temp[2];
		  }
  
		  $min_humid = get_min_humid($document,$zone_offset,$sources);
		  $info[$count-1][humidity][value] = $min_humid[0];
		$info[$count-1][humidity][source] = $sources[min_humid];  //Copy the entire sentence containing the min humidity from the weather report.

		  if($min_humid[0] < $extremes[humidity]) {
			  $extremes[humidity] = $min_humid[0];
			  $red_pos[] = $min_humid[1];
			  $red_len[] = $min_humid[2];
		  }
  
		  $wind = get_wind2($document,$zone_offset,$sources); // $wind = array( [spd] , [dir] )
		  $info[$count-1][wind][value] = $wind[dir] . " " . $wind[spd] . " MPH";
		$info[$count-1][wind][source] = $sources[wind];

		  if($wind[spd] > $extremes[windspd]) {
			  $extremes[windspd] = $wind[spd];
			  $extremes[wind] = $info[$count-1][wind][value];
		  }
		  //get_wind2($document,$zone_offset);
  
		  $lal = get_lal($document,$zone_offset,$sources);
		  $info[$count-1][lal][value] = $lal[0];
		$info[$count-1][lal][sources] = $sources[lal];

		  if($lal[0] > $extremes[lal]) {
			  $extremes[lal] = $lal[0];
			  $red_pos[] = $lal[1];
			  $red_len[] = $lal[2];
		  }
  
		  $haines = get_haines($document,$zone_offset,$sources);
		  $info[$count-1][haines][value] = $haines[0];
		$info[$count-1][haines][sources] = $sources[haines];

		  if($haines[0] > $extremes[haines]) {
			  $extremes[haines] = $haines[0];
			  $red_pos[] = $haines[1];
			  $red_len[] = $haines[2];
		  }
		} catch (Exception $e) {
			$info[$count-1][zone_num] = $zone_text;
			$info[$count-1][zone_name] = "Not Included in this report";
			$info[$count-1][temperature][value] = "X";
			$info[$count-1][humidity][value] = "X";
			$info[$count-1][wind][value] = "Not specified";
			$info[$count-1][lal][value] = "X";
			$info[$count-1][haines][value] = "X";
		}
	}


	print "<table>\n<tr><th> </th><th>WEATHER ZONE</th><th style=\"padding:0 5px 0 5px\">TEMP</th><th>RH</th><th style=\"padding:0 50px 0 50px\">WIND</th><th>LAL</th><th>HAINES</th></tr>\n";
	foreach($info as $row => $value_array) {
		$count++;
		print "<tr>";
		foreach($value_array as $cat => $options) {
			if($options[value] == $extremes[$cat]) print "<td class=\"highlight\"";
			else {
				if($count%2 == 0) print "<td class=\"even\"";
				else print "<td class=\"odd\"";
			}
			if($cat == "zone_name") print " style=\"font-size:10px\"";
			elseif($cat != "zone_num" && $cat != "zone_name" && $cat != "wind") print " style=\"text-align:center\"";
			elseif($cat == "wind") print " style=\"text-align:right\"";
			print ">".$options[value];
			if($cat == "temperature") print " &deg;F";
			elseif($cat == "humidity") print "%";
			print "</td>";
		}
		print "</tr>\n";
	}
	$extremes_celsius = ceil(((float)$extremes['temperature'] - 32) * 5.0 / 9.0); //Convert fahrenheit to celsius
	print "<tr><td colspan=\"7\"> &nbsp; </td></tr>\n";
	print "<tr><td colspan=\"2\" style=\"font-size:14px; font-weight:bold\">EXTREMES</td>";
	print "<td class=\"highlight\" style=\"text-align:center\">$extremes[temperature] &deg;F ($extremes_celsius &deg;C)</td>"
		. "<td class=\"highlight\" style=\"text-align:center\">$extremes[humidity]%</td>"
		. "<td class=\"highlight\" style=\"text-align:right\">$extremes[wind]</td>"
		. "<td class=\"highlight\" style=\"text-align:center\">$extremes[lal]</td>"
		. "<td class=\"highlight\" style=\"text-align:center\">$extremes[haines]</td></tr>\n\n";

	print "<tr><td colspan=\"7\"><br /><img src=\"images/weatherzonemap.jpg\" style=\"border:2px solid #000000;\"></td></tr>\n";
	
	print "</table><br />\n";

	print "<!-- ************************<< Source Info >>*************************";
	print_r($info);
	print "******************************************************************* -->";
	
?>

<br />

<?php
	// Gather info needed to plot ERC's on the pocket card image
/*
	$month = $_GET['month'];
	$day = $_GET['day'];
	$erc['interior'] = $_GET['erc_interior'];
	$erc['westside'] = $_GET['erc_westside'];
	
	if($erc['interior'] == "") $erc['interior'] = get_erc('interior');
	if($erc['westside'] == "") $erc['westside'] = get_erc('westside');
	if($month == "") $month = date('n');
	if($day == "") $day = date('j');
*/
?>

<?php
/*
	print "<table style=\"width:1000px; text-align:center; margin: 0 auto 0 auto;\">
			<tr><td><img id=\"erc_image\" src=\"../scripts/erc_plot_image.php?zone=interior&month=$month&day=$day&value=".$erc['interior']."\" style=\"display:inline;width:407px;height:300px;\"></td>\n
			<td><img id=\"erc_image\" src=\"../scripts/erc_plot_image.php?zone=westside&month=$month&day=$day&value=".$erc['westside']."\" style=\"display:inline;width:407px;height:300px;\"></td></tr>\n
			<tr><td>Today's Date: ".date("m-d-Y")."<br />\n
					Today's ERC: ".$erc['interior']."<br />\n
					<form name=\"erc_update_form_interior\" id=\"erc_update_form_interior\" action=\"".$_SERVER['PHP_SELF']."\" method=\"get\">
   					 <input type=\"hidden\" name=\"month\" value=\"".date('n')."\">
    				 <input type=\"hidden\" name=\"day\" value=\"".date('j')."\">
   					 Set ERC: <input type=\"text\" name=\"erc_interior\" style=\"width:5em;\" /> <input type=\"submit\" value=\"Update\" />
    				</form></td>
					
				<td>Today's Date: ".date("m-d-Y")."<br />\n
				Today's ERC: ".$erc['westside']."<br />\n
				<form name=\"erc_update_form_westside3\" id=\"erc_update_form_westside\" action=\"".$_SERVER['PHP_SELF']."\" method=\"get\">
   					 <input type=\"hidden\" name=\"month\" value=\"".date('n')."\">
    				 <input type=\"hidden\" name=\"day\" value=\"".date('j')."\">
   					 Set ERC: <input type=\"text\" name=\"erc_westside\" style=\"width:5em;\" /> <input type=\"submit\" value=\"Update\" />
    				</form></td></tr>\n";
*/
?>
    
<?php
	print "<table><tr><td colspan=\"7\"><br /><br /><hr /><b>SIZE-UP WORD OF THE DAY</b><br>\n";
	mw_wotd();
	print "<hr /></td></tr></table>\n";
?>
<?php include("includes/google_analytics.html"); ?>

</body>
</html>
