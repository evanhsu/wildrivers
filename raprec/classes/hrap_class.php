<?php
require_once("classes/mydb_class.php");

class hrap {
	var $id;					// An Integer, referring to the database id for this HRAP (hraps.id)
	var $firstname;
	var $lastname;
	var $name;					// Firstname, Lastname, and any 'bling' that has been earned - i.e. John Doe <img src="bling_filename">
	var $crew;					// An Integer, referring to the database id of a crew (crews.id)
	var $gender;				// The word "male" or "female"
	var $birthdate;				// A unix date
	var $iqcs_num;				// A string (limited by the database to a max of 30 characters)
	var $year_of_first_rappel;	// A 4-digit year
	var $count_offset_proficiency;		// An integer denoting the number of PROFICIENCY rappels this HRAP has performed that are NOT recorded in the RapRec system.
	var $count_offset_operational;		// An integer denoting the number of OPERATIONAL rappels this HRAP has performed that are NOT recorded in the RapRec system.
	var $spot_count_offset_proficiency;	// An integer denoting the number of PROFICIENCY spots this Spotter has performed that are NOT recorded in the RapRec system.
	var $spot_count_offset_operational;	// An integer denoting the number of OPERATIONAL spots this Spotter has performed that are NOT recorded in the RapRec system.
	var $spotter;				// 0=No, 1=Yes, 2=Trainee
	var $headshot_filename;
	
	var $age;						// An integer, years of age (i.e. 26)
	var $number_years_rappelling; 	// (Total_months_since_1st_rappel) / (12months) + floor(1/3 * (Total_months % 12months))
	
	var $raps_this_year_total;				// Includes operationals, live proficiencies, & tower rappels
	var $raps_all_time_total;
	var $raps_this_year_operational;		// Only operationals
	var $raps_all_time_operational;
	var $raps_this_year_proficiency;		// Includes tower rappels & live rappels
	var $raps_all_time_proficiency;
	var $raps_this_year_proficiency_live;	// Only proficiencies from a hovering helicopter
	var $raps_all_time_proficiency_live;
	var $raps_this_year_live;				// Includes operationals & live proficiencies
	var $raps_all_time_live;
	
	var $spots_this_year_total;				// Includes operationals, live proficiencies, & tower rappels
	var $spots_all_time_total;
	var $spots_this_year_operational;		// Only operationals
	var $spots_all_time_operational;
	var $spots_this_year_proficiency;		// Includes tower rappels & live rappels
	var $spots_all_time_proficiency;
	var $spots_this_year_proficiency_live;	// Only proficiencies from a hovering helicopter
	var $spots_all_time_proficiency_live;
	var $spots_this_year_live;				// Includes operationals & live proficiencies
	var $spots_all_time_live;
	
	var $favorite_rope;		// array('num', 'uses') holds the rope_num & number of uses for this HRAP's most-used rope
	var $favorite_genie;	// array('num', 'uses') holds the genie_num & number of uses for this HRAP's most-used genie
	
	var $bling_filename;	// HRAPs can have a merit badge displayed after their name (based on number of rappels logged).  This is the filename of the appropriate badge.
	
	private $gettable_members = array(	'id', 'firstname', 'lastname', 'name', 'crew', 'gender', 'birthdate', 'iqcs_num',
										'year_of_first_rappel', 'count_offset_proficiency', 'count_offset_operational',
										'spot_count_offset_proficiency','spot_count_offset_operational',
										'spotter', 'headshot_filename',
										'age', 'number_years_rappelling', 
										'raps_this_year_total', 'raps_all_time_total',
										'raps_this_year_operational', 'raps_all_time_operational',
										'raps_this_year_proficiency', 'raps_all_time_proficiency',
										'raps_this_year_proficiency_live', 'raps_all_time_proficiency_live',
										'raps_this_year_live','raps_all_time_live',
										'spots_this_year_total', 'spots_all_time_total',
										'spots_this_year_operational', 'spots_all_time_operational',
										'spots_this_year_proficiency', 'spots_all_time_proficiency',
										'spots_this_year_live','spots_all_time_live',
										'favorite_rope', 'favorite_genie',
										'bling_filename');
	
/********** Constructor **************************************/
	function hrap() {
		//include_once("./includes/connect.php");
		//$this->mysqli = connect();
	}

	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: set() ***************************************************************************/
/*******************************************************************************************************************************/

