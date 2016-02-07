<?php
include('../php_doc_root.php');

require_once("classes/mydb_class.php");
require_once("classes/hrap_class.php");
require_once("classes/crew_class.php");

require_once("includes/charts/chart_error_msg.php");

session_name('raprec');
session_start();

// Get Regional Data

$query = "
	SELECT count(hraps.id) as hrap_count FROM hraps 
	INNER JOIN rosters ON (rosters.hrap_id = hraps.id AND rosters.year = ".$_SESSION['current_view']['year'].")
	INNER JOIN crews ON (crews.id = rosters.crew_id AND crews.region = '".$_SESSION['current_view']['crew']->get('region')."')";
$result = mydb::cxn()->query($query);
if(!$result) chart_error_msg("Error counting rappels:\n".mydb::cxn()->error);
else $row = $result->fetch_assoc();
$total_hraps_in_region = $row['hrap_count'];


$query = "
	SELECT operation_type, count(raps) AS raps 
	FROM rap_type_count 
	WHERE year = '".$_SESSION['current_view']['year']."' AND region = '".$_SESSION['current_view']['crew']->get('region')."' 
	GROUP BY operation_type";
$result = mydb::cxn()->query($query);
if(!$result) echo "Error: ".mydb::cxn()->error;

$region_avg = array('operational'=>0, 'proficiency_live'=>0, 'proficiency_tower'=>0, 'certification_new_aircraft'=>0, 'certification_new_hrap'=>0);
while($row = $result->fetch_assoc()) {
	if(array_key_exists($row['operation_type'],$region_avg)) $region_avg[$row['operation_type']] = round($row['raps'] / $total_hraps_in_region,1);
}


$region_avg['proficiency_live'] = $region_avg['proficiency_live'] + $region_avg['certification_new_aircraft'] + $region_avg['certification_new_hrap'];
$region_avg['total_live'] = $region_avg['proficiency_live'] + $region_avg['operational'];

// 


// Build chart
$chart_type		="<chart_type>stacked column</chart_type>\n";

$chart_data 	="<chart_data>\n"
				."	<row>\n"
				."		<null/>\n"
				."		<string>".$_SESSION['current_view']['hrap']->get('firstname')."\r".$_SESSION['current_view']['hrap']->get('lastname')."</string>\n"
				."		<string>Crew\rAverage</string>\n"
				."		<string>Region\rAverage</string>\n"
				."	</row>\n"
				."	<row>\n"
				."		<string>Operationals</string>\n"
				."		<number tooltip='".$_SESSION['current_view']['hrap']->get('firstname')." has ".$_SESSION['current_view']['hrap']->get('raps_this_year_operational')." Operationals' label=''>"
							.$_SESSION['current_view']['hrap']->get('raps_this_year_operational')."</number>\n"
				."		<number tooltip='The Crew Average is ".$_SESSION['current_view']['crew']->get('raps_this_year_per_person_operational')." Operationals' label=''>"
							.$_SESSION['current_view']['crew']->get('raps_this_year_per_person_operational')."</number>\n"
				."		<number tooltip='The Region Average is ".$region_avg['operational']." Operationals' label=''>".$region_avg['operational']."</number>\n"
				."	</row>\n"
				."	<row>\n"
				."		<string>Proficiencies</string>\n"
				."		<number tooltip='".$_SESSION['current_view']['hrap']->get('firstname')." has ".$_SESSION['current_view']['hrap']->get('raps_this_year_proficiency_live')." Proficiencies'";
if($_SESSION['current_view']['hrap']->get('raps_this_year_live') > 0) $chart_data .= " label='".$_SESSION['current_view']['hrap']->get('raps_this_year_live')." Total'";

$chart_data		.= ">".$_SESSION['current_view']['hrap']->get('raps_this_year_proficiency_live')."</number>\n"
				."		<number tooltip='The Crew Average is ".$_SESSION['current_view']['crew']->get('raps_this_year_per_person_proficiency_live')." Proficiencies'";
if($_SESSION['current_view']['crew']->get('raps_this_year_per_person_live') > 0) $chart_data .= " label='".$_SESSION['current_view']['crew']->get('raps_this_year_per_person_live')." Total\r(Crew Avg)'";

$chart_data		.= ">".$_SESSION['current_view']['crew']->get('raps_this_year_per_person_proficiency_live')."</number>\n"
				."		<number tooltip='The Region Average is ".$region_avg['proficiency_live']." Proficiencies'";
if(1) $chart_data .= " label='".$region_avg['total_live']." Total\r(Region Avg)'";

$chart_data		.= ">".$region_avg['proficiency_live']."</number>\n"
				."	</row>\n"
				."</chart_data>\n";

$legend			="<legend	layout='vertical'
							width='250'
							bullet='square'
							font='arial'
							bold='true'
							size='11'
							color='555555'
							alpha='90'
							x='0'
							y='0'
							
							transition='slide_left' delay='1' duration='1'/>\n";

$transition		="<chart_transition		type='scale'
										delay='0'
										duration='1'
										order='all'
                  />\n";

$chart_label	="<chart_label	position='top' />";

$axis_category	="<axis_category size='10' color='333333' />";

$raps_all_time	="<draw><text transition='slide_left'
				delay='1'
				duration='1'
				x='0'
				y='35' 
				width='250'  
				height='15' 
				h_align='center' 
				v_align='top' 
				rotation='0' 
				size='10' 
				color='bbbbbb' 
				alpha='100'
				>Total Rappels (All-Time): ".$_SESSION['current_view']['hrap']->get('raps_all_time_total')."</text></draw>\n";

echo "<chart>\n"
	.$chart_type
	.$chart_label
	.$legend
	.$axis_category
	.$transition
	.$chart_data
	.$raps_all_time
	."</chart>";
	
	
?>
