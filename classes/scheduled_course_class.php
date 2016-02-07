<?php
require_once("../classes/mydb_class.php");

class scheduled_course {
	var $id;					// dB id of this scheduled_course entry in the 'scheduled_courses' dB table
	var $name;					// e.g. 'S-212'
	var $date_start;			// PHP 'DateTime' object
	var $date_end;				// PHP 'DateTime' object
	var $location;				// varchar(250)
	var $training_facility_id;	// The dB ID of the training facility
	var $g_cal_eventUrl;		// A Google Calendar URL
	var $comments;				// varchar(250)
	
	
	function scheduled_course() {
		// Constructor
		$this->date_start = new DateTime;
		$this->date_end = new DateTime;
	}
	
	
	function load($id) {
		if(!$this->exists($id)) throw new Exception('Scheduled course #'.$id.' could not be loaded because it does not exist');
		
		$query = "SELECT unix_timestamp(date_start), unix_timestamp(date_end), location, training_facility, name, g_cal_eventUrl, comments "
				."FROM scheduled_courses WHERE id = ".$id;
		
		$result = mydb::cxn()->query($query);
		if(mydb::cxn()->error != '') throw new Exception('Could not load scheduled course id '.$id);
		$row = $result->fetch_assoc();
		
		$this->date_start->setTimestamp($row['date_start']);
		$this->date_end->setTimestamp($row['date_end']);
		$this->location = $row['location'];
		$this->training_facility_id = $row['training_facility'];
		$this->g_cal_eventUrl = $row['g_cal_eventUrl'];
		$this->comments = $row['comments'];
	} // END function load()
	
	
	function save() {
		if(scheduled_course::exists($this->id)) {
			// UPDATE an existing entry
			$query = "UPDATE scheduled_courses SET "
					."name = '".$this->name."',"
					."date_start = from_unixtime(".$this->date_start->getTimestamp()."), "
					."date_end = from_unixtime(".$this->date_end->getTimestamp()."), "
					."location = '".$this->location."', "
					."training_facility = ".$this->training_facility_id.", "
					."g_cal_eventUrl = '".$this->g_cal_eventUrl."', "
					."comments = '".$this->comments."' "
					."WHERE id = ".$this->id;
			
			mydb::cxn()->query($query);
			if(mydb::cxn()->error != '') throw new Exception('Unable to save scheduled course id '.$this->id);
		}
		else {
			// INSERT a new dB entry
			$query = "INSERT INTO scheduled_courses (name, date_start, date_end, location, training_facility, g_cal_eventUrl, comments) "
					."VALUES ("
					."'".$this->name."', "
					."from_unixtime(".$this->date_start->getTimestamp()."), "
					."from_unixtime(".$this->date_end->getTimestamp()."), "
					."'".$this->location."', "
					.$this->training_facility_id.", "
					."'".$this->g_cal_eventUrl."', "
					."'".$this->comments."')";
			
			mydb::cxn()->query($query);
			if(mydb::cxn()->error != '') throw new Exception('Unable to save new scheduled course');
			else $this->id = mydb::cxn()->insert_id;  // If new entry was successfully saved, set $this-id to the dB-assigned autoincrement id
		}
		return 1;
	} // END function save()
	
	function get($var) {
		return $this->$var;
	} // END function get()
	
	function set($var,$val) {
		// ADD error checking here...
		$this->$var = $val;
	} // END function set()
	
	public function exists($id = false) {
		if(is_numeric($id)) {
			mydb::cxn()->query('SELECT count(*) FROM scheduled_courses WHERE id = '.$id);
			if(mydb::cxn()->affected_rows >= 1) return 1;
		}
		else return 0;
	} // End function exists()
	
} // END class scheduled_course
?>