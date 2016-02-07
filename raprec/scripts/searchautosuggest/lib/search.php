<?php
require_once("../../../includes/php_doc_root.php");
require_once("classes/mydb_class.php");
require_once("includes/constants.php");

	session_name('raprec');
	session_start();
	
	$searchq = strip_tags($_GET['q']);
	
	$search_type = strtolower(mydb::cxn()->real_escape_string($_GET['search_type']));
	switch($search_type) {
	case 'hrap_for_roster':
	$query		="SELECT DISTINCT hraps.id as return_id, concat('DOB: ',date_format(birthdate,'%c/%d/%Y')) as subtext, concat(firstname,' ',lastname) as return_text "
				."FROM hraps LEFT OUTER JOIN rosters ON rosters.hrap_id = hraps.id "
				."WHERE hraps.id NOT IN ( "
				."	SELECT DISTINCT hrap_id from rosters WHERE year = '".$_SESSION['current_view']['year']."' "
				.") "
				." AND (firstname LIKE \"".$searchq."%\" OR lastname LIKE \"".$searchq."%\" OR concat(firstname,' ',lastname) LIKE \"".$searchq."%\") ORDER BY return_text "
				."LIMIT ".$_SESSION['autosuggest_num_results'];
		break;

	case 'hrap_for_rappel':
		$query	="SELECT DISTINCT hraps.id as return_id, concat(hraps.firstname,' ',hraps.lastname) as return_text, crews.name as subtext "
				."FROM hraps INNER JOIN rosters ON hraps.id = rosters.hrap_id "
				."INNER JOIN crews ON rosters.crew_id = crews.id WHERE rosters.year = '".$_SESSION['current_view']['year']."' "
				."AND (firstname LIKE \"".$searchq."%\" OR lastname LIKE \"".$searchq."%\" OR concat(firstname,' ',lastname) LIKE \"".$searchq."%\") ORDER BY subtext,return_text "
				."LIMIT ".$_SESSION['autosuggest_num_results'];
		break;
		
	case 'spotter':
		$query	="SELECT DISTINCT hraps.id as return_id, concat(hraps.firstname,' ',hraps.lastname) as return_text, crews.name as subtext "
				."FROM hraps INNER JOIN rosters ON hraps.id = rosters.hrap_id "
				."INNER JOIN crews ON rosters.crew_id = crews.id WHERE rosters.year = '".$_SESSION['current_view']['year']."' "
				."AND (firstname LIKE '".$searchq."%' OR lastname LIKE '".$searchq."%' OR concat(firstname,' ',lastname) LIKE '".$searchq."%') "
				."AND hraps.spotter <> 0 "
				."ORDER BY subtext,return_text "
				."LIMIT ".$_SESSION['autosuggest_num_results'];
		break;
	case 'rope':
	case 'genie':
	case 'letdown_line':
		$query = "SELECT DISTINCT items.id as return_id, items.serial_num as return_text, crews.name as subtext "
				."FROM items INNER JOIN crews ON items.crew_affiliation_id = crews.id "
				."WHERE items.serial_num LIKE '%".$searchq."%' AND items.status = 'in_service'  AND items.item_type = '".$search_type."' "
				."ORDER BY items.serial_num "
				."LIMIT ".$_SESSION['autosuggest_num_results'];
		break;
		
	case 'pilot':
		
		break;
	
	case 'headshot':
		$query = "SELECT headshot_filename FROM hraps WHERE hraps.id = ".$searchq;
		$result = mydb::cxn()->query($query);
		$row = $result->fetch_assoc();
		echo $row['headshot_filename'];
		exit(1);
		break;
		
	default:
		break;
	} // End: switch($_GET['search_type'])

	if(strlen($searchq)>0){

		$result = mydb::cxn()->query($query);
		
		if(mydb::cxn()->affected_rows > 0) {
			$return_field_base_name	=	$_GET['return_field'];
			$return_field_for_id 	=	$_GET['return_field'] . "_id";
			$return_field_for_text	=	$_GET['return_field'] . "_text";
			
			echo " <!--[if lte IE 6]><table><tr><td><![endif]-->\n"
				."<ul>\n";
			
			while ($row = $result->fetch_array()) {
				echo "<li><a href=\"javascript:populate_autosuggest_form_fields(&quot;".$return_field_base_name."&quot;, &quot;".$row['return_id']."&quot;, &quot;".$row['return_text']."&quot;);\" ><span style=\"font-weight:bold;\">"
					.$row['return_text']."</span><br><small>".$row['subtext']."</small></a></li>";
			}

			
			echo "</ul>\n"
				."<!--[if lte IE 6]></td></tr></table></a><![endif]-->";
		}
	}
?>
