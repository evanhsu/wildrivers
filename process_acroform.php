<?php
include("classes/mydb_class.php");
if($_POST['passwd'] != 'siskiyou') exit(false); //Require valid password

//Determine if there is already cost summary data saved for this date and N# combination
$query = "SELECT id FROM costs where (costs.N='".$_POST['N']."' AND costs.Date=STR_TO_DATE('".substr($_POST['Date'],2,8)."','%Y%m%d'))";
$result = mydb::cxn()->query($query);

if($result->num_rows > 0) {
	//An entry already exists for this tailnumber on this date.
	//Delete the existing entry
	$row = $result->fetch_assoc();
	$existing_entry_id = $row['id'];
	$query = "DELETE from costs where id = ".$existing_entry_id;
	$result = mydb::cxn()->query($query);
}

//The following fields need to be enclosed in quotes when inserted into the dB
$text_fields = array(
"Agency",
"AgencyRow1",
"AgencyRow2",
"AgencyRow3",
"AgencyRow4",
"Flight_Invoice_Reference_Numbers",
"Helibase",
"Incident",
"MakeModel",
"Managers_Name",
"N",
"Other_Specify",
"form_version");

//The following checkbox fields with have a value of either "Yes" or "" (empty string) and need to be converted to a 0 or 1 for insertion into the dB
$checkbox_fields = array(
"CWN",
"Exclusive_Use_Checkbox",
"Initial_Attack_Checkbox",
"Large_Fire_Checkbox",
"Project_Checkbox",
"Type_1",
"Type_2",
"Type_3",
"Type_other");

//The following fields should be omitted altogether
$fields_to_omit = array(
"passwd",
"Submit");

//Prepare the INSERT statement
$keys = array();
$values = array();
foreach($_POST as $key=>$value) {
	if(in_array($key,$fields_to_omit)) continue; //Don't insert these fields to dB

	$keys[] = $key;
	if(in_array($key,$text_fields)) {
		//Enclose text fields in quotes
		$values[]="'".$value."'";
	}
	else if (in_array($key,$checkbox_fields)) {
		//Convert "Yes" to 1 and "" to 0
		$values[] = ($value == "Yes" ? 1 : 0);
	}
	else if ($key == "Date") {
		//If this is the Date field, strip the first few characters that Adobe PDF appends (D%3A) and use MySQL function to ensure this enters the dB as a Date object
		$values[] = "STR_TO_DATE('".substr($value,2,8)."','%Y%m%d')";
	}
	else {
		//Insert non-text values without quotes, and replace a blank value with "NULL"
		$values[] = ($value == "" ? "NULL" : $value);
	}
}

$query = "INSERT INTO costs ("
	.implode(",",$keys)
	.") VALUES ("
	.implode(",",$values)
	.")";

$result = mydb::cxn()->query($query);

?>
