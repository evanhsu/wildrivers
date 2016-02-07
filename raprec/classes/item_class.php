<?php
include_once("classes/mydb_class.php");
include_once("classes/crew_class.php");

class item {

	var $id;
	var $serial_num;
	var $item_type;
	var $color;
	var $size;
	var $description;
	var $condition;
	var $note;
	var $crew_affiliation_id;
	var $crew_affiliation_name;
	var $status;			// valid values listed in $valid_status

	var $valid_status = array("in_service", "suspended", "missing", "retired");
	
	// A list of the member variables in this class that can be requested via the get() function
	var $gettable_members = array('id','serial_num','item_type','color','size','description','condition','note','crew_affiliation_id','crew_affiliation_name','status');
	// A list of the member variables that can be changed via the set() function
	var $settable_members = array('serial_num','item_type','color','size','description','condition','note','crew_affiliation_id','status');
	
/********** Constructor **************************************/
	function __construct() {
		$this->status = "in_service";	// Default state: item is still in service (NOT retired)
	}

/*******************************************************************************************************************************/
/*********************************** FUNCTION: set() ***************************************************************************/
/*******************************************************************************************************************************/
	function set($var, $value) {
		$value = mydb::cxn()->real_escape_string($value);
		
		switch($var) {
		case 'serial_num':
			// The Serial Number of an item is not restricted to any subset of characters. Any string is allowed, provided that it fits into the allowed VARCHAR space in the database
			if($this->is_duplicate($value)) throw new Exception('That serial number ('.strtoupper($value).') is already being used for an item of type \''.$this->item_type.'\'');
			else $this->serial_num = $value;
			break;
		
		case 'item_type':
			$this->item_type = $value;
			break;
		
		case 'color':
			$this->color = $value;
			break;
		
		case 'size':
			$this->size = $value;
			break;
		
		case 'description':
			$this->description = $value;
			break;
		
		case 'condition':
			$this->condition = $value;
			break;
		
		case 'note':
			$this->note = $value;
			break;
			
		case 'crew_affiliation_id':
			if(!is_numeric($value) || !(intval($value) == floatval($value))) throw new Exception('A non-integer Crew ID was passed to item->set().'); // Make sure it's an integer
			else {
				if(!crew::exists($value)) throw new Exception('The Item cannot be affiliated with Crew #'.$value.' because that Crew does not exist.');
				else {
					$this->crew_affiliation_id = $value;
					$this->crew_affiliation_name = crew::get_name($value);
				}
			}
			break;
		
		case 'status':
			if(in_array(strtolower($value),$this->valid_status)) $this->status = strtolower($value);
			else throw new Exception('The item->set() function attempted to set an invalid \'status\' ('.$value.')');
			break;
			
		default:
			throw new Exception('The item->set() function attempted to modify an invalid variable ('.$var.')');
			break;
		} // End: switch($var)
	
	} // End: function set()


/*******************************************************************************************************************************/
/*********************************** FUNCTION: get() ***************************************************************************/
/*******************************************************************************************************************************/
	function get($var) {
		if(in_array($var,$this->gettable_members)) return $this->$var;
		else throw new Exception('An invalid property was sent to item->get() ('.$var.').');
	} // End: function get()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: load() ***************************************************************************/
/*******************************************************************************************************************************/
	function load($item_id) {
		// This function will populate all member variables of the current object with data from the database entry that
		// corresponds to the item with the requested $item_id

		if(!is_numeric($item_id) || !(intval($item_id) == floatval($item_id))) {
			//Make sure the $genie_id is an integer
			throw new Exception('A non-integer Item ID number was requested in item->load().');
			return 0;
		}
		$item_id = mydb::cxn()->real_escape_string($item_id);
		
		$query = "SELECT	items.id,
							items.serial_num,
							items.item_type,
							items.color,
							items.size,
							items.description,
							items.condition,
							items.note,
							items.crew_affiliation_id,
							crews.name as crew_affiliation_name,
							items.status "
				."FROM items INNER JOIN crews ON crews.id = items.crew_affiliation_id "
				."WHERE items.id = '".$item_id."'";
		$result = mydb::cxn()->query($query);
		
		$ar = mydb::cxn()->affected_rows;
		if($ar == 1) {
			$row = $result->fetch_assoc();
			foreach($this->gettable_members as $var) $this->$var = $row[$var];
		}
		elseif($ar == 0)	throw new Exception('The requested Item does not exist! Query: '.$query);
		else 				throw new Exception('An ambiguous Item ID was passed to item->load().');
	} //End: function load()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: save() **************************************************************************/
/*******************************************************************************************************************************/
	function save() {
		// This function will write the current ITEM object into the database as a new entry in the 'items' table
		// Incomplete entries will not be allowed - a catchable exception will be thrown
		
		if($this->crew_affiliation_id != "") {
			// Check to see whether we are inserting a NEW ENTRY or updating an EXISTING ENTRY
			if(isset($this->id)) {
				if($this->item_id_exists($this->id)) $action = "update";
				else {
					// The Item ID that was loaded no longer exists (another user has deleted the entry since this user loaded it)
					$action = "insert";
					$this->id = NULL;
				}
			}
			else $action = "insert";
			

			switch ($action) {
			case "insert":
				// Check for duplicate serial_num immediately before writing to database
				if($this->is_duplicate($this->serial_num,$this->item_type)) {
					throw new Exception('There is already an item with that serial number ('.$this->serial_num.'). You cannot create two items of the same type with the same serial number.');
				}
				else {
					$query = "INSERT INTO items (`serial_num`,`item_type`,`color`,`size`,`description`,`condition`,`note`,`crew_affiliation_id`,`status`) "
							."values('".mydb::cxn()->real_escape_string(strtolower($this->serial_num))."','"
									.mydb::cxn()->real_escape_string($this->item_type)."','"
									.mydb::cxn()->real_escape_string($this->color)."','"
									.mydb::cxn()->real_escape_string($this->size)."','"
									.mydb::cxn()->real_escape_string($this->description)."','"
									.mydb::cxn()->real_escape_string($this->condition)."','"
									.mydb::cxn()->real_escape_string($this->note)."',"
									.mydb::cxn()->real_escape_string($this->crew_affiliation_id).",'"
									.mydb::cxn()->real_escape_string($this->status)."'";
					$result = mydb::cxn()->query($query);
				}
				break;
			
			case "update":
				$query = "UPDATE items	SET `serial_num` = '".mydb::cxn()->real_escape_string(strtolower($this->serial_num))."', "
								."`item_type` = '".mydb::cxn()->real_escape_string($this->item_type)."', "
								."`color` = '".mydb::cxn()->real_escape_string($this->color)."', "
								."`size` = '".mydb::cxn()->real_escape_string($this->size)."', "
								."`description` = '".mydb::cxn()->real_escape_string($this->description)."', "
								."`condition` = '".mydb::cxn()->real_escape_string($this->condition)."', "
								."`note` = '".mydb::cxn()->real_escape_string($this->note)."', "
								."`crew_affiliation_id` = ".mydb::cxn()->real_escape_string($this->crew_affiliation_id).", "
								."`status` = '".mydb::cxn()->real_escape_string($this->status)."' "
						."WHERE `id` = ".$this->id;
				$result = mydb::cxn()->query($query);
				break;
			} // End: switch()
			
			if(mydb::cxn()->error != "") throw new Exception('Error saving Item info: '.mydb::cxn()->error." :: ".$query);
		}
		else {
			$missing_vars = "";
			if(!isset($this->crew_affiliation)) $missing_vars .= "Crew Affiliation";
			
			throw new Exception('Incomplete Item entries cannot be saved. The following required information is missing: '.$missing_vars);
		}
	
	} // End: function save()
	
	
  function is_duplicate($new_serial_num,$item_type='') {
	  // This function checks to see if there is another item (in addition to $this item) of the same item_type that shares the $serial_num specified by $new_serial_num
	  // If $this Item is new (it has never been stored into the database and therefore has no ID yet) then this function will simply check the dB for a matching serial_num and item_type.
	  // If $this Item has a defined ID, then this function will search for a matching serial_num and item_type, EXCLUDING the Item with $this->id (exclude the current Item from the search)
	  // Return TRUE if another item exists
	  // Return FALSE if the $serial_num / $item_type pair is unique
	  if($item_type == '' && isset($this->item_type)) $item_type = $this->item_type;
	  $query = "SELECT id FROM items WHERE (LOWER(serial_num) = '".strtolower(mydb::cxn()->real_escape_string($new_serial_num))
									 ."' OR LOWER(serial_num) = '".strtolower(mydb::cxn()->real_escape_string(htmlspecialchars($new_serial_num)))."')"
									 ." AND LOWER(item_type) = '".strtolower(mydb::cxn()->real_escape_string($item_type))."'";
	  if(isset($this) && isset($this->id) && ($this->id != '')) $query .= " AND id <> ".$this->id;
	  
	  $result = mydb::cxn()->query($query);
	  return $result->num_rows > 0 ? TRUE : FALSE;
  }
  
  function item_id_exists($item_id='') {
	  // This function checks the database to see if an item exists with the ID specified by $item_id
	  // Return TRUE if an item exists
	  // Return FALSE otherwise
	  if($item_id != '') {
		  $query = "SELECT * FROM items WHERE id = '".mydb::cxn()->real_escape_string($item_id)."'";
		  $result = mydb::cxn()->query($query);
	  
		  return $result->num_rows > 0 ? TRUE : FALSE;
	  }
	  else return FALSE;
  }

} // END class item
?>
