<?php
include_once("classes/mydb_class.php");
include_once("classes/item_class.php");
include_once("classes/rappel_equipment_class.php");

class genie extends rappel_equipment {

/********** Constructor **************************************/
	function __construct() {
		$this->item_type = "genie";
		parent::__construct();
	}

} // End: class genie
?>
