<?php
include('../php_doc_root.php');

require_once("classes/mydb_class.php");
require_once("classes/hrap_class.php");
require_once("classes/crew_class.php");

require_once("includes/charts/chart_error_msg.php");

session_name('raprec');
session_start();

/*******************************************************************/
$bins = $_SESSION['normalization_bins_in_raps_before_retirement_chart']; // The number of 'bins' you want in the normalized graph (This value is specified in the 'includes/constants.php' file)
$max_raps_per_rope = $_SESSION['max_rope_life_uses']; //Number of uses before a rope MUST be retired (This value is specified in the 'includes/constants.php' file)
/*******************************************************************/

// Determine Scope (CREW or REGION) and OWNERSHIP (CREW ID or REGION ID)
switch(strtolower($_GET['scope'])) {
	case 'crew':
		$crew_id = mydb::cxn()->real_escape_string($_GET['crew']);
		$query = "SELECT uses_a + uses_b as total_uses FROM rope_use_view WHERE status = 'retired' && crew_affiliation_id = ".$crew_id;
		break;
	
	case 'region':
		$region_id = mydb::cxn()->real_escape_string($_GET['region']);
		$query = "SELECT uses_a + uses_b as total_uses FROM rope_use_view WHERE status = 'retired' && region = ".$region_id;
		break;
} // End: switch()

// The $rope_cat array tracks the number of ropes that are retired in each of the usage categories.
// The categories are: "Ropes that are retired after 0-39 rappels", "Ropes retired after 40-79 rappels", etc
$rope_cat = array();
for($i=1; $i<=$bins; $i++) {
	$rope_cat[$i] = 0;
}
$total_ropes = 0;

$bin_size = floor($max_raps_per_rope / $bins);

$result = mydb::cxn()->query($query);
while($row = $result->fetch_assoc()) {
	for($i=1; $i<=$bins; $i++) {
		if($i == $bins) {
			if($row['total_uses'] < ($max_raps_per_rope)) {
				$rope_cat[$i]++; //The top bin is enlarged in case $max_raps_per_rope is not divisible by $bins
				break; // Exit the for() loop
			}
		}
		else {
			if($row['total_uses'] < ($bin_size * $i)) {
				$rope_cat[$i]++;
				break; // Exit the for() loop
			}
		}
	}
	$total_ropes++;
}


for($i=0; $i<$bins; $i++) {
	if($i == ($bins-1)) $bin_labels[$i] = $bin_size*$i." to ".$max_raps_per_rope;
	else {
		$a = $bin_size*$i;
		$b = ($bin_size*($i+1))-1;
		$bin_labels[$i] = $a." to ".$b;
	}
}
/*
echo "Bins: ".$bins."\n";
echo "Max Raps Per Rope: ".$max_raps_per_rope."\n";
echo "Bin Size: ".$bin_size."\n";
print_r($bin_labels);
*/
if($total_ropes == 0) chart_error_msg("No ropes have been retired!");
else {
	$chart_data 	="<chart_data>\n"
					."	<row>\n"
					."		<null/>\n";
	
	foreach($bin_labels AS $label) {
		$chart_data .="		<string>".$label."</string>\n";
	}
	$chart_data .=	"	</row>\n"
					."	<row>\n"
					."		<string>% of retired ropes with designated raps</string>\n";
	foreach($rope_cat AS $idx=>$rope_count) {
		$pct = $rope_count/$total_ropes*100;
		$chart_data .="		<number label='".$pct."%' tooltip='".$pct."% of retired ropes\rhave only ".$bin_labels[$idx-1]." raps'>".$pct."</number>\n";
	}
	$chart_data .= "	</row>\n"
					."</chart_data>\n";
	
	$chart_type		="<chart_type>column</chart_type>\n";
	
	$chart_rect		="<chart_rect	x='25'
									y='40'
									width='200'
									height='150' />\n";
									
	$legend			="<legend	layout='horizontal'
								bullet='square'
								font='arial'
								bold='true'
								size='10'
								color='555555'
								alpha='90'
								width='250'
								x='0'
								y='0'
								
								transition='slide_left' delay='1' duration='1'/>\n";
	
	$axis_value_label	="";
	$chart_label	="<chart_label	position='right' />";
	$axis_value		="<axis_value min='0' max='100' size='10' color='333333' />\n";
	$axis_category	="<axis_category size='10' color='333333' orientation='diagonal_up' />\n";
	
	$transition		="<chart_transition		type='scale'
											delay='0'
											duration='1'
											order='all'/>\n";
	
	
	echo "<chart>\n"
		.$chart_type
		.$chart_data
		.$chart_rect
		.$legend
		.$transition
		.$chart_label
		.$axis_value
		.$axis_category
		."</chart>";
}

?>
