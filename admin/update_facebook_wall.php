<?php
	session_start();
	require_once('../includes/facebook/src/facebook.php');
	require_once("../includes/auth_functions.php");

	if(($_SESSION['logged_in'] == 1) && check_access("crew_status")) {
		require_once("../classes/mydb_class.php");
	}
	else {
		header('location: http://tools.siskiyourappellers.com/admin/index.php');
	}
	
	$query = "SELECT status FROM current ORDER BY date DESC LIMIT 1";
	$result = mydb::cxn()->query($query);
	$row = $result->fetch_assoc();
	
	$text = html_entity_decode($row['status']); //Convert things like &#34; to their literal characters.  Facebook will escape strings on their own.
	$base = '<[bB][rR][\s]*[/]*[\s]*>'; //Convert <br> to \n
    $pattern = '|' . $base . '|';
    if ($multiIstance === TRUE) {
        $pattern = '|([\s]*' . $base . '[\s]*)+|';
    }
	$replace = "\n";
    
    $text = preg_replace($pattern, $replace, $text);
	update_facebook_wall($text);
	
	
	
function update_facebook_wall($text) {
	
	$appId = '101183239949229';
	$appSecret = 'fd040afd3af84cc13b6a70ea602b4ca6';
	$srcPageId = '116587918401126';
	
	//Use Evan Hsu's access token because he is one of the Page Admins
	//**** The access Token will need to be updated whenever Evan changes his Facebook password
	$accessToken = 'AAABcBo8TC60BAOJGsdKapdUYfvbBOJm9jc9EHZBZBpD45mAN2poo9t7nfxtII3I7XZAxH9RMuI1AOD2BjWUEyso17nTkNVR2aEGaZC287Vqe8932fjQ9';
	
	$config = array(
		'appId' => $appId,
		'secret' => $appSecret
	);
	$facebook = new Facebook($config);
	
	$args = array(
				'access_token'  => $accessToken,
				'message'       => $text
			);
	try {
		$post_id = $facebook->api("/$srcPageId/feed","post",$args);
	} catch (FacebookApiException $e) {
		//If post failed, do nothing. Permission check happens below.
	}

	if(!$post_id) {
	// If the accessToken is no longer valid (Evan changed his password or revoked permissions),
	// then someone with admin access to the Siskiyou facebook page will need to be logged in.
		$user = $facebook->getUser();
		 
		if ($user) {
		  try {
			$page_info = $facebook->api("/$srcPageId?fields=access_token");
			if( !empty($page_info['access_token']) ) {
				$args = array(
					'access_token'  => $page_info['access_token'],
					'message'       => $text
				);
				$post_id = $facebook->api("/$srcPageId/feed","post",$args);
				//echo "Access Token: ".$page_info['access_token'];
			} else {
				$permissions = $facebook->api("/me/permissions");
				if( !array_key_exists('publish_stream', $permissions['data'][0]) || 
					!array_key_exists('manage_pages', $permissions['data'][0])) {
					// We don't have one of the permissions
					// Alert the admin or ask for the permission!
					header( "Location: " . $facebook->getLoginUrl(array("scope" => "publish_stream, manage_pages, offline_access")) );
				}
		 
			}
		  } catch (FacebookApiException $e) {
			//error_log($e);
			$user = null;
		  }
		}
		 
		// Login or logout url will be needed depending on current user state.
		if ($user) {
		  $logoutUrl = $facebook->getLogoutUrl();
		} else {
		  $loginUrl = $facebook->getLoginUrl(array('scope'=>'manage_pages,publish_stream,offline_access'));
		  header( "Location: " . $loginUrl );
		}
	} // END if(!$post_id)

	if($post_id != "") header('location: http://tools.siskiyourappellers.com/current.php');
	
} //END function update_facebook_wall()
?>