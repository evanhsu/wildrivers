<?php
	/*******************************************************************************************************/
	/* Copyright (C) 2012 Evan Hsu
       Permission is hereby granted, free of charge, to any person obtaining a copy of this software
	   and associated documentation files (the "Software"), to deal in the Software without restriction,
	   including without limitation the rights to use, copy, modify, merge, publish, distribute,
	   sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is
	   furnished to do so, subject to the following conditions:

       The above copyright notice and this permission notice shall be included in all copies or
	   substantial portions of the Software.

       THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT
	   NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
	   IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
	   WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
	   SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE. */
	/********************************************************************************************************/
	/*	This file provides an API for data access.  Currently all data access is strictly read-only
		but write-access can be implemented later.
		
		Each API call must include user-authentication info.  A user account on the RapRec site
		is sufficient for read-only access via the API.
		
		Example:
		http://www.raprec.com/api/index.php?c=people&a=rappels&id=14&year=2012&timestamp=1353494008&api_user=my_username&key=F93h90j37g6YghI29487736H83fF
		
		Controller:	people
		Action:		rappels
		ID:			14
		Year:		2012
		Timestamp:	1353494008
		API User:	my_username
		Key:		F93h90j37g6YghI29487736H83fF
		
		This will generate a JSON-formatted response containing the ID's of every rappel involving a person with ID=14.
		This request will be valid for 2 minutes from the given timestamp.
		Authentication will be performed as follows:
			if(SHA1("c=people&a=rappels&id=14&year=2012&timestamp=48392058&api_user=my_username".SHA1(my_username.USER_PASSWORD)) == F93h90j37g6YghI29487736H83fF) allow_access = true;
	*/

	require("classes/mydb_class.php");
	
	try {
	  //Break apart the request string
	  $request = $_REQUEST;
	  if(isset($request["c"]) && $request["c"] != "") $controller = mydb::cxn()->real_escape_string($request["c"]);
	  if(isset($request["a"]) && $request["a"] != "") $action = mydb::cxn()->real_escape_string($request["a"]);
	  if(isset($request["id"]) && $request["id"] != "") $id = mydb::cxn()->real_escape_string($request["id"]);
	  if(isset($request["year"]) && $request["year"] != "") $year = mydb::cxn()->real_escape_string($request["year"]);
	  if(isset($request["timestamp"]) && $request["timestamp"] != "") $timestamp = date('U',mydb::cxn()->real_escape_string($request["timestamp"]));
	  if(isset($request["api_user"]) && $request["api_user"] != "") $api_user = mydb::cxn()->real_escape_string($request["api_user"]);
	  if(isset($request["key"]) && $request["key"] != "") $encoded_key = mydb::cxn()->real_escape_string($request["key"]);
  
	  unset($request['key']); //Remove the 'key' from the request string so we can hash the request string on the server and compare it to the 'key' that was passed in.
	  
	  //Check the timestamp - ensure that current time is within 2 minutes (120 seconds)
	  $time_diff = time() - $timestamp;
	  if(($time_diff < 0) || ($time_diff > 60)) throw new Exception('That key has expired');
	  
	  //Check the authentication in the request string
	  //Get user password from database and compare with submitted password
	  $query = "SELECT password FROM authentication WHERE email = '".$api_user."'";
	  $result = mydb::cxn()->query($query);
	  if(mydb::cxn()->affected_rows < 1) throw new Exception("User not found"); //Username not found in database
	  $row = $result->fetch_assoc();
	  $encrypted_username_password_hash = $row["password"]; // Password is stored in the dB as: sha1($plaintext_username.$plaintext_password)
	  
	  $query_strings = explode("&key=",$_SERVER['QUERY_STRING']);
	  $query_string = $query_strings[0];
	  $encoded_params = sha1($query_string.$encrypted_username_password_hash); // This is the same as: sha1($params.sha1($plaintext_username.$plaintext_password))
	  if($encoded_params != $encoded_key) throw new Exception("Hash doesn't match"); //Client hash doesn't match server hash.
	  
	  // The request has now been authenticated
	  // Call the requested controller/action
	  switch($controller) {
		  case "people":
		  	people($id, $action, $year);
			break;
		  case "crews":
		  	crews($id, $action, $year);
			break;
		  case "operations":
		  	operations($id, $action, $year);
			break;
		  case "rappels":
		  	rappels($id, $action, $year);
			break;
		  case "ropes":
		  	ropes($id, $action, $year);
			break;
		  case "genies":
		  	genies($id, $action, $year);
			break;
		  case "letdownlines":
		  	letdownlines($id, $action, $year);
			break;
		  case "aircraft":
		  	aircraft($id, $action, $year);
			break;
		  default:
		  	throw new Exception("Invalid Controller requested"); //Non-existant controller was requested
			break;
	  }
	  
	} catch (Exception $e) {
		echo $e->getMessage()."<br />\n";
		echo "That request was not valid.";
		echo "<br />\n".sha1("c=crews&a=rappels&id=1&year=2011&timestamp=1353494825&api_user=evanhsu@gmail.com"."df2954c5e0dd0e7c64df44795362b2c57e8436f4");
		echo "<br />\nTime: ".time()."<br />\n";
		echo "Query: ".$query_string;
	}
	/*********************************************************************************************/
	/*********************************************************************************************/
	/*********************************************************************************************/
	
	function people($id, $action, $year) {
		switch($action) {
			case "rappels":
				$query = "SELECT * FROM view_rappels WHERE hrap_id = ".$id." AND year = '".$year."' ORDER BY date";
				break;
			default:
				throw new Exception; //Non-existent action was requested
				break;
		}
		
		$result = mydb::cxn()->query($query);
		$object = array();
		while($row = $result->fetch_assoc()) {
			$object[] = $row;
		}
		echo json_encode($object, JSON_FORCE_OBJECT);
	}
	
	function crews($id, $action, $year) {
		switch($action) {
			case "operations":
				$query = "SELECT DISTINCT operation_id,
										incident_number,
										aircraft_tailnumber,
										spotter_id,
										pilot_name,
										location,
										date,
										operation_type,
										aircraft_fullname,
										year
							FROM `view_rappels`
							WHERE crew_id = ".$id." AND year = '".$year."'
							ORDER BY date";
				break;
			case "rappels":
				$query = "SELECT * FROM view_rappels WHERE crew_id = ".$id." and year = '".$year."' ORDER BY date, operation_id";
				break;
			case "people":
				$query = "SELECT ";
				break;
			case "ropes":
				$query = "SELECT ";
				break;
			case "genies":
				$query = "SELECT ";
				break;
			case "letdownlines":
				$query = "SELECT ";
				break;
			default:
				throw new Exception("Invalid Action specified"); //Non-existent action was requested
				break;
		}
		
		$result = mydb::cxn()->query($query);
		$object = array();
		while($row = $result->fetch_assoc()) {
			$object[] = $row;
		}
		echo json_encode($object, JSON_FORCE_OBJECT);
	}
	
	function operations($id, $action, $year) {
		$query = "SELECT ";
		
		$result = mydb::cxn()->query($query);
		$object = array();
		while($row = $result->fetch_assoc()) {
			$object[] = $row;
		}
		echo json_encode($object, JSON_FORCE_OBJECT);
	}
	
	function rappels($id, $action, $year) {
		$query = "SELECT ";
		
		$result = mydb::cxn()->query($query);
		$object = array();
		while($row = $result->fetch_assoc()) {
			$object[] = $row;
		}
		echo json_encode($object, JSON_FORCE_OBJECT);
	}
	
	function ropes($id, $action, $year) {
		$query = "SELECT ";
		
		$result = mydb::cxn()->query($query);
		$object = array();
		while($row = $result->fetch_assoc()) {
			$object[] = $row;
		}
		echo json_encode($object, JSON_FORCE_OBJECT);
	}
	
	function genies($id, $action, $year) {
		$query = "SELECT ";
		
		$result = mydb::cxn()->query($query);
		$object = array();
		while($row = $result->fetch_assoc()) {
			$object[] = $row;
		}
		echo json_encode($object, JSON_FORCE_OBJECT);
	}
	
	function letdownlines($id, $action, $year) {
		$query = "SELECT ";
		
		$result = mydb::cxn()->query($query);
		$object = array();
		while($row = $result->fetch_assoc()) {
			$object[] = $row;
		}
		echo json_encode($object, JSON_FORCE_OBJECT);
	}
	
	function aircraft($id, $action, $year) {
		switch($action) {
			case "operations":
				$query = "SELECT ";
				break;
			case "rappels":
				$query = "SELECT ";
				break;
			default:
				throw new Exception; //Non-existent action was requested
				break;
		}
		
		$result = mydb::cxn()->query($query);
		$object = array();
		while($row = $result->fetch_assoc()) {
			$object[] = $row;
		}
		echo json_encode($object, JSON_FORCE_OBJECT);
	}
	
?>