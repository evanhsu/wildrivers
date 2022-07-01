<?php
require_once("../classes/mydb_class.php");

function new_crop_length($needle, $sub_length, $content) {
	$new_length = $sub_length;
	$needle_length = strlen($needle);
	for($i = $sub_length-$needle_length;$i<$sub_length;$i++) {
		if(strtolower(substr($content,$i,$needle_length)) == strtolower($needle)) $new_length = $i + $needle_length;
	}
	return $new_length;
}

function update_rss_feed() {
	
	$description_length = 300;
	$title_length = 40;
	$num_entries = 4; // The number of blog entries to include in the RSS feed
	
	$query = "SELECT name, unix_timestamp(date) as date, status FROM current_sticky WHERE 1";
	$result= mydb::cxn()->query($query);
	$sticky = $result->fetch_assoc();

	$query = "SELECT name, unix_timestamp(date) as date, status FROM current ORDER BY date DESC LIMIT ".$num_entries;
	$result = mydb::cxn()->query($query);
	
	$rss = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n\n"
		  ."<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n"
		  ."<channel>\n\n"
	  
		  ."<title>SRC - Crew Status</title>\n"
		  ."<link>https://wildrivers.firecrew.us/current.php</link>\n"
		  ."<description>\n"
		  ."	The Crew Status Page provides information on the whereabouts of crewmembers\n"
		  ."	and the various projects that we're currently working on.\n"
		  ."</description>\n\n"

		  ."<atom:link href=\"http://tools.siskiyourappellers.com/rss.php\" rel=\"self\" type=\"application/rss+xml\" />\n"
	  
		  ."<lastBuildDate>" . date("r") . "</lastBuildDate>\n"
		  ."<language>en-us</language>\n\n";
	
	//Post the "sticky" content at the top of the RSS Feed
	if(strlen($sticky['status']) > 0) {
		if(strlen($sticky['status']) > $title_length) $content_title = substr($sticky['status'],0,$title_length) . "...";
		else $content_title = $sticky['status'];
		$timestamp_sticky = date("r", $sticky['date']);
		$timestamp_title = date("M jS", $sticky['date']);
		$rss .="<item>\n"
			  ."<title>[!] " . $content_title . "</title>\n"
			  ."<link>http://tools.siskiyourappellers.com/current.php</link>\n"
			  ."<guid>http://tools.siskiyourappellers.com/current.php?id=".$sticky['date']."</guid>\n"
			  ."<pubDate>" . $timestamp_sticky . "</pubDate>\n"
			  ."<description>" . $sticky['status'] . "</description>\n"
			  ."</item>\n\n";
	}
	
	
	
	//Add the most recent updates to the RSS feed
	while($row = $result->fetch_assoc()) {
	  //Replace <br> with a single space - " "
	  $status = str_replace(array("<br>","<br />","<BR>","<BR />")," ",$row['status']);
	  
	  //Generate a Title for this update
	  if(strlen($status) > $title_length) $content_title = substr($status,0,$title_length) . "...";
	  else $content_title = $status;
	  
	  //Format the date strings
	  $timestamp_status = date("r", $row['date']);
	  $timestamp_title = date("M jS", $row['date']);
	  
	  $rss .="<item>\n"
			."<title>[" . $timestamp_title . "] " . $content_title . "</title>\n"
			."<link>http://tools.siskiyourappellers.com/current.php</link>\n"
			."<guid>http://tools.siskiyourappellers.com/current.php?id=".$row['date']."</guid>\n"
			."<pubDate>" . $timestamp_status . "</pubDate>\n"
			."<description>" . $status . "</description>\n"
			."</item>\n\n";
	}// END WHILE
	
	$rss .="</channel>\n"
		  ."</rss>\n";
	
	//Open the rss.xml file for writing
	$rss_file = fopen("../rss.xml","w");
	fwrite($rss_file, $rss);
}
?>