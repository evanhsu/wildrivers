<?php

require('../raprec/scripts/connect.php');
$backup_dir		= '../raprec/db_backups';

//===================================================================
//SET VARIABLES
$filename_base	= 'db';	//The database name & backup date will be appended to the filename base
$ignored_tables = array(	'CHARACTER_SETS',
							'COLLATIONS',
							'COLLATION_CHARACTER_SET_APPLICABILITY',
							'COLUMNS','COLUMN_PRIVILEGES',
							'KEY_COLUMN_USAGE',
							'PROFILING',
							'ROUTINES',
							'SCHEMATA',
							'SCHEMA_PRIVILEGES',
							'STATISTICS',
							'TABLES',
							'TABLE_CONSTRAINTS',
							'TABLE_PRIVILEGES',
							'TRIGGERS',
							'USER_PRIVILEGES',
							'VIEWS');

$day_text	= strtolower(date('D'));	//A textual representation of a day, three letters ('Mon' -> 'Sun')
$file_date	= date('Y-m-d');			//Date string to be appended onto the filename

$todays_folder = $backup_dir."/".$day_text;

//===================================================================
//Connect to database
$dbh = connect();

//Check for existing directory structure
if(!is_dir($backup_dir)) mkdir($backup_dir);
if(!is_dir($todays_folder)) mkdir($todays_folder);
//===================================================================

//Get a list of databases on this database server & start a loop over databases
$db_list_result = mysql_list_dbs($dbh);
while ($db_list_row = mysql_fetch_object($db_list_result)) {
	$db = $db_list_row->Database;
	if($db == 'information_schema') continue; //Don't backup the schema
	
	$backup_text = "-- Generation Time: ".date("l, F jS, Y -- H:i:s")."\n"
				."--\n"
				."-- Database: `".$db."`\n"
				."--\n\n"
				."-- --------------------------------------------------------\n\n";
				
	//Start a loop over tables
	$table_result = mysql_query("show full tables in ".$db." WHERE table_type != 'VIEW'",$dbh); // 2 columns: [name, table_type]
	while($table_row = mysql_fetch_row($table_result)) {
		$table = $table_row[0]; // There should only be 1 table in each table_row
		if(in_array($table,$ignored_tables)) continue;
		
		//Get a 2D array of table info: ('Field', 'Type', 'Null', 'Key', 'Default', 'Extra') for each column
		$columns = array();
		$col_result = mysql_query("show columns in ".$table,$dbh);
		while($col_row = mysql_fetch_assoc($col_result)) {
			$columns[] = $col_row;
		}
		
		$primary_key = "";

		$backup_text .= "--\n"
						."-- Table structure for table `".$table."`\n"
						."--\n\n"
						."DROP TABLE IF EXISTS `".$table."`;\n"
						."CREATE TABLE IF NOT EXISTS `".$table."` (\n";
		$col_num = 0;
		$cols_to_use_null_instead_of_empty = array();
		$last_col_num = count($columns);
		foreach($columns as $key=>$col) {
			$col_num++;
			if($col['Null'] == 'Yes') $null = "NULL";
			else $null = "";
			
			if($col['Default'] != "") {
				if($col['Default'] == "CURRENT_TIMESTAMP") $default = "default CURRENT_TIMESTAMP"; // Remove quotes around CURRENT_TIMESTAMP
				else $default = "default '".$col['Default']."'";
			}
			else $default = "";
			
			$backup_text .= "	`".$col['Field']."` ".$col['Type']." ".$null." ".$col['Extra']." ".$default;
			if($col_num != $last_col_num) $backup_text .= ",\n";
			
			if($col['Key'] == 'PRI') $primary_key = $col['Field'];
			
			if($col['Type'] == 'date') $cols_to_use_null_instead_of_empty[] = $col_num;
		}
		
		if($primary_key != "") $backup_text .= ",\n	PRIMARY KEY (`".$primary_key."`)\n";
		$backup_text .= ") ENGINE=MyISAM  DEFAULT CHARSET=utf8;\n\n";
		
		$backup_text .=	"--\n"
					.	"-- Dumping data for table `".$table."`\n"
					.	"--\n\n"
					.	"INSERT INTO `".$table."` VALUES\n";
		
		//SELECT all table data
		$data_result = mysql_query("SELECT * FROM ".$table,$dbh);
		$data_row_num = 0;
		$last_data_row_num = mysql_num_rows($data_result);
		while($data_row = mysql_fetch_row($data_result)) {
			$data_row_num++;
			
			$value_num = 0;
			$last_value_num = count($data_row);
			$backup_text .= '(';
			foreach($data_row as $key=>$value) {
				$value_num++;
				if(in_array($value_num,$cols_to_use_null_instead_of_empty) && ($value == '')) $backup_text .= "NULL";
				else $backup_text .= "'".mysql_real_escape_string($value)."'";
				if($value_num < $last_value_num) $backup_text .= ", ";
			}
			if($data_row_num < $last_data_row_num) $backup_text .= "),\n";
		}
		if($data_row_num == 0) $backup_text .= "(";
		$backup_text .= ");\n\n";
	}//END loop over tables
	
	//Open new file for writing ('w')
	$filename = $todays_folder."/".$filename_base."__".$db."__".$file_date.".txt";
	if(!$fp = fopen($filename,'w')) $error = 1;
	
	//Write backup_text to the file
	if(!fwrite($fp, $backup_text)) $error = 1;
	
	//Close file
	fclose($fp);
	
	//If no errors, delete the OLD backup from last week
	if(!$error) {
		$dh = opendir($todays_folder);
		while($dir_file = readdir($dh)) {
			if((strpos($dir_file,$filename_base."__".$db."__") !== FALSE) && ($dir_file != basename($filename))) unlink($todays_folder."/".$dir_file);
		}
	}
	
	$error = 0;
	
}//END loop over databases

?>