	function set($var,$value) {
		if(isset($this->$var)) {
			try {
				$this->check_data($var,$value);
				$this->$var = $value;
				return true;
				
			} catch (Exception $e) {
				echo $e->getMessage()."\n";
			}
		}
		return false;
	} // End: set()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: get() ***************************************************************************/
/*******************************************************************************************************************************/
	function get($var) {
		if(in_array($var,$this->gettable_members)) return $this->$var;
		else throw new Exception('An invalid member variable name was passed to hrap::get() ('.$var.').');
	} // End: function get()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: load() **************************************************************************/
/*******************************************************************************************************************************/

	function load($hrap_id=false) {
		if(!$this->var_is_int($hrap_id)) {
			throw new Exception('You must specify an HRAP to load.'); //Make sure the $hrap_id is an integer
			return 0;
		}
		
		$hrap_id = mydb::cxn()->real_escape_string($hrap_id);
		
		$query = "SELECT id, firstname, lastname, gender, unix_timestamp(birthdate) as birthdate, iqcs_num, year_of_1st_rappel, count_offset_proficiency, count_offset_operational, spotter, spot_count_offset_proficiency, spot_count_offset_operational, headshot_filename FROM hraps WHERE id like ".$hrap_id;
		$result = mydb::cxn()->query($query);

		if(mydb::cxn()->affected_rows < 1) {
			throw new Exception('HRAP #'.$hrap_id.' was not found in the database');
			return 0; // Return with an error state of 0;
		}
		elseif(mydb::cxn()->affected_rows > 1) {
			throw new Exception('HRAP #'.$hrap_id.' appears more than once in the database');
			return 0; // Return with an error state of 0;
		}
		
		while($row = $result->fetch_assoc()) {
			$this->id = $row['id'];
			$this->firstname = stripcslashes($row['firstname']);
			$this->lastname = stripcslashes($row['lastname']);
			$this->gender = $row['gender'];
			$this->birthdate = date('m/d/Y',$row['birthdate']);
			$this->iqcs_num = $row['iqcs_num'];
			$this->year_of_1st_rappel = $row['year_of_1st_rappel'];
			$this->count_offset_proficiency = $row['count_offset_proficiency'];
			$this->count_offset_operational = $row['count_offset_operational'];
			$this->spotter = $row['spotter'];
			$this->spot_count_offset_proficiency = $row['spot_count_offset_proficiency'];
			$this->spot_count_offset_operational = $row['spot_count_offset_operational'];
			$this->headshot_filename = $row['headshot_filename'];
		}
		
		// Calculate this person's age
    	list($m,$d,$Y)	= explode("/",$this->birthdate);
    	$this->age		= date("Y") - $Y;
		if( date("md") < $m.$d ) $this->age--;

		
		$this->number_years_rappelling = 2; // (Total_months_since_1st_rappel) / (12months) + floor(1/3 * (Total_months % 12months))

		// Count this person's rappels and categorize them
		$this->raps_this_year_total = 0;
		$this->raps_all_time_total = $this->count_offset_proficiency + $this->count_offset_operational;
		
		$this->raps_this_year_operational = 0;
		$this->raps_all_time_operational = $this->count_offset_operational;
		
		$this->raps_this_year_proficiency = 0;
		$this->raps_all_time_proficiency = 0;
		
		$this->raps_this_year_proficiency_live = 0;
		$this->raps_all_time_proficiency_live = $this->count_offset_proficiency;
		
		$this->raps_this_year_live = 0; // Count all rappels that were performed from a hovering helicopter (operational & proficiencies, but NO tower rappels, mockups, etc)
		$this->raps_all_time_live = $this->count_offset_proficiency + $this->count_offset_operational;
		
		$this->spots_this_year_total = 0;
		$this->spots_all_time_total = $this->spot_count_offset_proficiency + $this->spot_count_offset_operational;
		
		$this->spots_this_year_operational = 0;
		$this->spots_all_time_operational = $this->spot_count_offset_operational;
		
		$this->spots_this_year_proficiency = 0;
		$this->spots_all_time_proficiency = 0;
		
		$this->spots_this_year_proficiency_live = 0;
		$this->spots_all_time_proficiency_live = $this->spot_count_offset_proficiency;
		
		$this->spots_this_year_live = 0; // Count all spots that were performed from a hovering helicopter (operational & proficiencies, but NO tower work, mockups, etc)
		$this->spots_all_time_live = $this->spot_count_offset_proficiency + $this->spot_count_offset_operational;
		
		$this->favorite_rope = array('num'=>'', 'uses'=>0);
		$this->favorite_genie= array('num'=>'', 'uses'=>0);
		$ropes = array();	// A temp array to count the occurrances of each rope in this rappeller's history
		$genies= array();	// A temp array to count the occurrances of each genie in this rappeller's history

		$query = "SELECT date, operation_type, rope_num, genie_num "
				."FROM view_rappels WHERE hrap_id = ".$this->id;
		$result = mydb::cxn()->query($query);
		
		if($result) {
		  while($row = $result->fetch_assoc()) {
			  // Determine the YEAR when this rappel took place
			  $date_array = explode('/',$row['date']);
			  $year = $date_array[2];
			  
			  // Tally up the rappels by category
			  if($year == $_SESSION['current_view']['year']) {
				  $this->raps_this_year_total++;
				  $this->raps_all_time_total++;
				  
				  switch ($row['operation_type']) {
					  case "operational":
						  $this->raps_this_year_operational++;
						  $this->raps_all_time_operational++;
						  $this->raps_this_year_live++;
						  $this->raps_all_time_live++;
						  break;
					  case "proficiency_live":
					  case "certification_new_aircraft":
					  case "certification_new_hrap":
						  $this->raps_this_year_proficiency++;
						  $this->raps_all_time_proficiency++;
						  $this->raps_this_year_proficiency_live++;
						  $this->raps_all_time_proficiency_live++;
						  $this->raps_this_year_live++;
						  $this->raps_all_time_live++;
						  break;
					  case "proficiency_tower":
						  $this->raps_this_year_proficiency++;
						  $this->raps_all_time_proficiency++;
						  break;
				  } // End: switch
			  }
			  else {// This rappel did NOT take place during the specified year
				  $this->raps_all_time_total++;
				  
				  switch ($row['operation_type']) {
					  case "operational":
						  $this->raps_all_time_operational++;
						  $this->raps_all_time_live++;
						  break;
					  case "proficiency_live":
					  case "certification_new_aircraft":
					  case "certification_new_hrap":
						  $this->raps_all_time_proficiency++;
						  $this->raps_all_time_proficiency_live++;
						  $this->raps_all_time_live++;
						  break;
					  case "proficiency_tower":
						  $this->raps_all_time_proficiency++;
						  break;
				  } // End: switch
			  } // End: while
		  } // End: if($result)
			
		
		// Tally up SPOTS
		if($this->spotter != 0) {
		  $query = "select distinct date, operation_type, operation_id "
				  ."from view_rappels where spotter_id = ".$this->id
				  ." order by date";
		  $result = mydb::cxn()->query($query);
		  
		  if($result) {
			while($row = $result->fetch_assoc()) {
				// Determine the YEAR when this operation took place
				$date_array = explode('/',$row['date']);
				$year = $date_array[2];
				
				// Tally up the rappels by category
				if($year == $_SESSION['current_view']['year']) {
					$this->spots_this_year_total++;
					$this->spots_all_time_total++;
					
					switch ($row['operation_type']) {
						case "operational":
							$this->spots_this_year_operational++;
							$this->spots_all_time_operational++;
							$this->spots_this_year_live++;
							$this->spots_all_time_live++;
							break;
						case "proficiency_live":
						case "certification_new_aircraft":
						case "certification_new_hrap":
							$this->spots_this_year_proficiency++;
							$this->spots_all_time_proficiency++;
							$this->spots_this_year_proficiency_live++;
							$this->spots_all_time_proficiency_live++;
							$this->spots_this_year_live++;
							$this->spots_all_time_live++;
							break;
						case "proficiency_tower":
							$this->spots_this_year_proficiency++;
							$this->spots_all_time_proficiency++;
							break;
					} // End: switch
				}// End: if($year == $_SESSION['current_view']['year'])
				else {// This rappel did NOT take place during the specified year
					$this->spots_all_time_total++;
					
					switch ($row['operation_type']) {
						case "operational":
							$this->spots_all_time_operational++;
							$this->spots_all_time_live++;
							break;
						case "proficiency_live":
						case "certification_new_aircraft":
						case "certification_new_hrap":
							$this->spots_all_time_proficiency++;
							$this->spots_all_time_proficiency_live++;
							$this->spots_all_time_live++;
							break;
						case "proficiency_tower":
							$this->spots_all_time_proficiency++;
							break;
					} // End: switch
				}//End: else
			} // End: while
		  } // End: if($result)
		} // End: if($this->spotter != 0)
		
		
		
			// Determine favorite rope (most occurrances, a tie is won by the first rope tallied - a new rope must appear MORE to become the favorite.)
			if(array_key_exists($row['rope_num'],$ropes)) {
				$ropes[$row['rope_num']]++;
				if($ropes[$row['rope_num']] > $this->favorite_rope['uses']) {
					$this->favorite_rope['num'] = $row['rope_num'];
					$this->favorite_rope['uses']= $ropes[$row['rope_num']];
				}
			}
			else $ropes[$row['rope_num']] = 1;
			
			// Determine favorite genie (most occurrances, a tie is won by the first genie tallied - a new genie must appear MORE to become the favorite.)
			if(array_key_exists($row['genie_num'],$genies)) {
				$genies[$row['genie_num']]++;
				if($genies[$row['genie_num']] > $this->favorite_genie['uses']) {
					$this->favorite_genie['num'] = $row['genie_num'];
					$this->favorite_genie['uses']= $genies[$row['genie_num']];
				}
			}
			else $genies[$row['genie_num']] = 1;
			
		} // End: WHILE
		
		
		// Grab some BLING based on total number of LIVE rappels logged
		if($this->raps_all_time_live >= $_SESSION['raps_for_merit_5']) {
			$this->bling_filename = $_SESSION['merit_5_image'];
			$this->name = $this->firstname." ".$this->lastname." <img src=\"".$this->bling_filename."\" style=\"border:none\">";
		}
		elseif($this->raps_all_time_live >= $_SESSION['raps_for_merit_4']) {
			$this->bling_filename = $_SESSION['merit_4_image'];
			$this->name = $this->firstname." ".$this->lastname." <img src=\"".$this->bling_filename."\" style=\"border:none\">";
		}
		elseif($this->raps_all_time_live >= $_SESSION['raps_for_merit_3']) {
			$this->bling_filename = $_SESSION['merit_3_image'];
			$this->name = $this->firstname." ".$this->lastname." <img src=\"".$this->bling_filename."\" style=\"border:none\">";
		}
		elseif($this->raps_all_time_live >= $_SESSION['raps_for_merit_2']) {
			$this->bling_filename = $_SESSION['merit_2_image'];
			$this->name = $this->firstname." ".$this->lastname." <img src=\"".$this->bling_filename."\" style=\"border:none\">";
		}
		elseif($this->raps_all_time_live >= $_SESSION['raps_for_merit_1']) {
			$this->bling_filename = $_SESSION['merit_1_image'];
			$this->name = $this->firstname." ".$this->lastname." <img src=\"".$this->bling_filename."\" style=\"border:none\">";
		}
		else {
			$this->bling_filename = "";
			$this->name = $this->firstname." ".$this->lastname;
		}
		
			
	} // End: function load()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: create() ************************************************************************/
/*******************************************************************************************************************************/

