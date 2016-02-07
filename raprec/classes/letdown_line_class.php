<?php
include_once("classes/mydb_class.php");
include_once("classes/item_class.php");
include_once("classes/rappel_equipment_class.php");

class letdown_line extends rappel_equipment {

/********** Constructor **************************************/
	function __construct() {
		$this->item_type = "letdown_line";
		parent::__construct();
	}

} // End: class letdown_line
?>
