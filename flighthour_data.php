<?php



//include charts.php to access the SendChartData function
include_once("includes/charts/charts.php");
require("scripts/connect.php");

$dbh = connect();

//Determine which 5 years to display on the chart
$query = "SELECT max(year) as year FROM flighthours";
$result = mysql_query($query, $dbh) or die("dB query failed (get max year from flighthours): " . mysql_error());
$row = mysql_fetch_assoc($result);
$first_year = $row['year'] - 4;

//Get data for each month of each year
for($y=$first_year; $y<=$first_year+4; $y++) {
	$hours_one_year = array();
	$query = "SELECT month, year, hours FROM flighthours WHERE id like \"_$y\" or id like \"__$y\" ORDER BY month";
	$result = mysql_query($query, $dbh) or die("dB query failed ($query): " . mysql_error());
	
	while($row = mysql_fetch_assoc($result)) {
		$hours_one_year[$row['month']] = $row['hours']; //Store the flight hours for this month
	}
	for($i=6;$i<=10;$i++) {
		if(!isset($hours_one_year[$i])) $hours_one_year[$i] = 0; //If no data exists for this month, use 0 hours for the chart
	}
	$monthly_totals[$y] = $hours_one_year; //Push the entire year onto the master array
	$yearly_totals[$y] = array_sum($hours_one_year);
}

//Determine max value for monthly scale (find month with most flight hours)
$monthly_max = 0;
foreach($monthly_totals AS $year=>$months) {
	foreach($months AS $month=>$hours) {
		if($hours > $monthly_max) $monthly_max = $hours;
	}
}
$monthly_max_padded = $monthly_max * 1.1; //Add 10% to the top of the chart

//Determine max & min value for yearly scale (find year with most flight hours)
$yearly_max = max($yearly_totals); //The most hours flown in any one year
$yearly_min = min($yearly_totals); //The fewest hours flown in any one year
$yearly_min_padded = $yearly_min - ($yearly_max - $yearly_min)*0.1; //Pad the bottom of the chart by 10%
$yearly_max_padded = $yearly_max + ($yearly_max - $yearly_min)*0.1; //Pad the top of the chart by 10%


//Build yearly arrays for chart
$legend = array ( "",       $first_year, $first_year+1, $first_year+2, $first_year+3, $first_year+4 );
$legend_blank = array("","","","","","");

$june_vals[] = "Jun";
$july_vals[] = "Jul";
$aug_vals[] = "Aug";
$sept_vals[] = "Sept";
$oct_vals[] = "Oct";
$year_vals[] = "Year Total";

$june_labls[]= null;
$july_labls[]= null;
$aug_labls[] = null;
$sept_labls[]= null;
$oct_labls[] = null;
$year_labls[]= null;

for($y=$first_year;$y<=$first_year+4;$y++) {
	$june_vals[] = As_Percentage($monthly_totals[$y][6],0,$monthly_max_padded);
	$june_labls[]= $monthly_totals[$y][6];
	
	$july_vals[] = As_Percentage($monthly_totals[$y][7],0,$monthly_max_padded);
	$july_labls[]= $monthly_totals[$y][7];
	
	$aug_vals[] = As_Percentage($monthly_totals[$y][8],0,$monthly_max_padded);
	$aug_labls[]= $monthly_totals[$y][8];
	
	$sept_vals[] = As_Percentage($monthly_totals[$y][9],0,$monthly_max_padded);
	$sept_labls[]= $monthly_totals[$y][9];
	
	$oct_vals[] = As_Percentage($monthly_totals[$y][10],0,$monthly_max_padded);
	$oct_labls[]= $monthly_totals[$y][10];
	
	$year_vals[] = As_Percentage($yearly_totals[$y],$yearly_min_padded,$yearly_max_padded);
	$year_labls[]= $yearly_totals[$y];
}

//Determine tick mark intervals for monthly totals
$m = $monthly_max_padded / 4;

//Determine tick mark intervals for yearly totals
$y = ($yearly_max_padded - $yearly_min_padded) / 4;


//______________________________________________________
function As_Percentage ( $value, $min, $max ){
   return ($value-$min)*100/($max-$min);
}
//_______________________________________________________



$chart [ 'chart_rect' ] = array ( 'x'=>75, 'y'=>50, 'width'=>500, 'height'=>250 );
$chart [ 'chart_type' ] = array ( "column", "column", "column", "column", "column", "line");
$chart [ 'series_color' ] = array ( "3333aa","5555bb","7777cc","aaaaee","ddddff", "eeaa00" ); 

$chart [ 'chart_data' ] = array ( $legend,$june_vals,$july_vals,$aug_vals,$sept_vals,$oct_vals,$year_vals );
$chart [ 'chart_value_text' ] = array ( $legend_blank,$june_labls,$july_labls,$aug_labls,$sept_labls,$oct_labls,$year_labls );

$chart [ 'axis_value' ] = array ( 'min'=>0, 'max'=>100, 'color'=>"3333aa" );
$chart [ 'axis_value_text' ] = array ( "0 hrs", round($m,0)." hrs", round(2*$m,0)." hrs", round(3*$m,0)." hrs", round(4*$m,0)." hrs");

for($i=0;$i<5;$i++){
   $chart [ 'draw' ][ $i ] = array ( 'type'=>"text", 'x'=>580, 'y'=>285-$i*61, 'text'=>round($yearly_min_padded+($i*$y),0)." hrs", 'color'=>"eeaa00" ); 
}

$chart [ 'legend_label' ] = array ( 'layout' => 'horizontal',
									'size'	 =>	11
								  );

$chart [ 'chart_value' ] = array (  'decimals'         =>  1, 
                                    'position'         =>  'top',
                                    'bold'             =>  'false', 
                                    'size'             =>  8, 
                                    'color'            =>  '333333'
                                 ); 

$chart [ 'chart_transition' ] = array ( 'type'      =>  "drop",
                                        'delay'     =>  0, 
                                        'duration'  =>  1, 
                                        'order'     =>  "category"                                 
                                      ); 

SendChartData ( $chart );

?>