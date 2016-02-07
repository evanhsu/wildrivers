<?php

require_once('includes/constants.php');

class email {

	private $type;
	private $to;
	private $headers;
	private $subject;
	private $body;
	private $xtra;

	function email($type,$to,$xtra="") {
		/* Constructor function */
		/* $type	The prewritten message body to use */
		/* $to		The recipient's email address */
		$this->headers = "Content-type: text/html\r\n";
		$this->headers	.="From: ".$_SESSION['email_sender']."\r\n";
		$root = "http://" . $_SERVER['HTTP_HOST']; // Determine the base URL for links within the email (i.e. http://www.raprec.com)
		
		if(!preg_match("/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i", $to)) throw new Exception('Email must be of the form: yourname@yourhost.com');
		
		switch($type) {
		case 'rappel_needs_verification':
			// $xtra = the URI of the rappel that needs verification
			$this->to		= $to;
			$this->subject	= "RapRec Central: Confirm Rappels";
			$this->body		= "Information affecting your crew records has been recorded by another crew.<br />\n"
							. "You must approve these records before they become official:<br /><br />\n"
							. "<a href=\"".$xtra."\">View these records now</a><br /><br />\n\n"
							. "<strong>RapRec Central</strong><br />\n"
							. "<a href=\"".$root."/raprec/index.php\">".$root."/raprec</a>\n"
							. "</body></html>\n";;
			
			return 1;
			break;
			
		case 'change_crew_admin_affiliation':
			// $xtra[0] = Verification Code for Admin approval
			// $xtra[1] = Verification Code for Crewmember-Only approval
			// $xtra[2] = User object of Requestor
			// $xtra[3] = Crew Name of Requestor
			if($xtra=="") throw new Exception('Error making Crew Admin account change: No verification code specified for verification email.');
			if(sizeof($xtra) < 4) throw new Exception('Error making Crew Admin account change: Missing Requestor information for verification email.');

			$this->to		= $to;
			$this->subject	= "RapRec Central: New Admin Request";
			$this->body		="<html><body>A Crew Administrator from ".$xtra[3]." would like to transfer affiliation to your crew. Details of this request are as follows:<br /><br />\n\n"
							."<table>\n"
							."<tr><td>Requestor Name:</td><td>".$xtra[2]->get('firstname')." ".$xtra[2]->get('lastname')."</td></tr>\n"
							."<tr><td>Requestor Crew:</td><td>".$xtra[3]."</td></tr>\n"
							."<tr><td>Requestor Email:</td><td>".$xtra[2]->get('email')."</td></tr></table><br /><br />\n\n"
							."You can approve this request in 1 of 2 ways:<br />\n"
							."<ol>\n"
							."<li><a href=\"".$root."/raprec/confirm_email.php?verification=".$xtra[0]."\">"
								."Approve this user as an Admin</a> - ".$xtra[2]->get('firstname')." ".$xtra[2]->get('lastname')." will become an Administrator on your crew.</li>\n"
							."<li><a href=\"".$root."/raprec/confirm_email.php?verification=".$xtra[1]."\">"
								."Approve this user as a Crewmember</a> - ".$xtra[2]->get('firstname')." ".$xtra[2]->get('lastname')." will become affiliated with your crew, "
								."but will not have administrative privileges.</li>\n"
							."</ol><br />\n"
							."This approval request will expire after ".$_SESSION['days_until_confirmation_expires']." days.<br />\n"
							."Please note that this email has been sent to each Crew Admin on your crew.  If you get an error that your confirmation<br />\n"
							."code is no longer valid, it may be that somebody else has aready given their approval.<br /><br />\n\n"
							. "<strong>RapRec Central</strong><br />\n"
							. "<a href=\"".$root."/raprec/index.php\">".$root."/raprec</a>\n"
							. "</body></html>\n";
			return 1;
			break;
		
		case 'password_reset':
			//$xtra = the new password
			if($xtra == "") throw new Exception('No new password was provided');
			$this->to		= $to;
			$this->subject	= "RapRec Central: Password Reset";
			$this->body		= "<html><body>Your RapRec Central password has been reset.  You can now login with the following password:<br /><br />\n"
							. "<pre style=\"font-size:1.5em\">".$xtra."</pre><br />\n\n"
							. "You can keep this password or change it - your choice.<br />\nFor best results, simply copy and paste this password while logging in.<br />\n"
							. "<strong>RapRec Central</strong><br />\n"
							. "<a href=\"".$root."/raprec/index.php\">".$root."/raprec</a>\n"
							. "</body></html>\n";
			return 1;
			break;
		
		case 'new_account':
			//$xtra = the new password
			if($xtra == "") throw new Exception('No new password was provided');
			$this->to		= $to;
			$this->subject	= "RapRec Central: Your New Account";
			$this->body		= "<html><body>Congratulations! Your RapRec Central account is now active.  Your login information is below:<br /><br />\n"
							. "<table><tr><td>Username:</td><td style=\"font-face:courier;font-size:1.5em\">".$to."</td></tr>\n"
							. "		<tr><td>Password:</td><td style=\"font-face:courier;font-size:1.5em\">".$xtra."</td></tr></table>\n\n"
							. "You can keep this password or change it - your choice.<br />\nFor best results, simply copy and paste this password while logging in.<br /><br />\n"
							. "<strong>RapRec Central</strong><br />\n"
							. "<a href=\"".$root."/raprec/index.php\">".$root."/raprec</a>\n"
							. "</body></html>\n";
			return 1;
			break;
			
		default:
			throw new Exception('Unrecognized email message type');
			break;
		} // End switch($type)

		
		//All SUCCESSFUL conditions would have 'returned' 1 before the end of the SWITCH statement.
		//Errors have thrown exceptions and terminated this function.
		//Execution should never reach this point
		return 0;
	} // End function email()
	
	
	function send() {
		if (1/*mail($this->to, $this->subject, $this->body, $this->headers)*/) return 1;
		else {
			throw new Exception('Message delivery failed');
			return 0;
		}
	} // End function send()
	
	
	
}
?>
