<?php

	session_start();
	require_once("../includes/auth_functions.php");
	
	if(($_SESSION['logged_in'] == 1) && check_access("roster")) {
		require_once("../classes/mydb_class.php");
	}
	else {
		if($_SESSION['logged_in'] != 1) $_SESSION['intended_location'] = $_SERVER['PHP_SELF'];
		header('location: https://wildrivers.firecrew.us/admin/index.php');
	}

	//****************************************************************************************
	//$cmd == 0; //Default - main menu
	//$cmd == 1; //Modify an existing roster (specify year)
	//$cmd == 2; //Create a new roster from scratch (specify year)
	//$cmd == 3; //Delete an existing roster (specify year)
	
	//****************************************************************************************
	function check_form_data($data) {
		$status = array('success'=>1,'desc'=>"");
		foreach($data as $field=>$value) {
			if(($value == '') && ($field != 'uploadedfile')) $status = array('success'=>0,'desc'=>"Enter something in the ".$field." field");
		}
		
		if(!$status['success']) return $status;
		else {
			if($data['uploadedfile'] != '') $status = check_uploaded_file($_FILES['uploadedfile']); // $status['success'] (0,1) - $status['desc'] (text)
			else $status = array('success'=>1,'desc'=>'');	//No need to check file if we're using the pre-stored "missing image" placeholder jpeg
			return $status;
		}
	}
	
	//****************************************************************************************

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
			$status['desc'] = "The file size is too large.<br>\n";
		}
		elseif (strtolower($ext) != "jpg") {
			$status['success'] = 0;
			$status['desc'] = "Only JPEG images are allowed (file extension '.jpg').<br>\n";
		}

		return $status;
	} // End 'check_uploaded_file()'

	//****************************************************************************************
	function resize($src_img_filename,$dst_img_filename) {

		$new_width = 100;
		$new_height= 100;
		$new_ratio = $new_width / $new_height;

		$src_img = imagecreatefromjpeg($src_img_filename);

		$width = imagesx($src_img) or die("Can't get image width");
		$height= imagesy($src_img) or die("Can't get image height");
		$ratio = $width / $height;

		if($ratio >= $new_ratio) {
			//Image is too wide - size the height to fill the thumbnail, then crop off the sides
			$src_height = $height;
			$src_width = round($height * $new_ratio);

			$src_x = round(($width - $src_width) / 2);
			$src_y = 0;
		}
		else {
			//Image is too tall - resize the width to fill the thumbnail, then crop off top & bottom
			$src_width = $width;
			$src_height = round($width / $new_ratio);

			$src_y = round(($height - $src_height) / 2);
			$src_x = 0;
		}

		$dst_image = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled ($dst_image,$src_img,0,0,$src_x,$src_y,$new_width,$new_height,$src_width,$src_height);

		return imagejpeg($dst_image, $dst_img_filename, 85); //Output resized image to file with 85% jpeg quality
	}
	
	//****************************************************************************************
	function format_filename($firstname,$lastname,$missing_image_bool=false) {
		
		//If missing_image_bool is TRUE, set image path to the missing image placeholder
		if($missing_image_bool) {
			$base_path = "../images/roster_headshots/"; //Folder to store uploaded roster photos
			$filename = "missing_image.jpg";
		}
		else {
			$base_path = "../images/roster_headshots/"; //Folder to store uploaded roster photos
			$filename = strtolower($firstname) . "_" . strtolower($lastname) . "_headshot.jpg";
		}
		$targets = array('base'=>$base_path,'filename'=>$filename);

		return $targets;
	}
	//****************************************************************************************
	//***** MAIN *****
	if($_POST['function'] == "check_roster_year") {
		if(strlen($_POST['year']) != 4) $status = array('success'=>0,'desc'=>"Enter a four-digit year (yyyy)");
		elseif(!is_numeric($_POST['year'])) $status = array('success'=>0,'desc'=>$_POST['year'] . " is not a valid numeric year");
		else {
			$result = mydb::cxn()->query("SELECT year FROM roster WHERE year like '".$_POST['year']."'");
			if(mydb::cxn()->error != '') {
				die("Retrieving YEARs failed: " . mydb::cxn()->error . "<br>\n".$query);
			}
			$row = $result->fetch_assoc();
			
			if($row['year'] == $_POST['year']) {
				$status = array('success'=>0,'desc'=>"A roster already exists for that year");
			}
			else $status = array('success'=>1,'desc'=>"");
		}
	}
	elseif(($_POST['function'] == "create_new_roster") || ($_POST['function'] == "add_crewmember")) {
		$status = check_form_data($_POST); //$status = array('success','desc');
		if(!$status['success']) {/*Bad form data - don't add to database*/}
		else {
			if($_POST['uploadedfile'] == '') $missing_image_bool = 1;
			else $missing_image_bool = 0;
			
			$targets = format_filename($_POST['firstname'],$_POST['lastname'],$missing_image_bool);
			$target_path = $targets['base'] . $targets['filename'];
			
			if(!$missing_image_bool) {
				if(!@move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
					$status['success'] = 0;
					$status['desc'] = "Unable to accept photo file, try again later.<br>\n";
				}
				elseif(!resize($target_path, $target_path)) {//file was successfully moved onto the server
					$status['success'] = 0;
					$status['desc'] = "Unable to resize photo.<br>\n";
				}
			}
			if($status['success'] != 0) {
				// Photo successfully uploaded, now add an entry in the database
				$query = "insert into crewmembers(firstname,lastname,headshot_filename,bio)
						  values(\"".$_POST['firstname']."\",\"".$_POST['lastname']."\",\"images/roster_headshots/".$targets['filename']."\",\"".nl2br(htmlentities($_POST['bio'],ENT_QUOTES))."\")";
				$result = mydb::cxn()->query($query);
				if(mydb::cxn()->error != '') {
					die("Update crewmembers table failed: " . mydb::cxn()->error . "<br>\n".$query);
				}
				
				$query = "select max(id) as id from crewmembers"; //Get the most recent id (the one we just added
				$result = mydb::cxn()->query($query);
				if(mydb::cxn()->error != '') {
					die("Couldn't retrieve new crewmember from the database: " . mydb::cxn()->error . "<br>\n".$query);
				}

				$row = $result->fetch_assoc();

				$result = mydb::cxn()->query("insert into roster(id,year) values(".$row['id'].",".$_POST['year'].")");
				if(mydb::cxn()->error != '') {
					die("Error adding crewmember to roster" . mydb::cxn()->error . "<br>\n".$query);
				}

				$status = array('success'=>1,'desc'=>$_POST['firstname']." ".$_POST['lastname']." has been added to the ".$_POST['year']." roster");
				unset($_POST['function']);
			}
		} //end if(!$status['success']) else {
	}
	elseif($_POST['function'] == "choose_crewmember") {
		$query = "insert into roster(id,year) values(".$_POST['crewmember_id'].", ".$_POST['year'].")";
		$result = mydb::cxn()->query($query);
		
		if(!mydb::cxn()->error) {
			$status = array('success'=>1,'desc'=>"Crewmember has been added to the ".$_POST['year']." roster");
		}
		else {
			$status = array('success'=>0,'desc'=>"Crewmember was NOT added to the roster. Try again later.");
		}
	
		unset($_POST['function']);
	}
	elseif($_POST['function'] == "modify_crewmember") {
		$status = array('success'=>1,'desc'=>"");
		if($_FILES['uploadedfile']['name'] != "") {
			$status = check_uploaded_file($_FILES['uploadedfile']); //$status = array('success','desc');
			if(!$status['success']) {/*Bad form data - don't add to database... $status['desc'] holds the explanation already */}
			else {
				$targets = format_filename($_POST['firstname'],$_POST['lastname'], false);
				$target_path = $targets['base'] . $targets['filename'];
				
				if(!@move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
					$status['success'] = 0;
					$status['desc'] = "Unable to accept photo file, try again later.<br>\n";
				}
				elseif(!resize($target_path, $target_path)) {//file was successfully moved onto the server
					$status['success'] = 0;
					$status['desc'] = "Unable to resize photo.<br>\n";
				}
				else {
					// Photo successfully uploaded, now update entry in the database
					$query = "update crewmembers set firstname = \"".$_POST['firstname']."\", lastname = \"".$_POST['lastname']."\", bio = \"".nl2br(htmlentities($_POST['bio'],ENT_QUOTES))
										. "\", headshot_filename = \"images/roster_headshots/".$targets['filename']."\" where id like \"".$_POST['id']."\"";
					$result = mydb::cxn()->query($query);
					if(mydb::cxn()->error != '') {
						die("Modify crewmember info failed: " . mydb::cxn()->error . "<br>\n".$query);
					}
					$status = array("success"=>1,"desc"=>"Profile has been successfully updated.");
				}
			}
		} //END if($_FILES['uploadedfile']['name'] != "")
		else {
			// A new image file was NOT specified = just update everything else
			$query = "update crewmembers set firstname = \"".$_POST['firstname']."\", lastname = \"".$_POST['lastname']."\", bio = \"".nl2br(htmlentities($_POST['bio'],ENT_QUOTES))
					."\" where id like \"".$_POST['id']."\"";
			$result = mydb::cxn()->query($query);
			if(mydb::cxn()->error != '') {
				die("Modify crewmember info failed: " . mydb::cxn()->error . "<br>\n".$query);
			}

			$status = array("success"=>1,"desc"=>"Profile has been successfully updated.");
		}
	}
	else {
		$status = array('success'=>1,'desc'=>"");
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Modify Roster :: Wild Rivers Ranger District</title>

<?php include_once("../classes/Config.php"); ?>
<base href="<?php echo ConfigService::getConfig()->app_url ?>" />

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
<meta name="Description" content="Modify Crew Rosters (Admin Only)" />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />

</head>

<body>
<div id="wrapper">
	<div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none" alt="Scroll down..." /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Wild Rivers Ranger District - Administrative Console</div>
    </div>
	<?php include("../includes/menu.php"); ?>

    <div id="content">
    <?php
    	if(!isset($_GET['cmd']) || ($_GET['cmd'] == 0)) { //Main Menu ********************************************************
			echo "<h1>Manage Crew Rosters</h1><br><br>\n";
			
			//if(isset($_POST['create_new_roster']) && ($_POST['create_new_roster'] == 1)) echo $status['desc'] . "<br><br>\n";
			
    		echo "<a href=\"".$_SERVER['PHP_SELF']."?cmd=1\">Modify an existing roster</a><br>\n"
				."<a href=\"".$_SERVER['PHP_SELF']."?cmd=2\">Create a new roster</a><br>\n"
				."<a href=\"".$_SERVER['PHP_SELF']."?cmd=3\">Delete an existing roster</a><br>\n";
		}
		elseif($_GET['cmd'] == 1) { //Modify an existing roster ***************************************************************************************************
			echo "<h1>Modify an Existing Roster</h1>";
			if(isset($_POST['year'])) echo " - (".$_POST['year'].")<br><br>\n";
			if(!isset($_POST['year'])) {
				echo "<form action=\"".$_SERVER['PHP_SELF']."?cmd=1\" method=\"POST\">\n"
					."Select the year you want to modify:<br>\n"
					."<select name=\"year\" style=\"width:75px\">\n";
				
				//Get all existing years from the database
				$result = mydb::cxn()->query("SELECT DISTINCT year FROM roster ORDER BY year DESC");
				if(mydb::cxn()->error != '') {
					die("Retrieving YEARs for dropdown menu failed: " . mydb::cxn()->error . "<br>\n".$query);
				}
				while($row = $result->fetch_assoc()) {
					echo "<option value=\"".$row['year']."\">".$row['year']."\n";
				}
				echo "</select>\n<input type=\"submit\" value=\"Continue\">\n</form><br>\n\n";
			} //end if(!isset($_GET['year']))
			elseif (!isset($_POST['function'])) { //Year has been selected, add/modify NOT YET specified
				if($status['desc'] != "") echo "<b>".$status['desc']."</b><br><br>\n\n";
				
				echo "<b>Add a first-time crewmember</b> - this person has never been on the crew before\n"
					."<form action=\"".$_SERVER['PHP_SELF']."?cmd=1\" method=\"POST\">\n"
					."<input type=\"hidden\" name=\"function\" value=\"add_crewmember_menu\">"
					."<input type=\"hidden\" name=\"year\" value=\"".$_POST['year']."\">"
					."<input type=\"submit\" value=\"Add\">"
					."</form><br><br>\n\n";
				echo "<b>Add a returning crewmember</b> - choose from a list of people who have been on this crew before\n"
					."<form action=\"".$_SERVER['PHP_SELF']."?cmd=1\" method=\"POST\">\n"
					."<input type=\"hidden\" name=\"function\" value=\"choose_crewmember_menu\">"
					."<input type=\"hidden\" name=\"year\" value=\"".$_POST['year']."\">"
					."<input type=\"submit\" value=\"Choose\">"
					."</form><br><br>\n\n";
				echo "<b>Modify/Remove a crewmember from this roster</b> - update names, pictures, bios - or remove people from this roster\n"
					."<form action=\"".$_SERVER['PHP_SELF']."?cmd=1\" method=\"POST\">\n"
					."<input type=\"hidden\" name=\"function\" value=\"modify_crewmember_menu\">"
					."<input type=\"hidden\" name=\"year\" value=\"".$_POST['year']."\">"
					."<input type=\"submit\" value=\"Modify\">"
					."</form><br><br>\n\n";
			}// end elseif (!isset($_POST['function']))
			elseif ($_POST['function'] == "add_crewmember_menu") {
				echo "<h1>Add New Crewmember</h1> ...or <b><a href=\"admin/modify_roster.php\">Quit</a></b><br><br>\n";
				if($status['desc'] != "") echo "<b>".$status['desc']."</b><br><br>\n\n";
				
				echo "<form enctype=\"multipart/form-data\" action=\"".$_SERVER['PHP_SELF']."?cmd=1\" method=\"POST\">\n"
					."<input type=\"hidden\" name=\"year\" value=\"".$_POST['year']."\">\n"
					."<input type=\"hidden\" name=\"function\" value=\"add_crewmember\">\n"
					."<table><tr>"
					."<td style=\"width:100px\">First Name: </td><td><input type=\"text\" name=\"firstname\"></td></tr>\n"
					."<tr><td>Last Name: </td><td><input type=\"text\" name=\"lastname\"></td></tr>\n"
					."<tr><td>Upload a Photo:</td><td><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"500000\" /> <input name=\"uploadedfile\" type=\"file\" /></td></tr>\n"
					."<tr><td colspan=\"2\" style=\"font-size:10px\">Photo MUST be a JPEG (.jpg) image sized to 100x100 pixels<br>Max file size is 500KB<br><br></td></tr>\n"
					."<tr><td>Bio:</td><td></td></tr>\n<tr><td colspan=\"2\"><textarea name=\"bio\" WRAP=VIRTUAL style=\"width:450px;height:50px;font-size:10px;font-family:verdana;\"></textarea></td></tr>\n"
					."<tr><td colspan=\"2\"><input type=\"submit\" value=\"Add Crewmember\" /></td></tr>"
					."</table>\n</form><br><br>\n\n";
			} //end add_crewmember_menu
			elseif ($_POST['function'] == "choose_crewmember_menu") {
				echo "<form action=\"".$_SERVER['PHP_SELF']."?cmd=1\" method=\"POST\">\n"
					."<input type=\"hidden\" name=\"function\" value=\"choose_crewmember\">\n"
					."<input type=\"hidden\" name=\"year\" value=\"".$_POST['year']."\">\n"
					."Select a crewmember from the dropdown list (alphabetical by last name):<br>\n"
					."<select name=\"crewmember_id\" style=\"width:150px\">\n";
				
				//Get all existing crewmembers from the database (who are NOT already a member of this year's roster)
				$result = mydb::cxn()->query("	SELECT	DISTINCT crewmembers.id,
												CONCAT(crewmembers.lastname,', ',crewmembers.firstname) as name
										FROM	crewmembers LEFT OUTER JOIN roster
										ON		crewmembers.id = roster.id
										WHERE	crewmembers.id not in (select id from roster where year like \"".$_POST['year']."\")
										ORDER BY lastname");
				if(mydb::cxn()->error != '') {
					die("Retrieving crewmembers for dropdown menu failed: " . mydb::cxn()->error . "<br>\n".$query);
				}
				while($row = $result->fetch_assoc()) {
					echo "<option value=\"".$row['id']."\">".$row['name']."\n";
				}
				echo "</select>\n<input type=\"submit\" value=\"Continue\">\n</form><br>\n\n";
			
			} //end choose_crewmember_menu
			elseif (($_POST['function'] == "modify_crewmember_menu") || ($_POST['function'] == "modify_crewmember")) {
				echo "<h1>Modify Existing Crewmembers</h1><br><br>\n";
				if($_POST['remove'] == 1) {
					$result = mydb::cxn()->query(" DELETE from roster
											WHERE id like \"".$_POST['id']."\"
											and year like \"".$_POST['year']."\"");
					
					if(mydb::cxn()->error != '') {
						$status = array('success'=>0,'desc'=>"Removing crewmember failed: ".mydb::cxn()->error);
					}
				}
				if($status['desc'] != "") {
					echo "<b>".$status['desc']."</b><br><br>\n\n";
				}
					
				$result = mydb::cxn()->query("	SELECT	crewmembers.id,
												crewmembers.firstname,
												crewmembers.lastname,
												crewmembers.headshot_filename,
												crewmembers.bio
										FROM	crewmembers INNER JOIN roster
										ON		crewmembers.id = roster.id
										WHERE	roster.year like \"".$_POST['year']."\"
										ORDER BY	crewmembers.id");
				if(mydb::cxn()->error != '') {
					die("Retrieving roster failed: " . mydb::cxn()->error . "<br>\n".$query);
				}
				
				$count = 0;
				while($row = $result->fetch_assoc()) {
					$count = $count + 1;
					if($count % 2 == 0) $side = "right";
					else $side = "left";
					
					$bio = str_replace("<br />","",html_entity_decode($row['bio']));
					
					echo "<form enctype=\"multipart/form-data\" action=\"".$_SERVER['PHP_SELF']."?cmd=1\" method=\"POST\">\n"
						."<input type=\"hidden\" name=\"year\" value=\"".$_POST['year']."\">\n"
						."<input type=\"hidden\" name=\"function\" value=\"modify_crewmember\">\n"
						."<input type=\"hidden\" name=\"id\" value=\"".$row['id']."\">\n"
						."<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"500000\" />\n";
						
					echo "<div class=\"bio\">\n"
						."<img src=\"../".$row['headshot_filename']."\" class=\"biopix_$side /><b class=\"highlight1\" style=\"float:$side\">";
					
					echo "<div style=\"float:$side\"><input type=\"text\" name=\"firstname\" value=\"".$row['firstname']."\" style=\"width:100px; text-align:right;\">"
						."<input type=\"text\" name=\"lastname\" value=\"".$row['lastname']."\" style=\"width:100px;\"></div>"
						."<input type=\"submit\" value=\"Modify\" style=\"float:$side\">\n"
						."<input type=\"checkbox\" name=\"remove\" value=\"1\" style=\"float:$side; margin-$side:15px;\"><span style=\"float:$side;font-size:9px\">Remove from roster</span>"
						."</b><br /><br />\n"
						."<textarea name=\"bio\" style=\"width:85%; height:50px; text-align:left; font-family:Verdana;font-size:9px;float:$side\">".$bio."</textarea>\n";
					if($count % 2 == 0) echo "<input name=\"uploadedfile\" type=\"file\" style=\"float:$side\"><div style=\"float:right\">Choose a new photo --> </div></div>\n</form><br>\n\n";
					else echo "<input name=\"uploadedfile\" type=\"file\" style=\"float:$side\"><div style=\"float:left\"> <-- Choose a new photo</div></div>\n</form><br>\n\n";
					
					echo "<br style=\"clear:both\"/><br />\n\n";
				}
				
				
			} //end modify_crewmember_menu

			else echo "<b>".$status['desc']."</b><br><br>\n\n";
		}
		elseif($_GET['cmd'] == 2) { //Create a new roster ********************************************************************************************************
			if(!isset($_POST['year']) || ($status['success']==0)) {
				//A VALID year has NOT YET been specified, create a text entry field
				if($status['success'] != 1) echo "<b>".$status['desc']."</b><br><br>\n\n";
				
				echo "<h1>Create a New Roster</h1><br><br>\n";
				echo "<form action=\"".$_SERVER['PHP_SELF']."?cmd=2\" method=\"POST\">\n"
					."What year is this roster active?<br>"
					."<input type=\"text\" name=\"year\" value=\"".date('Y')."\" style=\"width:40px\">\n"
					."<input type=\"hidden\" name=\"function\" value=\"check_roster_year\">\n"
					."<input type=\"submit\" value=\"Create\">"
					."</form><br>\n\n";
			} //end if(!isset($_POST['year']))
			elseif($_POST['function'] == "check_roster_year") {//Year to create has been selected, new/returning crewmember NOT YET specified
				echo "<h1>Create a New Roster - ".$_POST['year']."</h1><br><br>\n";
				if($status['desc'] != "") echo "<b>".$status['desc']."</b><br><br>\n\n";
				
				echo "<b>Add a first-time crewmember</b> - this person has never been on the crew before\n"
					."<form action=\"".$_SERVER['PHP_SELF']."?cmd=2\" method=\"POST\">\n"
					."<input type=\"hidden\" name=\"function\" value=\"add_crewmember_menu\">"
					."<input type=\"hidden\" name=\"year\" value=\"".$_POST['year']."\">"
					."<input type=\"submit\" value=\"Add\">"
					."</form><br><br>\n\n";
				echo "<b>Add a returning crewmember</b> - choose from a list of people who have been on this crew before\n"
					."<form action=\"".$_SERVER['PHP_SELF']."?cmd=2\" method=\"POST\">\n"
					."<input type=\"hidden\" name=\"function\" value=\"choose_crewmember_menu\">"
					."<input type=\"hidden\" name=\"year\" value=\"".$_POST['year']."\">"
					."<input type=\"submit\" value=\"Choose\">"
					."</form><br><br>\n\n";
			}
			elseif ($_POST['function'] == "add_crewmember_menu") {
				echo "<h1>Add New Crewmember</h1> ...or <b><a href=\"admin/modify_roster.php\">Quit</a></b><br><br>\n";
				if($status['desc'] != "") echo "<b>".$status['desc']."</b><br><br>\n\n";
				
				echo "<form enctype=\"multipart/form-data\" action=\"".$_SERVER['PHP_SELF']."?cmd=1\" method=\"POST\">\n"
					."<input type=\"hidden\" name=\"year\" value=\"".$_POST['year']."\">\n"
					."<input type=\"hidden\" name=\"function\" value=\"add_crewmember\">\n"
					."<table><tr>"
					."<td style=\"width:100px\">First Name: </td><td><input type=\"text\" name=\"firstname\"></td></tr>\n"
					."<tr><td>Last Name: </td><td><input type=\"text\" name=\"lastname\"></td></tr>\n"
					."<tr><td>Upload a Photo:</td><td><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"500000\" /> <input name=\"uploadedfile\" type=\"file\" /></td></tr>"
					."<tr><td colspan=\"2\" style=\"font-size:10px\">Photo MUST be a JPEG (.jpg) image sized to 100x100 pixels<br>Max file size is 500KB<br><br></td></tr>"
					."<tr><td>Bio:</td><td></td></tr>\n<tr><td colspan=\"2\"><textarea name=\"bio\" WRAP=VIRTUAL style=\"width:450px;height:50px;\"></textarea></td></tr>\n"
					."<tr><td colspan=\"2\"><input type=\"submit\" value=\"Add Crewmember\" /></td></tr>"
					."</table>\n</form><br><br>\n\n";
			} //end add_crewmember_menu
			elseif ($_POST['function'] == "choose_crewmember_menu") {
				echo "<form action=\"".$_SERVER['PHP_SELF']."?cmd=1\" method=\"POST\">\n"
					."<input type=\"hidden\" name=\"function\" value=\"choose_crewmember\">\n"
					."<input type=\"hidden\" name=\"year\" value=\"".$_POST['year']."\">\n"
					."Select a crewmember from the dropdown list (alphabetical by last name):<br>\n"
					."<select name=\"crewmember_id\" style=\"width:150px\">\n";
				
				//Get all existing crewmembers from the database (who are NOT already a member of this year's roster)
				$result = mydb::cxn()->query("	SELECT	crewmembers.lastname,
												crewmembers.firstname,
												crewmembers.id,
												roster.year
										FROM	crewmembers INNER JOIN roster
										ON		crewmembers.id = roster.id
										WHERE	roster.year not like \"".$_POST['year']."\"
										ORDER BY lastname");
				if(mydb::cxn()->error != '') {
					die("Retrieving crewmembers for dropdown menu failed: " . mydb::cxn()->error . "<br>\n".$query);
				}

				while($row = $result->fetch_assoc()) {
					echo "<option value=\"".$row['id']."\">".$row['lastname'].", ".$row['firstname']."\n";
				}
				echo "</select>\n<input type=\"submit\" value=\"Continue\">\n</form><br>\n\n";
			
			} //end choose_crewmember_menu
			else echo "<b>".$status['desc']."</b><br><br>\n\n";
		}
		elseif($_GET['cmd'] == 3) {//Delete an existing roster ********************************************************
			echo "<h1>Delete an Existing Roster</h1><br><br>\n";
			
			if(!isset($_POST['year'])) {
				echo "<form action=\"".$_SERVER['PHP_SELF']."?cmd=3\" method=\"POST\">\n"
					."Select the year you want to delete:<br>\n"
					."<select name=\"year\" style=\"width:75px\">\n";
				
				//Get all existing years from the database
				$result = mydb::cxn()->query("SELECT DISTINCT year FROM roster");
				if(mydb::cxn()->error != '') {
					die("Retrieving YEARs for dropdown menu failed: " . mydb::cxn()->error . "<br>\n".$query);
				}

				while($row = $result->fetch_assoc()) {
					echo "<option value=\"".$row['year']."\">".$row['year']."\n";
				}
				echo "</select>\n<input type=\"submit\" value=\"Continue\">\n</form><br>\n\n";
			} //end if(!isset($_GET['year']))
			else {
				//Year has been specified, DELETE all entries in the ROSTER table corresponding to that year
				$query = "DELETE from roster WHERE year like \"".$_POST['year']."\"";
				$result = mydb::cxn()->query($query);
				if(mydb::cxn()->error != '') {
					die("Deleting the ".$_POST['year']." roster failed: " . mydb::cxn()->error . "<br>\n".$query);
				} else {
					echo "The ".$_POST['year']." roster has been successfully removed!<br>\n";
				}
			}
		} // end elseif($_GET['cmd'] == 3)
		else { //Unknown command **************************************************************************************
			echo "<br><br><b>Please select an option from the <a href=\"admin/modify_roster.php\">main menu</a></b>";
		}
    ?>
    
    
    
    
    </div><!-- end 'content'-->
</div><!-- end 'wrapper'-->

<?php include("../includes/footer.html") ?>

</body>
</html>