	function create($firstname, $lastname, $gender, $birthdate, $iqcs_num, $year_of_1st_rappel, $count_offset_proficiency, $count_offset_operational, $spotter, $spot_count_offset_proficiency, $spot_count_offset_operational) {
		//No CREW info is dealt with here.  HRAPS are bound to crews in the crew_class

		try {
			$this->check_data('firstname',$firstname);
			$this->check_data('lastname',$lastname);
			$this->check_data('gender',$gender);
			$this->check_data('birthdate',$birthdate);
			$this->check_data('iqcs_num',$iqcs_num);
			$this->check_data('year_of_1st_rappel',$year_of_1st_rappel);
			$this->check_data('rap_count_offset',$count_offset_proficiency);
			$this->check_data('rap_count_offset',$count_offset_operational);
			$this->check_data('spotter',$spotter);
			$this->check_data('spot_count_offset',$spot_count_offset_proficiency);
			$this->check_data('spot_count_offset',$spot_count_offset_operational);
			
			if($this->already_exists($firstname,$lastname,$iqcs_num)) throw new Exception('The rappeller you described is already in the system! (Same first name, last name & iqcs number)');
			
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
			return 0;
		}	
		
		// If any of the data FAILED the checks performed above, this function will exit
		// and everything below this line will be ignored
		$this->firstname = mydb::cxn()->real_escape_string($firstname);
		$this->lastname = mydb::cxn()->real_escape_string($lastname);
		$this->gender = ucwords(strtolower($gender));
		$this->birthdate = $birthdate;
		$this->iqcs_num = str_pad($iqcs_num,11,"0",STR_PAD_LEFT);
		$this->year_of_1st_rappel = $year_of_1st_rappel;
		$this->count_offset_proficiency = $count_offset_proficiency;
		$this->count_offset_operational = $count_offset_operational;
		$this->spotter = ucwords(strtolower($spotter));
		$this->spot_count_offset_proficiency = $spot_count_offset_proficiency;
		$this->spot_count_offset_operational = $spot_count_offset_operational;
		
		$bd = explode("/",$this->birthdate);
		$bd_mysql = $bd[2]."-".$bd[0]."-".$bd[1];
		$query = "INSERT INTO hraps (firstname, lastname, gender, birthdate, iqcs_num, year_of_1st_rappel, count_offset_proficiency, count_offset_operational, spotter, spot_count_offset_proficiency, spot_count_offset_operational) "
				."values('".$this->firstname."','".$this->lastname."','".$this->gender."','".$bd_mysql."','".$this->iqcs_num."','".$this->year_of_1st_rappel."',".$this->count_offset_proficiency.",".$this->count_offset_operational.",".$this->spotter.",".$this->spot_count_offset_proficiency.",".$this->spot_count_offset_operational.")";
		
		$result = mydb::cxn()->query($query);
		$this->id = mydb::cxn()->insert_id;
		
		//Now process the headshot photo (if one was uploaded)
		if(basename( $_FILES['uploadedfile']['name']) != '') {
			
			try {
				$target_path = "images/hrap_headshots/".$this->id.".jpg";
				$this->process_headshot_file($target_path);
			} catch (Exception $e) {
				//If an error occurred with the image, remove the rappeller's partial entry from the db
				$query = "DELETE FROM hraps WHERE id = ".$this->id;
				$result = mydb::cxn()->query($query);
				
				//And delete the uploaded file, if it exists
				if(file_exists($target_path)) unlink($target_path);
				
				//Re-throw the exception that was originally thrown in $this->process_headshot_file()
				throw new Exception($e->getMessage());
			} // End: catch
			
		}// End: if(basename( $_FILES['uploadedfile']['name']) != '')
		
	} // End: function create

/*******************************************************************************************************************************/
/*********************************** FUNCTION: update() ************************************************************************/
/*******************************************************************************************************************************/
	function update($firstname, $lastname, $gender, $birthdate, $iqcs_num, $year_of_1st_rappel, $count_offset_proficiency, $count_offset_operational, $spotter, $spot_count_offset_proficiency, $spot_count_offset_operational, $remove_headshot) {
		//No CREW info is dealt with here.  HRAPS are bound to crews in the crew_class

		try {
			$this->check_data('firstname',$firstname);
			$this->check_data('lastname',$lastname);
			$this->check_data('gender',$gender);
			$this->check_data('birthdate',$birthdate);
			$this->check_data('iqcs_num',$iqcs_num);
			$this->check_data('year_of_1st_rappel',$year_of_1st_rappel);
			$this->check_data('rap_count_offset',$count_offset_proficiency);
			$this->check_data('rap_count_offset',$count_offset_operational);
			$this->check_data('spotter',$spotter);
			$this->check_data('spot_count_offset',$spot_count_offset_proficiency);
			$this->check_data('spot_count_offset',$spot_count_offset_operational);
			
		} catch (Exception $e) {
			throw new Exception($e->getMessage()."spot_offset: ".$spot_count_offset_proficiency); // ReThrow any exceptions that were generated by 'check_data()'
			return 0;
		}	
		
		// If any of the data FAILED the checks performed above, this function will exit
		// and everything below this line will be ignored
		$this->firstname = mydb::cxn()->real_escape_string($firstname);
		$this->lastname = mydb::cxn()->real_escape_string($lastname);
		$this->gender = ucwords(strtolower($gender));
		$this->birthdate = $birthdate;
		$this->iqcs_num = str_pad($iqcs_num,11,"0",STR_PAD_LEFT);
		if($year_of_1st_rappel == "") $this->year_of_1st_rappel = "NULL";
		else $this->year_of_1st_rappel = $year_of_1st_rappel;
		$this->count_offset_proficiency = $count_offset_proficiency;
		$this->count_offset_operational = $count_offset_operational;
		$this->spotter = ucwords(strtolower($spotter));
		$this->spot_count_offset_proficiency = $spot_count_offset_proficiency;
		$this->spot_count_offset_operational = $spot_count_offset_operational;
		
		$bd = explode("/",$this->birthdate);
		$bd_mysql = $bd[2]."-".$bd[0]."-".$bd[1];
		$query = "UPDATE hraps "
				."SET firstname = '".$this->firstname."',"
				."lastname = '".$this->lastname."',"
				."gender = '".$this->gender."',"
				."birthdate = '".$bd_mysql."',"
				."iqcs_num = '".$this->iqcs_num."',"
				."year_of_1st_rappel = ".$this->year_of_1st_rappel.","
				."count_offset_proficiency = ".$this->count_offset_proficiency.","
				."count_offset_operational = ".$this->count_offset_operational.","
				."spotter = ".$this->spotter.","
				."spot_count_offset_proficiency = ".$this->spot_count_offset_proficiency.","
				."spot_count_offset_operational = ".$this->spot_count_offset_operational." "
				."WHERE id = ".$this->id;
		
		$result = mydb::cxn()->query($query);
		if(mydb::cxn()->error) throw new Exception('Error Updating HRAP: '.mydb::cxn()->error);
		
		//Now process the headshot photo (if one was uploaded) - existing image will be overwritten
		$target_path = "images/hrap_headshots/".$this->id.".jpg";
		
		if($remove_headshot) {
			//Delete the headshot image from the server, if it exists
			if(file_exists($target_path)) unlink($target_path);
			
			//Define this HRAP's headshot image as the 'missing' default
			$query = "UPDATE hraps SET headshot_filename = '".$_SESSION['missing_headshot_image']."' WHERE id = ".$this->id;
			$result = mydb::cxn()->query($query);
		}
		elseif(basename( $_FILES['uploadedfile']['name']) != '') {
			// A new image file was specified...
			try {
				$this->process_headshot_file($target_path);
			} catch (Exception $e) {
				//Re-throw the exception that was originally thrown in $this->process_headshot_file()
				throw new Exception($e->getMessage());
				
			} // End: catch
			
		}// End: if(basename( $_FILES['uploadedfile']['name']) != '')
		
	
	} // End: function update()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: process_headshot_file() *********************************************************/
/*******************************************************************************************************************************/
	function process_headshot_file($target_path) {
		
		try {
			if(file_exists($target_path)) rename($target_path,$_SESSION['temp_image_filename']); // If an image file already exists with the desired filename, create a temp backup
			check_uploaded_file($_FILES['uploadedfile']['tmp_name']); // $status['success'] (0,1) - $status['desc'] (text)
			if(!move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) throw new Exception('Unable to accept file, try again later.');
			if(!resize($target_path, $target_path, "hrap_headshot")) throw new Exception('Unable to resize file');
			
			$query = "UPDATE hraps SET headshot_filename = '".$target_path."' WHERE id = ".$this->id;
			$result = mydb::cxn()->query($query);
			
			if(file_exists($_SESSION['temp_image_filename'])) unlink($_SESSION['temp_image_filename']); // Delete the temp image if everything was successful

		} catch (Exception $e) {
			if(file_exists($_SESSION['temp_image_filename'])) rename($_SESSION['temp_image_filename'],$target_path); //Restore the backup image if something went wrong
			
			//Re-throw the exception
			throw new Exception($e->getMessage());
		
		} // End: catch() 
	} // End: process_headshot_file()
	
/*******************************************************************************************************************************/
/*********************************** FUNCTION: add_to_roster() *****************************************************************/
/*******************************************************************************************************************************/

