<?php
include_once("classes/mydb_class.php");
include_once("classes/item_class.php");
include_once("classes/rappel_equipment_class.php");

class rope extends rappel_equipment {

/********** Constructor **************************************/
	function __construct() {
		$this->item_type = "rope";
		$this->use_offset = "a0,b0";	// Set the special format used by ROPE objects
		
		parent::__construct();
	}

/*************************************************************/
	function set($var,$value) {
		// This function handles a SPECIAL CASE where the use_offset is queried for a ROPE object.  This is special because a ROPE has a different use_offset
		// for each end (end 'a' and end 'b'), which are serialized into a single STRING value for database storage.
		// If this set function is called and the special case does not apply, the 'set' function in the parent class will be invoked.
		$value = strtolower(mydb::cxn()->real_escape_string($value));
		
		switch($var) {
		case 'use_offset':
			if($value == "") $this->use_offset = 'a0,b0';
			elseif(preg_match('/\ba\d{1,3},b\d{1,3}\b/',$value) != 1) throw new Exception('The USE OFFSET for a rope must include both the \'A\' end and the \'B\' end.');
			else $this->use_offset = $value;
		
		case 'use_offset_a':
			if($value == "") $this->use_offset = 'a0,b0';
			if($this->var_is_int($value) && ($value >= 0)) $this->use_offset = 'a'.$value.',b'.$this->get_use_offset('b');
			else throw new Exception('The use-offset for end \'A\' must be a number greater than or equal to zero.');
			break;
		
		case 'use_offset_b':
			if($this->var_is_int($value) && ($value >= 0)) $this->use_offset = 'a'.$this->get_use_offset('a').',b'.$value;
			else throw new Exception('The use-offset for end \'B\' must be a number greater than or equal to zero.');
			break;
			
		default:
			parent::set($var,$value);
		} // End: switch()
		
	} // End: function set()

/*************************************************************/
	function get($var) {
		switch(strtolower($var)) {
		case 'use_offset':
			return $this->get_use_offset();
			break;
		
		case 'use_offset_a':
			return $this->get_use_offset('a');
			break;
		
		case 'use_offset_b':
			return $this->get_use_offset('b');
			break;
			
		default:
			return parent::get($var);
		}// End: switch()
		
	} // End: function get()

/*************************************************************/
	private function get_use_offset($end='c') {
		// INPUT:	$end	Either 'a' or 'b', describing the end of the rope you're interested in
		// OUTPUT:			The INTEGER number of uses on the specified $end of $this rope
		//
		// This function returns the number of times this rope has been used on the specified end.
		// If no end is specified, the sum total of both ends will be returned.
		// This function is necessary because the use_offset for a rope is stored as a STRING in the following format:
		// FORMAT:	$this->use_offset = 'a123,b456'
		//			where 123 is the number of uses on End A
		//			and 456 is the number of uses on End B
		//			These integer values must have 1 to 3 digits
		
		if(($end != 'a') && ($end != 'b') && ($end != 'c')) throw new Exception('Tried to determine rope-use offset, but no rope-end was specified.');
		else {
			list($a,$b) = explode(',',$this->use_offset);
			$a = substr($a,1,strlen($a)); // Strip the character 'a' off the beginning of the string
			$b = substr($b,1,strlen($b)); // Strip the character 'b' off the beginning of the string
			
			switch($end) {
				case 'a':
				return $a;
				break;
				
				case 'b':
				return $b;
				break;
				
				case 'c':
				return $a + $b;
				break;
			}
		}
	} // End: function get_use_offset()

/*******************************************************************************************************************************/
/*********************************** FUNCTION: var_is_int() ********************************************************************/
/*******************************************************************************************************************************/
	private function var_is_int($value) {
		// Returns TRUE if $value is an integer.
		// Returns FALSE otherwise.
		// This function will take any data type as input.
    	return ((string) $value) === ((string)(int) $value);
	} // End: function var_is_int()
	
	
} // End: class rope
?>
