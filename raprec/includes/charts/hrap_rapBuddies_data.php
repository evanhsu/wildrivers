<?php
include('../php_doc_root.php');

require_once("../../classes/mydb_class.php");
require_once("../../classes/hrap_class.php");
require_once("../../classes/crew_class.php");

require_once("includes/charts/chart_error_msg.php");

session_name('raprec');
session_start();

// Determine the 3 other HRAPS who have rappelled with THIS HRAP the most
$query = "SELECT hrap_name FROM view_rappels "
		."WHERE (operation_id IN (SELECT operation_id FROM rappels WHERE hrap_id = ".$_SESSION['current_view']['hrap']->get('id').")) "
		."&& (hrap_id <> ".$_SESSION['current_view']['hrap']->get('id').")";
$result = mydb::cxn()->query($query);

$fav = array(1=>array('name'=>'', 'raps'=>0), 2=>array('name'=>'', 'raps'=>0), 3=>array('name'=>'', 'raps'=>0));
$tally = array();

while($row = $result->fetch_assoc()) {

	if(array_key_exists($row['hrap_name'],$tally)) $tally[$row['hrap_name']]++;
	else $tally[$row['hrap_name']] = 1;
}
arsort($tally);
$tally_keys = array_keys($tally);

if(isset($tally_keys[0])) $fav[1] = array('name'=>$tally_keys[0], 'raps'=>$tally[$tally_keys[0]]);
if(isset($tally_keys[1])) $fav[2] = array('name'=>$tally_keys[1], 'raps'=>$tally[$tally_keys[1]]);
if(isset($tally_keys[2])) $fav[3] = array('name'=>$tally_keys[2], 'raps'=>$tally[$tally_keys[2]]);


$temp = explode(" ",$fav[1]['name']);
$fav[1]['firstname'] = $temp[0];

$temp = explode(" ",$fav[2]['name']);
$fav[2]['firstname'] = $temp[0];

$temp = explode(" ",$fav[3]['name']);
$fav[3]['firstname'] = $temp[0];

$chart_type		="<chart_type>bar</chart_type>\n";

$chart_data 	="<chart_data>\n"
				."	<row>\n"
				."		<null/>\n"
				."		<string>".ucwords(str_replace(" ","\r",$fav[1]['name']))."</string>\n"
				."		<string>".ucwords(str_replace(" ","\r",$fav[2]['name']))."</string>\n"
				."		<string>".ucwords(str_replace(" ","\r",$fav[3]['name']))."</string>\n"
				."	</row>\n"
				."	<row>\n"
				."		<string>Shared Raps</string>\n"
				."		<number label='".$fav[1]['raps']."' tooltip='".$_SESSION['current_view']['hrap']->get('firstname')." &lt;3 ".$fav[1]['firstname']."'>".$fav[1]['raps']."</number>\n"
				."		<number tooltip=''>".$fav[2]['raps']."</number>\n"
				."		<number tooltip=''>".$fav[3]['raps']."</number>\n"
				."</chart_data>\n";

$chart_rect		="<chart_rect	x='75'
								y='25'
								width='150'
								height='200' />\n";
								
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
										order='all'/>\n";

$chart_label	="<chart_label	position='right' />";

$axis_value		="<axis_value size='10' color='333333' />\n";
$axis_category	="<axis_category size='10' color='333333' orientation='diagonal_up' />\n";

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
				></text></draw>\n";

echo "<chart>\n"
	.$chart_type
	.$chart_label
	.$chart_rect
	.$legend
	.$axis_category
	.$axis_value
	.$transition
	.$chart_data
	.$raps_all_time
	."</chart>";
	
	
?>