	function add_to_roster($crew,$year) {
		// First check to see if this crewmember is already on the requested roster
		$query = "SELECT id FROM rosters WHERE hrap_id = ".$this->id." AND crew_id = ".$crew." AND year = '".$year."'";
		$result = mydb::cxn()->query($query);
		
		if(mydb::cxn()->affected_rows < 1) {
			//This crewmember is NOT already on the requested roster - go ahead and add this entry to the db
			$query = "INSERT INTO rosters (hrap_id, crew_id, year) values(".$this->id.",".$crew.",'".$year."')";
			$result = mydb::cxn()->query($query);
		}
		else {
			//Do nothing.  This entry already exists.
		}
	}

/*******************************************************************************************************************************/
/*********************************** FUNCTION: remove_from_roster() ************************************************************/
/*******************************************************************************************************************************/

	function remove_from_roster($crew,$year) {
			$query = "DELETE FROM rosters WHERE hrap_id = ".$this->id." AND crew_id = ".$crew." AND year = '".$year."'";
			$result = mydb::cxn()->query($query);
	}
		
/*******************************************************************************************************************************/
/*********************************** FUNCTION: check_data() ********************************************************************/
/*******************************************************************************************************************************/

	function check_data($var, $value) {
		switch($var) {
		case('id'):
			if(!$this->var_is_int($value)) {
				throw new Exception('HRAP ID must be an integer');
			}
			break;
			
		case('firstname'):
			if(!preg_match('/^[\'-a-z\d_]{1,25}$/i', $value)) {
				throw new Exception('Firstname must be 1 - 25 letters');
				return 0;
			}
			break;
			
		case('lastname'):
			if(!preg_match('/^[\'-a-z\d_]{1,25}$/i', $value)) {
				throw new Exception('Lastname must be 1 - 25 letters');
				return 0;
			}
			break;
		
		case('gender'):
			if((strtolower($value) != 'male') && (strtolower($value) != 'female')) {
				throw new Exception('Gender must be either male or female');
				return 0;
			}
			break;
		
		case('birthdate'):
			$dates = explode("/",$value);
			if(!checkdate((int)$dates[0], (int)$dates[1], (int)$dates[2])) {
				throw new Exception('The birthdate entered is not a valid date');
				return 0;
			}
			break;
		
		case('iqcs_num'):
			if(!(preg_match('/^[0-9]{3,11}$/', $value) || ($value == ""))) {
				throw new Exception('Double check your IQCS number, brah.');
				return 0;
			}
			break;
			
		case('year_of_1st_rappel'):
			if(!(preg_match('/^(19|20)[0-9]{2}$/', $value) || ($value == ""))) {
				throw new Exception('Year-of-1st-Rappel must be a 4-digit year (i.e. 2005)');
				return 0;
			}
			break;
		
		case('rap_count_offset');
			if(!$this->var_is_int($value) || ($value < 0)) {
				throw new Exception('The \'Number of rappels NOT in the RapRec system\' must be a number greater than zero');
				return 0;
			}
			break;
		
		case('spot_count_offset');
			if(!$this->var_is_int($value) || ($value < 0)) {
				throw new Exception('The \'Number of spots NOT in the RapRec system\' must be a number greater than zero');
				return 0;
			}
			break;
			
		case('spotter'):
			if($value != 0 && $value != 1 && $value != 2) {
				throw new Exception('Spotter status must be 0 (Not a spotter), 1 (Spotter qualified), or 2 (Trainee spotter)');
				return 0;
			}
			break;
		} // End: switch($var)
		
		//If execution reaches this point, data has been check and no exceptions have been thrown. Return with success state.
		return 1;
	} // End: private function check_data()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: already_exists() ***************************************************************/
/*******************************************************************************************************************************/

