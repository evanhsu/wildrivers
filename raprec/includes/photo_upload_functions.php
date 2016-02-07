<?php

	function check_uploaded_file($tmp_name) {

		$pieces = explode('.',$_FILES['uploadedfile']['name']); //Get file extension
		$ext = $pieces[sizeof($pieces)-1];

		switch ($_FILES['uploadedfile']['error']) { //Check for HTML Errors
			case 1:
			case 2:
				$success = false;
				throw new Exception('That file is too large.');
				break;
			case 3: 
				$success = false;
				throw new Exception('The file was only partially uploaded. Try again later.');
				break; 
			case 4: 
				$success = false;
				throw new Exception('No file was uploaded.');
				break;
			default:
				$success = true;
		}

		if($_FILES['uploadedfile']['size'] > $_POST['MAX_FILE_SIZE']) { //Double-check filesize
			$success = false;
			throw new Exception('That file is too large');
		}
		elseif (strtolower($ext) != "jpg") {
			$success = false;
			throw new Exception('Only JPEG images are allowed (file extension \'.jpg\').');
		}

		return $success;
	} // End 'check_uploaded_file()'

	//****************************************************************************************

	function resize($orig_filename, $resized_filename, $type) {
		
		if($type == "hrap_headshot") {
			$max_photo_height = 100; //If uploaded photo is larger than this, it will be scaled down to this size
			$max_photo_width = 100; //If uploaded photo is larger than this, it will be scaled down to this size
		}
		elseif($type == "crew_logo") {
			$max_photo_height = 200; //If uploaded photo is larger than this, it will be scaled down to this size
			$max_photo_width = 200; //If uploaded photo is larger than this, it will be scaled down to this size
		}
		else throw new Exception('Unable to resize image: Invalid image type ('.$type.') was passed to function \'resize\' in \'photo_upload_functions.php\'.');

		$max_ratio = $max_photo_width / $max_photo_height;
		$src_img = imagecreatefromjpeg($orig_filename);

		if(!$width = imagesx($src_img)) throw new Exception('Can\'t get image width');
		if(!$height= imagesy($src_img)) throw new Exception('Can\'t get image height');
		$ratio = $width / $height;

		if(($ratio >= $max_ratio) && ($width > $max_photo_width)) { //Image is limited by width
			if($type == "hrap_headshot") {
				//Size the height to fill the thumbnail, then crop off the sides (only crop HRAP headshots)
				$src_height = $height;
				$src_width = round($height * $max_ratio);
	
				$src_x = round(($width - $src_width) / 2);
				$src_y = 0;
			}
			elseif($type == "crew_logo") {
				//Resize the image to the maximum width and scale height proportionally (maintain aspect ratio for logos)
				$new_width = $max_photo_width;
				$new_height = round($new_width / $ratio);
			}
		}
		elseif(($ratio < $max_ratio) && ($height > $max_photo_height)){ //Image is limited by height
			if($type == "hrap_headshot") {
				$src_width = $width;
				$src_height = round($width / $max_ratio);
	
				$src_y = round(($height - $src_height) / 2);
				$src_x = 0;
			}
			elseif($type == "crew_logo") {
				$new_height = $max_photo_height;
				$new_width = round($new_height * $ratio);
			}
		}
		else {
			//Photo does not need to be resized
			$new_width = $width;
			$new_height= $height;
			
			$src_width = $width;
			$src_height= $height;
			
			$src_x = 0;
			$src_y = 0;
		}

		if($type == "hrap_headshot") {
			$dst_image = imagecreatetruecolor($max_photo_width, $max_photo_height);
			imagecopyresampled ($dst_image,$src_img,0,0,$src_x,$src_y,$max_photo_width,$max_photo_height,$src_width,$src_height);
		}
		elseif ($type == "crew_logo") {
			$dst_image = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled ($dst_image,$src_img,0,0,0,0,$new_width,$new_height,$width,$height);
		}

		return imagejpeg($dst_image, $resized_filename, 85); //Output resized image to file with 85% jpeg quality
	} // End 'resize()'

	//****************************************************************************************
	function format_filename($filename, $type) {
		
		if($type == "hrap_headshot") $base_path = "/images/hrap_headshots";	//Folder to store this image
		elseif($type == "crew_logo") $base_path = "/images/crew_logos";		//Folder to store this image
		else return false;

		$filename = strtolower(str_replace(' ','_',$filename));//Remove whitespace from filenames & make lowercase
		$filename = str_replace('\"','',$filename);		//Remove double-quotes from filenames
		$filename = str_replace("\'",'',$filename);		//Remove single-quotes from filenames

		//$target_path = array('base'=>$base_path,'filename'=>$filename);
		$target_path = $base_path . "/" . $filename;
		return $target_path;
	}
?>
