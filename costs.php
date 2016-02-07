<?php
include("classes/mydb_class.php");
include("classes/raprecdb_class.php");

$query = "SELECT Date, N, Helibase, AgencyRow1 as chargecode, Daily_Grand_Total_Cost FROM costs";
$result = mydb::cxn()->query($query);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">


<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Costs :: Siskiyou Rappel Crew</title>
<?php include("includes/basehref.html"); ?>
<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="about, fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
<meta name="Description" content="General information about the Siskiyou Rappel Crew. Get to know us." />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<!--script src="scripts/jquery_tablesorter/jquery.tablesorter.min.js"></script-->
<script type="text/javascript" src="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.js"></script>
<script language="javascript">
Number.prototype.formatMoney = function(c, d, t){
var n = this, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };

//Initiate the tablesorter script
$(document).ready(function() {
    $('#costTable').DataTable( {
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                } );
 
            // Total over this page
            pageTotal = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 5 ).footer() ).html(
                '$'+pageTotal.formatMoney() // +' ( $'+ total.toFixed(2) +' total)'
            );
        }
    } );
} );
</script>

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="scripts/jquery_tablesorter/themes/blue/style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.css"/>

<style>
td {
font-size:15px;
}
</style>
</head>


<body>
<div id="wrapper">
	<div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none" /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Siskiyou Rappel Crew - Costs</div>
    </div>
<?php include("includes/menu.php"); ?>

    <div id="content">
	<br />
        <div class="highlight1" style="font-size:18px">
		Cost Summaries Received
	</div>

	<div>
		This table collects data from the <span style="font-style:italic;">CostSummary-Submit_v1.pdf</span> form.<br />
		You can <a href="distribute/CostSummary-Submit_v1.pdf"> download the form here</a>.<br />
		The submit button won't work if you view the PDF in-browser - you need to 'Save link as...' and open it with Adobe Reader.
	</div>
	<br />
	<!--<table id="costTable" class="tablesorter">-->
	<table id="costTable">
	<thead>
	<tr>
		<th style="width:100px">Date</th>
		<th style="width:75px">N#</th>
		<th>Location</th>
		<th># of Rappels<br /><span style="font-style:italic; font-size:10px;">Queried from RapRec</span></th>
		<th>Charge Code</th>
		<th style="width:150px">Daily Cost</th>
	</tr>
	</thead>
	<tbody>
<?php
while($row = $result->fetch_assoc()) {
	$rapresult = raprecdb::cxn()->query("SELECT COUNT(*) as raps FROM view_rappels WHERE STR_TO_DATE(view_rappels.date,'%m/%d/%Y') = STR_TO_DATE('".$row['Date']."','%Y-%m-%d') AND (view_rappels.aircraft_tailnumber LIKE '%".$row['N']."%' OR CONCAT('%',aircraft_tailnumber,'%') like '".$row['N']."')");
	$rapdata = $rapresult->fetch_assoc();

	print "<tr>"
		."<td>".$row['Date']."</td>"
		."<td>".$row['N']."</td>"
		."<td>".$row['Helibase']."</td>"
		."<td>".$rapdata['raps']."</td>"
		."<td>".$row['chargecode']."</td>"
		."<td style=\"text-align:right;\">$".number_format($row['Daily_Grand_Total_Cost'],2)."</td></tr>";
}
print "<tfoot><tr><td colspan=4>&nbsp;</td><td style=\"text-align:right;\">Grand Total:</td><td style=\"text-align:right;\"></td></tr></tfoot>\n";
	
?>
	</tbody>
	</table>

    </div><!-- end 'content'-->
</div><!-- end 'wrapper'-->

<?php include("includes/footer.html"); ?>
<?php include("includes/google_analytics.html"); ?>
</body>
</html>