	function already_exists($firstname, $lastname, $iqcs_num) {
		//$bd = explode('/',$birthdate); // $birthdate is in the format: mm/dd/yyyy
		//$sql_bday = $bd[2]."-".$bd[0]."-".$bd[1]; // $sql_bday format: yyyy-mm-dd
		$query = "SELECT id FROM hraps WHERE LOWER(firstname) = '".mydb::cxn()->real_escape_string(strtolower($firstname))."' "
				."AND LOWER(lastname) = '".mydb::cxn()->real_escape_string(strtolower($lastname))."' "
				."AND iqcs_num = '".str_pad($iqcs_num,11,"0",STR_PAD_LEFT)."'";
		
		$result = mydb::cxn()->query($query);
		
		if(mydb::cxn()->affected_rows > 0) return true; // The specified person already exists in the database
		else return false;
	
	} // End: function already_exists()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: get_crew_by_year() ***************************************************************/
/*******************************************************************************************************************************/
	function get_crew_by_year($year) {
		$query = "SELECT crews.id FROM crews INNER JOIN rosters ON crews.id = rosters.crew_id "
				."WHERE rosters.hrap_id = ".$this->id." && rosters.year = '".$year."'";
		
		$result = mydb::cxn()->query($query);
		
		if($row = $result->fetch_assoc()) return $row['id'];
		else return false;	
	} // End: function get_crew_by_year()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: get_rap_history() ***************************************************************/
/*******************************************************************************************************************************/
	function get_rap_history($field = "hrap",$year = 0) {
		// This function retrieves a list of every rappel that this HRAP has performed and returns them as an array
		// to be dealt with using a "foreach" block.
		// Data can be optionally restricted to a single year by specifying a 4-digit year in the function call,
		// otherwise the rappel history will include all dates.
		//
		// $field should be either "hrap" or "spotter" depending on whether you want to retrieve this person's rappels or spots
		
		if($year == 0) $year_clause = "";
		else $year_clause = " AND YEAR(operations.date) = '".$year."' ";
		
		if($field == "spotter") {
			$field = "spotter_id";
			
			$query = "	SELECT	operations.id as operation_id,
								operations.type as operation_type,
								date_format(operations.date,'%c/%d/%Y') as date,
								aircraft_types.fullname as aircraft_fullname
						FROM operations JOIN aircraft_types ON aircraft_types.id = operations.aircraft_type_config
						WHERE operations.spotter_id = ".$this->id.$year_clause." ORDER BY date";
			$result = mydb::cxn()->query($query);
			
		}
		else {
			$field = "hrap_id";
			
			$query = "SELECT * FROM view_rappels WHERE hrap_id = ".$this->id.$year_clause." ORDER BY date";
			$result = mydb::cxn()->query($query);
		}
		
		$rappels = array();
		$i = 0;
		while($row = $result->fetch_assoc()) {
			foreach($row as $key=>$val) {
				$rappels[$i][$key] = $val;
			}
			$i++;
		}
		return $rappels;
	}

/*******************************************************************************************************************************/
/*********************************** FUNCTION: var_is_int() ********************************************************************/
/*******************************************************************************************************************************/
	private function var_is_int($value) {
		// Returns TRUE if $value is an integer.
		// Returns FALSE otherwise.
		// This function will take any data type as input.
    	return ((string) $value) === ((string)(int) $value);
	} // End: function var_is_int()


/*******************************************************************************************************************************/
/*********************************** FUNCTION: exists() ********************************************************************/
/*******************************************************************************************************************************/
	static function exists($hrap_id = false) {
		// Returns TRUE if $hrap_id is found in the 'hraps' database table
		// Returns FALSE otherwise.
		// This function will take any data type as input.
		if(!$hrap_id) return false;
		
    	$query = "SELECT id FROM hraps WHERE id = ".mydb::cxn()->real_escape_string($hrap_id);
		$result = mydb::cxn()->query($query);
		
		
		if(mydb::cxn()->affected_rows > 0) return TRUE;
		else return FALSE;
	} // End: function exists()


/*******************************************************************************************************************************/
/*******************************************************************************************************************************/
/*******************************************************************************************************************************/
} // End: class hrap()
?>
