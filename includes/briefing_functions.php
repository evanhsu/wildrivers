<?php

function mw_wotd() {
	$url = "http://www.merriam-webster.com/word/index.xml";
	//$url = "http://localhost/index.xml.xhtml";
	$curl_handle=curl_init();
	curl_setopt($curl_handle,CURLOPT_URL,$url);
	curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,20);
	curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
	$document = curl_exec($curl_handle);
	$document_lc = strtolower($document);
	curl_close($curl_handle);
	
	$wotd_offset_phrase	= "<p><strong><font color=\"#000066\">merriam-webster's word of the day for";
	$wotd_start_phrase	= "<strong>";
	$wotd_end_phrase	= "<strong>did you know?</strong>";

	$wotd_offset = strpos($document_lc, $wotd_offset_phrase) + 30;
	$start = strpos($document_lc,$wotd_start_phrase, $wotd_offset);
	$end = strpos($document_lc,$wotd_end_phrase,$start) - 8;
	
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" . substr($document,$start,$end-$start);
}

function get_chuck_norris_fact() {
	//require("scripts/connect.php");
	//$dbh = connect();
	
	$query = "SELECT MAX(id) as max, MIN(id) as min from chuck_norris_facts";
	$result= mydb::cxn()->query($query);
	$row = $result->fetch_assoc();
	
	$min_id = $row['min'];
	$max_id = $row['max'];
	
	$id = rand($min_id,$max_id);
	
	$query = "SELECT fact FROM chuck_norris_facts WHERE id LIKE '" . $id . "'";
	$result= mydb::cxn()->query($query);
	$row = $result->fetch_assoc();
	
	echo $row['fact'];
}

function get_civil_twilight() {
	//$url = "http://www.wunderground.com/US/OR/Grants_Pass.html";
	$url = "www.idcide.com/weather/or/grants-pass.htm";
	$curl_handle=curl_init();
	curl_setopt($curl_handle,CURLOPT_URL,$url);
	curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,20);
	curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
	$document = curl_exec($curl_handle);
	$document_lc = strtolower($document);
	curl_close($curl_handle);
	
	$offset_phrase	= "<td>civil twilight:";
	$start_phrase	= "<td>civil twilight:<br>";
	$end_phrase	= "</td>";

	$offset = strpos($document_lc, $offset_phrase) + 19;
	$start = strpos($document_lc,$start_phrase, $offset) + 23;
	$end = strpos($document_lc,$end_phrase,$start);
	
	return substr($document,$start,$end-$start);
	
}

function get_erc($zone) {
	$url = "www.ormic.org/intel/intelreport.shtml";
	$curl_handle=curl_init();
	curl_setopt($curl_handle,CURLOPT_URL,$url);
	curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,20);
	curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
	$document = curl_exec($curl_handle);
	$document_lc = strtolower($document);
	curl_close($curl_handle);
	
	// THIS FUNCTION NEEDS TO BE CHANGED TO FIND THE GREATEST ERC VALUE IN THE TABLE AND REPORT IT ALONG WITH THE NAME OF THE CORRESPONDING WEATHER ZONE.
	switch($zone) {
		case 'westside':
			$offset_phrase	= "<td>interior</td>";
			break;
		case 'interior':
			$offset_phrase = "<td>coast</td>";
			break;
	} // END switch($zone)
			
	$start_phrase	= "</td>";
	$end_phrase	= "</td>";

	$offset = strpos($document_lc, $offset_phrase) + strlen($offset_phrase) + 1;
	if(($start = strpos($document_lc,$start_phrase, $offset)) === false) {
		// An ERC value could not be found
		return false;
	}
	else {
		$start += 25;
	}

	if(($end = strpos($document_lc,$end_phrase,$start) === false)) {
		// An ERC value could not be found
		return false;
	}


	return substr($document,$start,$end-$start);
}
?>
