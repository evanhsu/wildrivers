<?php

	session_start();
	require("../includes/auth_functions.php");
	
	if(($_SESSION['logged_in'] == 1) && check_access("photos")) {
		require("../scripts/connect.php");
		$dbh = connect();
		ini_set('memory_limit', '128M');
	}
	else {
		if($_SESSION['logged_in'] != 1) $_SESSION['intended_location'] = $_SERVER['PHP_SELF'];
		header('location: http://www.siskiyourappellers.com/admin/index.php');
	}

	//****************************************************************************************

	function check_uploaded_file($tmp_name) {

		$pieces = explode('.',$_FILES['uploadedfile']['name']); //Get file extension
		$ext = $pieces[sizeof($pieces)-1];

		switch ($_FILES['uploadedfile']['error']) { //Check for HTML Errors
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

		if($_FILES['uploadedfile']['size'] > $_POST['MAX_FILE_SIZE']) { //Double-check filesize
			$status['success'] = 0;
			$status['desc'] = "The file is too large.<br>\n";
		}
		elseif (strtolower($ext) != "jpg") {
			$status['success'] = 0;
			$status['desc'] = "Only JPEG images are allowed (file extension '.jpg').<br>\n";
		}

		return $status;
	} // End 'check_uploaded_file()'

	//****************************************************************************************

	function resize($orig_filename, $resized_filename, &$size) {
		
		//$watermark_filename = "../images/watermark/watermark1.gif";
		$watermark_filename = "../images/watermark/watermark.png";
		$max_photo_height = 750; //If uploaded photo is larger than this, it will be scaled down to this size
		$max_photo_width = 875; //If uploaded photo is larger than this, it will be scaled down to this size
		$max_ratio = $max_photo_width / $max_photo_height;

		$orig = imagecreatefromjpeg($orig_filename);

		$width = imagesx($orig) or die("Can't get image width");
		$height= imagesy($orig) or die("Can't get image height");
		$ratio = $width / $height;

		if(($ratio >= $max_ratio) && ($width > $max_photo_width)) { //Image is limited by width
			$new_width = $max_photo_width;
			$new_height = round($new_width / $ratio);
		}
		elseif(($ratio < $max_ratio) && ($height > $max_photo_height)){ //Image is limited by height
			$new_height = $max_photo_height;
			$new_width = round($new_height * $ratio);
		}
		else {
			//Photo does not need to be resized
			$new_width = $width;
			$new_height = $height;
		}

		$size = array('width'=>$new_width,'height'=>$new_height);
		$dst_image = imagecreatetruecolor($new_width, $new_height);

		imagecopyresampled ($dst_image,$orig,0,0,0,0,$new_width,$new_height,$width,$height);
		
		//Overlay a watermark
		$watermark_info = getimagesize($watermark_filename) or die("File not found or supported");
		switch($watermark_info[2]) {
		case 1:
			$watermark = imagecreatefromgif($watermark_filename);
			break;
		case 2:
			$watermark = imagecreatefromjpeg($watermark_filename);
			break;
		case 3:
			$watermark = imagecreatefrompng($watermark_filename);
			break;
		default:
			print_r($watermark_info);
			die("Unsupported Image Format. Please upload a JPEG.");
			break;
		}
		
		$overlay_x = $watermark_info[0];
		$overlay_y = $watermark_info[1];
		$offset_x = 0;
		$offset_y = $new_height - $overlay_y;
		
		imagealphablending($dst_image, true);
		imagealphablending($watermark, true);
		
		//imagecopymerge($dst_image, $watermark, $offset_x, $offset_y, 0, 0, $overlay_x, $overlay_y, 25);
		imagecopy($dst_image, $watermark, $offset_x , $offset_y, 0, 0, $overlay_x, $overlay_y);

		return imagejpeg($dst_image, $resized_filename, 85); //Output resized image to file with 85% jpeg quality
	} // End 'resize()'

	//****************************************************************************************
	function create_thumbnail($src_img_filename,$dst_img_filename) {

		$thumb_width = 100;
		$thumb_height= 125;
		$thumb_ratio = $thumb_width / $thumb_height; //0.8

		$src_img = imagecreatefromjpeg($src_img_filename);

		$width = imagesx($src_img) or die("Can't get image width");		//1024
		$height= imagesy($src_img) or die("Can't get image height");	//768
		$ratio = $width / $height;	//1.33333

		if($ratio >= $thumb_ratio) {
			//Image is too wide - size the height to fill the thumbnail, then crop off the sides
			$src_height = $height;
			$src_width = round($height * $thumb_ratio); //614

			$src_x = round(($width - $src_width) / 2);
			$src_y = 0;
		}
		else {
			//Image is too tall - resize the width to fill the thumbnail, then crop off top & bottom
			$src_width = $width;
			$src_height = round($width / $thumb_ratio);

			$src_y = round(($height - $src_height) / 2);
			$src_x = 0;
		}

		$dst_image = imagecreatetruecolor($thumb_width, $thumb_height);
		imagecopyresampled ($dst_image,$src_img,0,0,$src_x,$src_y,$thumb_width,$thumb_height,$src_width,$src_height);

		return imagejpeg($dst_image, $dst_img_filename, 75); //Output resized image to file with 75% jpeg quality
	}

	//****************************************************************************************
	function format_filename($filename) {

		$base_path = "../photos/"; //Folder to store all uploaded photos

		$filename = strtolower(str_replace(' ','_',$filename));//Remove whitespace from filenames & make lowercase
		$filename = str_replace('\"','',$filename);		//Remove double-quotes from filenames
		$filename = str_replace("\'",'',$filename);		//Remove single-quotes from filenames
		$filename = date("YmdHis") . $filename; //Add a timestamp to the beginning of the filename (eliminate filename conflicts).

		$target_path = array('base'=>$base_path,'filename'=>$filename);

		return $target_path;
	}

	//****************************************************************************************
	if(isset($_POST['MAX_FILE_SIZE'])) {

		$dbh = connect();
		
		$targets = format_filename(basename( $_FILES['uploadedfile']['name']));
		$target_path = $targets['base'] . $targets['filename'];

		$status = check_uploaded_file($_FILES['uploadedfile']['tmp_name']); // $status['success'] (0,1) - $status['desc'] (text)

		$size = array('width'=>0,'height'=>0); //Holds final dimensions of resized image

		if($status['success']) {

			if(!move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
			//if(!is_uploaded_file($_FILES['uploadedfile']['tmp_name'])) {
			//if(!resize($_FILES['uploadedfile']['tmp_name'], $target_path)) {

				$status['success'] = 0;
				$status['desc'] = "Unable to accept file, try again later.<br>\n";
			}
			elseif(!resize($target_path, $target_path, $size)) {//file was successfully moved onto the server
				$status['success'] = 0;
				$status['desc'] = "Unable to resize file.<br>\n";
			}
			elseif(!create_thumbnail($target_path,"../photos/thumbs/".$targets['filename'])) {
				$status['success'] = 0;
				$status['desc'] = "Unable to create thumbnail.<br>\n";
			}
			else {
				// Photo successfully uploaded, now add an entry in the database
				$result = mysql_query("insert into photos(path,thumbpath,caption,year,height,width)
										values(\"photos/".$targets['filename']."\",\"photos/thumbs/".$targets['filename']."\",\"".mysql_real_escape_string($_POST['caption'])."\",".$_POST['year'].",".$size['height'].",".$size['width'].")",$dbh)
					or die("Login failed: " . mysql_error());
			}

		}// end 'if($status['success'])'

	}// end 'if(isset($_POST['MAX_FILE_SIZE']))'

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Upload Photos :: Siskiyou Rappel Crew</title>

<?php include("../includes/basehref.html"); ?>

<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, cofms, fire management, central, oregon, helitack, hecm, crew, prineville" />
<meta name="Description" content="Upload photos (Admin Only)" />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />

</head>

<body>
<div id="wrapper">
	<div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none" alt="Scroll down..." /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Siskiyou Rappel Crew - Administrative Console</div>
    </div>

	<?php include("../includes/menu.php"); ?>

    <div id="content">
	    <br />

        <?php
			if(isset($status['success']) && ($status['success'] == 1)) {
				echo "<b>The file '". $targets['filename'] . "' has been uploaded!</b><br><br>\n";
			}
			elseif(isset($status['success']) && ($status['success'] == 0)) {
				echo "<b>" . $status['desc'] . "</b><br>\n";
			}
		?>

        <form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="hidden" name="MAX_FILE_SIZE" value="6500000" />
            
            Choose a photo to upload:<br />
            <input name="uploadedfile" type="file" /> (.jpg images only)<br /><br />
            
            Type a caption for this image (optional):<br />
            <input type="text" name="caption" style="width:300px" /><br /><br />
            
            Year:<br />
            <select name="year" style="width:75px" />
            	<?php
					$cur_year = date("Y");
					print "<option value=\"".$cur_year."\" selected >".$cur_year."</option>\n";
					for($i = $cur_year-1; $i >= 2006; $i--) print "<option value=\"".$i."\">".$i."</option>\n";
				?>
            </select><br />

            <input type="submit" value="Upload" style="background-color:#693; color:#444; border:2px solid #444; width:60px; height:25px; margin-top:10px;" />
        </form>

        <br />

        Maximum upload filesize is 6MB.<br />
        Photos will be resized for web-viewing after upload.<br />

    </div><!-- end 'content'-->

</div><!-- end 'wrapper'-->

<?php include("../includes/footer.html") ?>

</body>
</html>

