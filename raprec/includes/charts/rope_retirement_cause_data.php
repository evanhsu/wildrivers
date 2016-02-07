<?php
include('../php_doc_root.php');

require_once("classes/mydb_class.php");
require_once("classes/hrap_class.php");
require_once("classes/crew_class.php");

require_once("includes/charts/chart_error_msg.php");

session_name('raprec');
session_start();

// Determine Scope (CREW or REGION) and OWNERSHIP (CREW ID or REGION ID)
switch(strtolower($_GET['scope'])) {
	case 'crew':
		$crew_id = mydb::cxn()->real_escape_string($_GET['crew']);
		$query = "SELECT retired_category, COUNT( id ) AS count FROM rope_use_view WHERE status = 'retired' && crew_affiliation_id = ".$crew_id." GROUP BY retired_category";
		break;
	
	case 'region':
		$region_id = mydb::cxn()->real_escape_string($_GET['region']);
		$query = "SELECT retired_category, COUNT( id ) AS count FROM rope_use_view WHERE status = 'retired' && region = ".$region_id." GROUP BY retired_category";
		break;
} // End: switch()


$total_retired_ropes = 0;

$result = mydb::cxn()->query($query);
while($row = $result->fetch_assoc()) {
	$temp_ropes[] = array('cause'=>ucwords(str_replace("_"," ",$row['retired_category'])), 'count'=>$row['count']);
	$total_retired_ropes += $row['count'];
}

if($total_retired_ropes == 0) chart_error_msg("No ropes have been retired!");
else {
	
	$chart_data 	="<chart_data>\n"
					."	<row>\n"
					."		<null/>\n";
	
	foreach($temp_ropes AS $row_num=>$row) {
		$chart_data .="		<string>".str_replace(" ","\r",$row['cause'])."</string>\n";
	}
	$chart_data .=	"	</row>\n"
					."	<row>\n"
					."		<string>Retired Ropes</string>\n";
	foreach($temp_ropes AS $row_num=>$row) {
		$pct = round($row['count'] / $total_retired_ropes * 100,0);
		$chart_data .="		<number label='".str_replace(" ","\r",$row['cause'])."' tooltip='".$row['cause']." accounts for\r".$pct."% of retired ropes'>".$row['count']."</number>\n";
	}
	$chart_data .= "	</row>\n"
					."</chart_data>\n";
	
	$chart_type		="<chart_type>pie</chart_type>\n";
	
	$chart_rect		="<chart_rect	x='94'
									y='25'
									width='150'
									height='200' />\n";
									
	$legend			="<legend	layout='vertical'
								bullet='square'
								font='arial'
								bold='true'
								size='10'
								color='555555'
								alpha='90'
								x='0'
								y='25'
								
								transition='slide_down' delay='1' duration='1'/>\n";
	
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
		."</chart>";
}

?>
