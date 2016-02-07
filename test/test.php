<?php
require("includes/facebook/src/facebook.php");

update_facebook_wall("This is a test");

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
		$post_id = $facebook->api("/$page_id/feed","post",$args);
	} catch (FacebookApiException $e) {
		//If post failed, do nothing. Permission check happens below.
	}

	if(!$post_id) {
	// If the accessToken is no longer valid (Evan changed his password or revoked permissions),
	// then someone with admin access to the Siskiyou facebook page will need to be logged in.
		$user = $facebook->getUser();
		 
		if ($user) {
		  try {
			$page_id = $srcPageId;
			$page_info = $facebook->api("/$page_id?fields=access_token");
			if( !empty($page_info['access_token']) ) {
				$args = array(
					'access_token'  => $page_info['access_token'],
					'message'       => $text
				);
				$post_id = $facebook->api("/$page_id/feed","post",$args);
				//echo "Access Token: ".$page_info['access_token'];
			} else {
				$permissions = $facebook->api("/me/permissions");
				if( !array_key_exists('publish_stream', $permissions['data'][0]) || 
					!array_key_exists('manage_pages', $permissions['data'][0])) {
					// We don't have one of the permissions
					// Alert the admin or ask for the permission!
					header( "Location: " . $facebook->getLoginUrl(array("scope" => "publish_stream, manage_pages")) );
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
		  $loginUrl = $facebook->getLoginUrl(array('scope'=>'manage_pages,publish_stream'));
		  header( "Location: " . $loginUrl );
		}
	} // END if(!$post_id)

} //END function update_facebook_wall()
?>