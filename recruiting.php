<?php
	include_once("classes/mydb_class.php");
	
	$query = "SELECT name, date, text FROM job_vacancies ORDER BY date DESC LIMIT 1";
	if($result = mydb::cxn()->query($query)) {
		$row = $result->fetch_assoc();
		$page_content = $row['text'];
	}




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>Recruiting :: Siskiyou Rappel Crew</title>
<?php include("includes/basehref.html"); ?>
<meta name="Author" content="Evan Hsu" />
<meta name="Keywords" content="fire, wildland, firefighting, suppression, helicopter, aviation, fire management, oregon, helitack, hecm, crew, grants pass, merlin" />
<meta name="Description" content="Recruitment Information: Job announcement numbers, job description, and contact information." />

<link rel="stylesheet" type="text/css" href="styles/main_style.css" />
<link rel="stylesheet" type="text/css" href="styles/menu.css" />

</head>


<body>

<div id="wrapper">

	<div id="banner">
        <a href="index.php"><img src="images/banner_index2.jpg" style="border:none" /></a>
        <div id="banner_text_bg" style="background: url(images/banner_text_bg2.jpg) no-repeat;">Siskiyou Rappel Crew - Recruiting</div>
    </div>

	<?php include("includes/menu.php"); ?>

    <div id="content">
    <br />
    <hr />
    <div style="width:80%; border:none; font-style:italic; margin:0 auto 0 auto; text-align:justify; padding: 25px 40px 25px 40px; background:url(images/quotes.png) no-repeat;">
        The <span class="highlight1">Siskiyou Rappel Crew</span> is looking for motivated, physically fit candidates who can work well both
        independently and in a team environment. We are a group of highly trained wildland firefighters that are aerially
        inserted into remote areas to conduct initial attack and extended attack operations that threaten life and property. We provide
        qualified personnel and skilled leaders to other U.S. Forest Service rappel bases and overhead management teams throughout the
        fire season. Rappelling requires a high level of personal motivation, individual responsibility and initiative. Being physically
        and mentally fit is not just a recommendation; it is paramount to our success as a professional firefighting organization.<br />
	</div>

        <br />
	<div style="width:80%; border:2px solid #666666; margin:0 auto 0 auto; text-align:justify; padding: 10px; background:url(images/quotes.png) no-repeat;">
	<?php print $page_content; ?>

	</div>
    	<br />
        <h2>The Selection Process:</h2><br />
        Each season, the Siskiyou Rappel Crew seeks quality employees to fill crew member positions for duty as aerial delivered wildland
        firefighters. Before being considered for a position, applicants must meet these basic minimum requirements: be 18 years of age
        at time of hire, provide proof of U.S. Citizenship, have at least one season (3 months or more) of experience where their
        primary duty was as a wildland firefighter, and complete the Work Capacity Test (WCT) within the specified time for arduous
        duty. Qualifications beyond basic wildland firefighter, such as FFT1, ICT5, CFAL, EMT, etc., are not required but
        are desirable. Previous recreational rappel experience is not required nor is it advantageous.<br />
        Occasionally, one or more of the permanent positions on the crew becomes available. These positions are targeted
        for experienced helicopter crewmembers with a background in fire management and aviation.<br />

        <br />

        <h2>Pay Scale:</h2><br />
        Most crew members qualify as a GS-462-4 with an hourly wage of approximately $13.68. Overtime is paid for duty over 8 hours
        in a day at a rate of 1.5 times the base wage. For hazardous duty (which includes fire suppression activities, low level recon,
        and a variety of other helicopter related operations) employees are compensated an additional 25% of their base wage for all
        hours worked during that day. The Siskiyou Rappel Crew averages approximately 600 hours of overtime each fire season.<br />
        <br />


        <h2>How To Apply:</h2><br />

		All hiring is processed through the Federal Government&#39;s Official Job Website:

        <ul>
        	<li><a href="http://www.usajobs.gov/" style="font-weight:bold">www.usajobs.gov</a><br />
        		You must have a <a href="https://my.usajobs.gov/Account/Account">user account</a> with USA Jobs in order to apply, but
                you can browse job listings without one.</li><br />
        	<li><a href="assets/HowtoApplyforaJob.pdf" style="font-weight:bold">How To Apply For A Job On The USAJobs Website</a><br />
            	This document will walk you through the general application process on USAJobs.  Take a peek.</li>
        </ul>
        Job offers for temporary, detail and apprentice positions are typically made in January or February. Offers for permanent positions
        could occur at any time - check the 'Current Vacancies' section below.<br />
		<br />
		If you have any questions regarding the job description, application process or web site, please feel free to
        <a href="contact.php" style="font-weight:bold">contact us</a>.<br />
        Due to the volume of applications you are also highly encouraged to stop by the base (<a href="contact.php">map</a>).

		<br /><br />
        <hr />


	</div><!-- end 'content'-->
</div><!-- end 'wrapper'-->

<?php include("includes/footer.html"); ?>
<?php include("includes/google_analytics.html"); ?>
</body>
</html>
