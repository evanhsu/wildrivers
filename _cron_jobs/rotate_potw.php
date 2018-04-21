<?php
	require_once("../classes/mydb_class.php");
	
	
	// Find the most-recently-selected photo (the one currently being displayed on the photo_of_the_week page)
	$query = "SELECT id, datediff(now(),last_date_used) as age FROM photo_of_the_week ORDER BY last_date_used DESC,id LIMIT 1";
	$result = mydb::cxn()->query($query);
	$row = $result->fetch_assoc();
	
	if($row['age'] > 7) next_photo();
	
	
function next_photo() {
	// Find the ID of the next photo to use
	$query = "SELECT id FROM photo_of_the_week ORDER BY last_date_used,id LIMIT 1";
	$result = mydb::cxn()->query($query);
	$row = $result->fetch_assoc();
	$next_photo_id = $row['id'];
	
	$query = "UPDATE photo_of_the_week SET last_date_used = curdate() where id = ".$next_photo_id;
	$result = mydb::cxn()->query($query);
}
?>