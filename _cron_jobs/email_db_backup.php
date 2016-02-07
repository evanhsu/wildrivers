<?php

//$to = 'webmaster@siskiyourappellers.com';
$to = 'evan@siskiyourappellers.com';
$subject = 'Weekly dB Backup: siskiyourappellers.com';

//create a boundary string. It must be unique
//so we use the MD5 algorithm to generate a random hash
$random_hash = md5(date('r', time()));

$headers = "From: dB.Backup@siskiyourappellers.com";

//add boundary string and mime type specification
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";

//get the filename of the most recent database backup (there should only be one file in this folder, but loop anyway
$filename = "";
$path = "../db_backups/fri/";
if ($handle = opendir($path)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            $filename = $path.$file;
        }
    }
    closedir($handle);
}
//read the atachment file contents into a string,
//encode it with MIME base64,
//and split it into smaller chunks
if($filename != "") $attachment = chunk_split(base64_encode(file_get_contents($filename)));

//define the body of the message.
ob_start(); //Turn on output buffering
?>

--PHP-mixed-<?php echo $random_hash; ?> 
Content-Type: multipart/alternative; boundary="PHP-alt-<?php echo $random_hash; ?>"

--PHP-alt-<?php echo $random_hash; ?> 
Content-Type: text/plain; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

This message was sent automatically from the Siskiyou Rappel Crew backup server.
The file attachment contains the most recent backup copy of the database.  Although
a backup is performed daily, you will only receive an email copy once per week. The
un-mailed nightly backups will remain on the server for 7 days.

Database backups exist on the server at the following location:
ftp://siskiyourappellers.com/db_backups/

--PHP-alt-<?php echo $random_hash; ?> 
Content-Type: text/html; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

This message was sent automatically from the Siskiyou Rappel Crew backup server.<br />
The file attachment contains the most recent backup copy of the database.  Although
a backup is performed daily, you will only receive an email copy once per week. The
un-mailed nightly backups will remain on the server for 7 days.<br />
<br />
<a href="ftp://siskiyourappellers.com/db_backups" target="_blank">ftp://siskiyourappellers.com/db_backups</a>

--PHP-alt-<?php echo $random_hash; ?>--


--PHP-mixed-<?php echo $random_hash; ?> 
Content-Type: text/plain; name="<?php echo $filename; ?>" 
Content-Transfer-Encoding: base64 
Content-Disposition: attachment 

<?php echo $attachment; ?>
--PHP-mixed-<?php echo $random_hash; ?>--

<?php
//copy current buffer contents into $message variable and delete current output buffer
$message = ob_get_clean();

//send the email
$mail_sent = @mail( $to, $subject, $message, $headers );

//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed"
//echo $mail_sent ? "Mail sent" : "Mail failed";
?> 