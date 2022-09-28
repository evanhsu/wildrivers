<?php
session_start();
require_once(__DIR__ . "/../classes/mydb_class.php");

switch($_POST['form_name']) {
	case 'req_form':
		try {
			commit_requisition();
			$_SESSION['status_msg'] = 'Your information was successfully saved!';
			if($_POST['card_used'] == 'wishlist') header('Location: http://'.$_SERVER['HTTP_HOST'].'/admin/budget_helper.php?function=view_wishlist');
			else header('Location: http://'.$_SERVER['HTTP_HOST'].'/admin/budget_helper.php');
		} catch (Exception $e) {
			$_SESSION['status_msg'] = $e->getMessage();
			header('Location: '.$_SERVER['HTTP_REFERER']);
		}
		break;
		
	case 'req_delete':
		try {
			delete_requisition($_POST['id']);
			$_SESSION['status_msg'] = 'The requisition entry was deleted!';
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/admin/budget_helper.php');
		} catch (Exception $e) {
			$_SESSION['status_msg'] = $e->getMessage();
			header('Location: '.$_SERVER['HTTP_REFERER']);
		}
		break;
	
	case 'req_attachment_delete':
		try {
			delete_requisition_attachment($_POST['req_id'], $_POST['attachment_id']);
			$_SESSION['status_msg'] = 'Attachment #'.$_POST['attachment_id'].' was deleted!';
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/admin/budget_helper.php?function=view_requisition&id='.$_POST['req_id']);
		} catch (Exception $e) {
			$_SESSION['status_msg'] = $e->getMessage();
			header('Location: '.$_SERVER['HTTP_REFERER']);
		}
		break;
}


/*********************************************************************************************************/
function commit_requisition() {
	
	mydb::cxn()->autocommit(FALSE); // Make this section TRANSACTIONAL
	try {
		// Check date format
		$date = trim($_POST['date']);
		if($date == "") $date = date("m/d/Y");  //Use today's date if the date was left blank
		$dates = explode("/",$date); // The Date should be in the form: mm/dd/yyyy
		if(!checkdate((int)$dates[0], (int)$dates[1], (int)$dates[2])) {
			throw new Exception('The Date entered is not a valid date (dates must be in the form: mm/dd/yyyy)');
		}
		
		$amount = 0.0;
		if((trim($_POST['order_total']) != "")  && is_numeric($_POST['order_total'])) {
			$amount = number_format(mydb::cxn()->real_escape_string(trim($_POST['order_total'])),2,'.','');
		}
		
		if(!isset($_POST['id']) || $_POST['id'] == '' || $_POST['id'] == 'new') {
			// This is a NEW requisition entry
			// If this is a wishlist item, determine the next priority number available (give this the lowest priority)
			if($_POST['card_used'] == 'wishlist') {
				$result = mydb::cxn()->query("SELECT max(priority)+1 as nextpri FROM requisitions");
				$row = $result->fetch_assoc();
				$pri_field = ",priority";
				$pri_value = ",".$row['nextpri'];
			}

				$query = "INSERT INTO requisitions (vendor_info,description,amount,date,card_used".$pri_field.",added_by) "
				  ."VALUES (\"".mydb::cxn()->real_escape_string($_POST['vendor_info'])."\",\""
				  .mydb::cxn()->real_escape_string($_POST['description'])."\","
				  .$amount
				  .",str_to_date('".$date."','%m/%d/%Y')"
				  .",\"".mydb::cxn()->real_escape_string($_POST['card_used'])."\""
				  .$pri_value
                                  .",\"".$_POST['added_by']."\")";
			
			$result = mydb::cxn()->query($query);
			if(mydb::cxn()->error != "") throw new Exception("The requisition was not saved!<br />\n".mydb::cxn()->error);
			$requisition_id = mydb::cxn()->insert_id;
		}
		else {
			// UPDATE an EXISTING requisition entry
			// If this item is not on the wishlist, remove any existing priority
			if($_POST['card_used'] != 'wishlist') $priority = ",priority = NULL";
			//else $priority = ",priority = ".mydb::cxn()->real_escape_string($_POST['priority']);
			else $priority = ""; //Don't change the priority with this UPDATE

			  $query = "UPDATE requisitions "
					  ."SET vendor_info = \"".mydb::cxn()->real_escape_string($_POST['vendor_info'])."\""
					  .",description = \"".mydb::cxn()->real_escape_string($_POST['description'])."\""
					  .",amount = ".$amount
					  .",date = str_to_date('".$date."','%m/%d/%Y')"
					  .",card_used = \"".mydb::cxn()->real_escape_string($_POST['card_used'])."\""
                                          .",added_by = \"".$_POST['added_by']."\""
					  .$priority
					  ." WHERE requisitions.id = ".mydb::cxn()->real_escape_string($_POST['id']);
			$result = mydb::cxn()->query($query);
			if(mydb::cxn()->error != "") throw new Exception("The requisition was not saved!<br />\n".mydb::cxn()->error);
			$requisition_id = mydb::cxn()->real_escape_string($_POST['id']);
			
			// Delete existing itemized entries to make room for the new POST'ed entries
			// Make this section transactional....
			$result = mydb::cxn()->query("DELETE FROM requisitions_split WHERE requisition_id = ".$requisition_id);
		}
		
		//Ensure that at least 1 split line gets stored, even if the dollar-amount is blank
		if((trim($_POST['amount_1']) == "") || !is_numeric($_POST['amount_1']) || is_null($_POST['amount_1'])) $_POST['amount_1'] = "0.0";
		
		for($i=1;$i<=$_SESSION['split_qty'];$i++) {
			if($_POST['amount_'.$i] != '') {
				$query = "INSERT INTO requisitions_split (requisition_id, s_number, charge_code, override, amount, received, reconciled, comments) "
					."VALUES (".$requisition_id.",\""
					.mydb::cxn()->real_escape_string(strtoupper($_POST['s_number_'.$i]))."\",\""
					.mydb::cxn()->real_escape_string(strtoupper($_POST['charge_code_'.$i]))."\",\""
					.mydb::cxn()->real_escape_string($_POST['override_'.$i])."\","
					.number_format(mydb::cxn()->real_escape_string($_POST['amount_'.$i]),2,'.','').",\""
					.mydb::cxn()->real_escape_string($_POST['split_received_'.$i])."\",\""
					.mydb::cxn()->real_escape_string($_POST['split_reconciled_'.$i])."\",\""
					.mydb::cxn()->real_escape_string($_POST['split_comments_'.$i])."\")";
					
					//echo $query;
		  
				$result = mydb::cxn()->query($query);
				
				if(mydb::cxn()->error != "") {
					// If an error occurs, rollback this entire transaction
	/*				mydb::cxn()->query("DELETE FROM requisitions WHERE id = ".$requisition_id);
					mydb::cxn()->query("DELETE FROM requisitions_split WHERE requisition_id = ".$requisition_id);
	*/				throw new Exception("The requisition was not saved!<br />\n".mydb::cxn()->error);
				}
				
			}
		}
		
		for($i=1;$i<=3;$i++) {
		  if($_FILES['uploadedfile'.$i]['name'] != "") {
			  $status = check_uploaded_file($_FILES['uploadedfile'.$i]); //$status = array('success','desc');
			  if(!$status['success']) {/*Bad form data - don't add to database... $status['desc'] holds the explanation already */}
			  else {
				  $targets = format_filename($requisition_id, $i, $_FILES['uploadedfile'.$i]);
				  $target_path = $targets['base'] . $targets['filename'];
				  
				  if(!@move_uploaded_file($_FILES['uploadedfile'.$i]['tmp_name'], $target_path)) {
					  throw new Exception('The file attachment couldn\'t be saved! Please check the file format and filesize.');
				  }
				  else {
					  // File successfully uploaded, now update entry in the database
					  $query = "UPDATE requisitions SET attachment".$i." = \"".$target_path."\" WHERE id = ".$requisition_id;
					  $result = mydb::cxn()->query($query);
					  if(mydb::cxn()->error != "") throw new Exception("File attachment #".$i." could not be saved, but the requisition information was saved successfully.<br />\n".mydb::cxn()->error);
				  }
			  }
		  } //END if($_FILES['uploadedfile']['name'] != "")
		} //END for($i=1;$i<=3;$i++)
		
		$_SESSION['form_memory']['requisition'] = array();
		mydb::cxn()->commit();
		mydb::cxn()->autocommit(TRUE);
		
	} catch (Exception $e) {
		mydb::cxn()->rollback();
		mydb::cxn()->autocommit(TRUE);
		throw new Exception($e->getMessage());
	}
	
	return;
}
/*********************************************************************************************************/
function delete_requisition($id) {
	$attachment = false;
	
	$query = "SELECT attachment from requisitions WHERE id = ".$id;
	$result = mydb::cxn()->query($query);
	if(mydb::cxn()->affected_rows > 0) {
		$row = $result->fetch_assoc();
		$attachment = $row['attachment'];
	}
	
	$query = "DELETE FROM requisitions WHERE id =".$id;
	$result = mydb::cxn()->query($query);
	if(mydb::cxn()->error != '') throw new Exception('There was a problem deleting requisition #'.$id.' - '.mydb::cxn()->error);
	
	$query = "DELETE FROM requisitions_split WHERE requisition_id = ".$id;
	$result = mydb::cxn()->query($query);
	
	if(mydb::cxn()->error != '') throw new Exception('There was a problem deleting requisition #'.$id.' - '.mydb::cxn()->error);
	elseif($attachment != false) {
		if(!unlink($attachment)) throw new Exception('The requisition was deleted, but your attachment is now lost on the server.');
	}
	
	return;
}

