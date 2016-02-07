<?php
include('../php_doc_root.php');

require_once("classes/mydb_class.php");
require_once("classes/hrap_class.php");
require_once("classes/crew_class.php");

require_once("includes/charts/chart_error_msg.php");

session_name('raprec');
session_start();

$max_rope_uses = $_SESSION['max_rope_life_uses']; //Number of uses before a rope MUST be retired (This value is specified in the 'includes/constants.php' file)

$query = "SELECT serial_num, uses_a, uses_b FROM rope_use_view WHERE id = ".$_GET['rope_id'];
$result = mydb::cxn()->query($query);
if(!$result) chart_error_msg("Rope not found!");
else {
	$row = $result->fetch_assoc();
	
	
	$uses_a_pct = round(100 * $row['uses_a'] / $max_rope_uses); // A percentage of max_rope_uses
	$uses_b_pct = round(100 * $row['uses_b'] / $max_rope_uses); // A percentage of max_rope_uses
	$remaining_life_pct = 100 - $uses_a_pct - $uses_b_pct; // The remaining percentage of max_rope_uses
	$uses_a = $row['uses_a'];
	$uses_b = $row['uses_b'];
	$uses_total = $uses_a + $uses_b;
	$uses_remaining = $max_rope_uses - $uses_total;
	
	$chart_data 	="<chart_data>\n"
					."	<row>\n"
					."		<null/>\n"
					."		<string>Uses (End A)</string>\n"
					."		<string>Remaining\rUses</string>\n"
					."		<string>Uses (End B)</string>\n"
					."	</row>\n"
					."	<row>\n"
					."		<string>% of 200-Use Lifespan</string>\n"
					."		<number label='".$uses_a_pct."% Used\r(End A)' tooltip='This rope has been used\r".$uses_a." times on End A'>".$uses_a_pct."</number>\n"
					."		<number label='".$remaining_life_pct."%\rRemaining' tooltip='This rope must be retired\rafter ".$uses_remaining." more uses'>".$remaining_life_pct."</number>\n"
					."		<number label='".$uses_b_pct."% Used\r(End B)' tooltip='This rope has been used\r".$uses_b." times on End B'>".$uses_b_pct."</number>\n"
					."	</row>\n"
					."</chart_data>\n";
					
	$series_color	="	<series_color>
							<color>CC8888</color>
							<color>11CC11</color>
							<color>8888CC</color>
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
