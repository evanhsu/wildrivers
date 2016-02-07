<?php
include('../php_doc_root.php');

require_once("classes/mydb_class.php");
require_once("classes/hrap_class.php");
require_once("classes/crew_class.php");

require_once("includes/charts/chart_error_msg.php");

session_name('raprec');
session_start();

$max_rope_life = $_SESSION['max_rope_life_time']; //Number of years before a rope MUST be retired (This value is specified in the 'includes/constants.php' file)

$query = "SELECT serial_num, life_days_remaining, status, in_service_date, DATEDIFF( STR_TO_DATE( retired_date,  '%m/%d/%Y' ) , STR_TO_DATE( in_service_date,  '%m/%d/%Y' ) ) AS retired_age FROM rope_use_view WHERE id = ".$_GET['rope_id'];
$result = mydb::cxn()->query($query);
if(!$result) chart_error_msg("Rope not found!");
else {
	$row = $result->fetch_assoc();

	if($row['status'] != "retired") {
		$days_in_service = (365 * $max_rope_life) - $row['life_days_remaining'];
		$tooltip1 = "This rope has been\rIn Service for ".$days_in_service." days";
		if($row['life_days_remaining'] > 0) {
			$used_life = round(100 * ((365 * $max_rope_life) - $row['life_days_remaining']) / (365 * $max_rope_life),1); // A percentage of max_rope_life
			$remaining_life = round(100 - $used_life,1); // The remaining percentage of max_rope_life
		}
		else {
			$used_life = 100;
			$remaining_life = 0;
		}
		$tooltip2 = "This rope must be\rretired in ".$row['life_days_remaining']." days";
	}
	else { // Rope is retired
		  $used_life = round(100 * ($row['retired_age'] / (365 * $max_rope_life)),1); // A percentage of max_rope_life
		  $remaining_life = round(100 - $used_life,1); // The remaining percentage of max_rope_life
		  $days_in_service = $row['retired_age'];
		  $tooltip1 = "This rope was retired\rafter ".$days_in_service." days in service";
		  $tooltip2 = "This rope had ".$row['life_days_remaining']." days\rof useful life remaining\rwhen it was retired";
	}
	
	
	$chart_data 	="<chart_data>\n"
					."	<row>\n"
					."		<null/>\n"
					."		<string>Used Life</string>\n"
					."		<string>Remaining Life</string>\n"
					."	</row>\n"
					."	<row>\n"
					."		<string>% of 5-Year Lifespan</string>\n"
					."		<number label='".$used_life."%\rUsed' tooltip='".$tooltip1."'>".$used_life."</number>\n"
					."		<number label='".$remaining_life."%\rRemaining' tooltip='".$tooltip2."'>".$remaining_life."</number>\n"
					."	</row>\n"
					."</chart_data>\n";
					
	$series_color	="	<series_color>
							<color>888888</color>
							<color>11CC11</color>
						</series_color>";
	   
	$chart_type		="<chart_type>pie</chart_type>\n";
	
	$chart_rect		="<chart_rect	x='50'
									y='40'
									width='150'
									height='200' />\n";
	
	$chart_label	="<chart_label	size='10' />\n";
	
	$legend			="<legend	layout='horizontal'
								bullet='square'
								font='arial'
								bold='true'
								size='10'
								color='555555'
								alpha='90'
								x='0'
								y='10'
								width='250'
								height='30'
								
								transition='slide_down' delay='1' duration='1'/>\n";
	
	$transition		="<chart_transition		type='scale'
											delay='0'
											duration='1'
											order='all'/>\n";
	
	
	echo "<chart>\n"
		.$chart_type
		.$chart_data
		.$chart_rect
		.$chart_label
		.$series_color
		.$legend
		.$transition
		."</chart>";
} // End if(!$result) else 
?>
