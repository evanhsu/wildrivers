<?php
require_once(__DIR__ . "/../classes/mydb_class.php");
require_once(__DIR__ . "/../classes/student_class.php");
require_once(__DIR__ . "/../classes/scheduled_course_class.php");

class course_enrollment {
	var $id;					// dB id of this 'enrollment' entry
	var $student_id;			// The ID of a crewmember - replace this with 'crewmember' object ***********************************
	var $student;				// A 'student' object
	var $scheduled_course;		// A 'scheduled_course' object
	var $status;				// Nominated; Cancelled; Enrolled; Passed; Failed;
	var $cost_tuition;
	var $cost_wages;
	var $cost_travel;
	var $cost_misc;
	var $prework_received;		// Yes;No;N/A
	var $certificate_received;	// Yes;No;N/A
	var $payment_method;		// VARCHAR(50), e.g. Dan's VISA
	var $charge_code;			// e.g. 'WFPR10'
	var $override;				// e.g. '0610'
	var $travel_paid;			// Bool
	
	function course_enrollment() {
		// CONSTRUCTOR
		//$this->student = new person;
		//OR make 'student' class extends person - add an 'enroll()' function
		$this->scheduled_course = new scheduled_course;
	}
	
	
	function load($id) {
		if(!$this->id_exists($id)) throw new Exception('The specified enrollment does not exist (id: '.$id.')');
		
		$query = "SELECT student_id, scheduled_course_id, status, prework_received, certificate_received, cost_tuition, cost_wages, cost_travel, "
				."cost_misc, payment_method, charge_code, override, travel_paid "
				."FROM enrollment "
				."WHERE id = ".$id;
		$result = mydb::cxn()->query($query);
		if(mydb::cxn()->error != '') throw new Exception('A database error occurred while retrieving the specified enrollment object (id: '.$id.')');
		$row = $result->fetch_assoc();
		
		$this->id = $id;
		$this->student = $row['student_id'];
		//$this->student->load($row['student_id']);
		$this->scheduled_course->load($row['scheduled_course_id']);
		$this->status = $row['status'];
		$this->cost_tuition = $row['cost_tuition'];
		$this->cost_wages = $row['cost_wages'];
		$this->cost_travel = $row['cost_travel'];
		$this->cost_misc = $row['cost_misc'];
		$this->prework_received = $row['prework_received'];
		$this->cerificate_received = $row['certificate_received'];
		$this->payment_method = $row['payment_method'];
		$this->charge_code = $row['charge_code'];
		$this->override = $row['override'];
		$this->travel_paid = $row['travel_paid'];
	}
	
	function set($key, $val) {
		switch($key) {
			case 'student':
			if(crewmember::id_exists($val)) $this->student = new person($val);
			else throw new exception('The specified student does not exist (student ID '.$val.')');
			break;
			
			case 'student_id':
			if(crewmember::id_exists($val)) $this->student_id = $val;
			else throw new exception('The specified student does not exist (student ID '.$val.')');
			break;
			
			case 'scheduled_course':
				$this->scheduled_course->load($val);
			break;
			
			case 'status':
			break;
			
			case 'cost_tuition':
			break;
			
			case 'cost_wages':
			break;
			
			case 'cost_travel':
			break;
			
			case 'cost_misc':
			break;
			
			case 'prework_received':
			break;
			
			case 'cerificate_received':
			break;
			
			case 'payment_method':
			break;
			
			case 'charge_code':
			break;
			
			case 'override':
			break;
			
			case 'travel_paid':
			break;
		} // End switch()
	} // END function set()
	
	
	function get($var) {
		return $this->$var;
	} // END function get()
	
	
	function save() {
		if($this->id_exists($this->id)) {
			// UPDATE an existing database entry
			$query = "UPDATE enrollment SET "
					//."student_id = ".$this->student->get('id').", "
					."student_id = ".$this->student.", "
					."scheduled_course_id = ".$this->scheduled_course->get('id').", "
					."status = '".$this->status."', "
					."prework_received = '".$this->prework_received."', "
					."certificate_received = ".$this->certificate_received.", "
					."cost_tuition = ".$this->cost_tuition.", "
					."cost_wages = ".$this->cost_wages.", "
					."cost_travel = ".$this->cost_travel.", "
					."cost_misc = ".$this->cost_misc.", "
					."payment_method = '".$this->payment_method."', "
					."charge_code = '".$this->charge_code."', "
					."override = '".$this->override."', "
					."travel_paid = ".$this->employee_paid.") "
					."WHERE id = ".$this->id;
		}
		else {
			// INSERT a new database entry
			$query = "INSERT INTO enrollment ("
					."student_id, "
					."scheduled_course_id, "
					."status, "
					."prework_received, "
					."certificate_received, "
					."cost_tuition, "
					."cost_wages, "
					."cost_travel, "
					."cost_misc, "
					."payment_method, "
					."charge_code, "
					."override, "
					."travel_paid "
					.") VALUES ("
					//.$this->student->get('id').", '"
					.$this->student.", "
					.$this->scheduled_course->get('id').", "
					.$this->status."', '"
					.$this->prework_received."', '"
					.$this->certificate_received."', "
					.$this->cost_tuition.", "
					.$this->cost_wages.", "
					.$this->cost_travel.", "
					.$this->cost_misc.", '"
					.$this->payment_method."', '"
					.$this->charge_code."', '"
					.$this->override."', "
					.$this->travel_paid.")";
		} // END else()
		
		$result = mydb::cxn()->query($query);
		if(mydb::cxn()->error != '') throw new exception('Could not update the enrollment status of '.$this->student->get('fullname').' in the class '.$this->scheduled_course->get('name'));
		else {
			$this->id = mydb::cxn()->insert_id;	// Set this objects ID to the value assigned by the database (only matters if this was a new INSERT)
			return 1;
		}
	} // END function save()
	
	public function id_exists($id = false) {
		if(is_numeric($id)) {
			$result = mydb::cxn()->query("SELECT id FROM enrollment WHERE id = ".$id);
			if(mydb::cxn()->error == '') return 1;
		}
		else return 0;
	} // END function id_exists()
	
} // END class course_enrollment
?>