/*********************************************************************************************************/
function check_uploaded_file($file_info) {

	$pieces = explode('.',$file_info['name']); //Get file extension
	$ext = $pieces[sizeof($pieces)-1];

	switch ($file_info['error']) { //Check for HTML Errors
		case 1:
		case 2:
			$status['success'] = 0;
			$status['desc'] = "The file is too large.<br>\n";
			break;
		case 3: 
			$status['success'] = 0;
			$status['desc'] = 'File only partially uploaded'; 
			break; 
		case 4: 
			$status['success'] = 0;
			$status['desc'] = 'No file uploaded'; 
			break;
		default:
			$status['success'] = 1;
			$status['desc'] = "success";
	}

	if($file_info['size'] > $_POST['MAX_FILE_SIZE']) { //Double-check filesize
		$status['success'] = 0;
		$status['desc'] = "The attached file is too large. The maximum filesize is ".$_POST['MAX_FILE_SIZE']."bytes<br>\n";
	}
	elseif (!in_array(strtolower($ext),array("jpg","png","pdf"))) {
		$status['success'] = 0;
		$status['desc'] = "Only JPG, PNG & PDF files can be uploaded.<br>\n";
	}

	return $status;
} // End 'check_uploaded_file()'

/*********************************************************************************************************/
function format_filename($requisition_id, $file_num, $file_info) {
	
	$pieces = explode('.',$file_info['name']);
	$ext = $pieces[sizeof($pieces)-1]; // Get file extension
	
	$base_path = "requisition_images/"; //Folder to store uploaded roster photos
	$filename = "requisition_" . strtolower($requisition_id) . "-" . $file_num . "." . $ext;
	
	$targets = array('base'=>$base_path,'filename'=>$filename);

	return $targets;
}
?>
