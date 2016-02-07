function crew_rope_retirement_cause_chart(crew) {
	// This creates a PIE CHART showing the different RETIREMENT CATEGORIES
	// and the percentage of retired ropes in each category (for one crew).
	// RETIREMENT CATEGORIES: "age", "use", "field_damage", "other_damage"
	//
	// INPUT:	crew	An integer Crew ID (from the database table `crews.id`)

	var requiredMajorVersion = 9;
	var requiredMinorVersion = 0;
	var requiredRevision = 45;
	
	if (AC_FL_RunContent == 0 || DetectFlashVer == 0) {
		alert("This page requires AC_RunActiveContent.js.");
	} else {
		var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
		if(hasRightVersion) { 
			AC_FL_RunContent(
				'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,45,0',
				'width', '250',
				'height', '250',
				'scale', 'noscale',
				'salign', 'TL',
				'bgcolor', '#777788',
				'wmode', 'opaque',
				'movie', 'includes/charts/charts',
				'src', 'includes/charts/charts',
				'FlashVars', 'library_path=includes/charts/charts_library&xml_source=includes/charts/rope_retirement_cause_data.php%3Fscope%3Dcrew%26crew%3D'+crew, 
				'id', 'my_chart',
				'name', 'my_chart',
				'menu', 'true',
				'allowFullScreen', 'true',
				'allowScriptAccess','sameDomain',
				'quality', 'high',
				'align', 'middle',
				'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
				'play', 'true',
				'devicefont', 'false'
				); 
		} else { 
			var alternateContent = 'This content requires the Adobe Flash Player. '
			+ '<u><a href=http://www.macromedia.com/go/getflash/>Get Flash</a></u>.';
			document.write(alternateContent); 
		}
	}
}

/************************************************************/
/************************************************************/
/************************************************************/
function region_rope_retirement_cause_chart(region) {
	// This creates a PIE CHART showing the different RETIREMENT CATEGORIES
	// and the percentage of retired ropes in each category (for one region).
	// RETIREMENT CATEGORIES: "age", "use", "field_damage", "other_damage"
	//
	// INPUT:	region		An integer Region ID (from the database table `crews.region`)
	
	var requiredMajorVersion = 9;
	var requiredMinorVersion = 0;
	var requiredRevision = 45;
	
	if (AC_FL_RunContent == 0 || DetectFlashVer == 0) {
		alert("This page requires AC_RunActiveContent.js.");
	} else {
		var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
		if(hasRightVersion) { 
			AC_FL_RunContent(
				'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,45,0',
				'width', '250',
				'height', '250',
				'scale', 'noscale',
				'salign', 'TL',
				'bgcolor', '#777788',
				'wmode', 'opaque',
				'movie', 'includes/charts/charts',
				'src', 'includes/charts/charts',
				'FlashVars', 'library_path=includes/charts/charts_library&xml_source=includes/charts/rope_retirement_cause_data.php%3Fscope%3Dregion%26region%3D'+region, 
				'id', 'my_chart',
				'name', 'my_chart',
				'menu', 'true',
				'allowFullScreen', 'true',
				'allowScriptAccess','sameDomain',
				'quality', 'high',
				'align', 'middle',
				'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
				'play', 'true',
				'devicefont', 'false'
				); 
		} else { 
			var alternateContent = 'This content requires the Adobe Flash Player. '
			+ '<u><a href=http://www.macromedia.com/go/getflash/>Get Flash</a></u>.';
			document.write(alternateContent); 
		}
	}
}

/************************************************************/
/************************************************************/
/************************************************************/
function crew_rope_raps_before_retirement_chart(crew) {
	// This creates a NORMALIZED BAR GRAPH showing the number of ropes (within this crew) that get retired within 5 different ranges of use.
	// The rope MUST be retired after 200 rappels (no more than 100 on one end). This chart shows the distribution
	// of rope use before retirement.
	// The 5 'bins' are : 0-39 Rappels (0%-19% of max lifespan), 40-79 Rappels (20%-39%), 80-119 Rappels (40%-59%), 120-159 (60%-79%), 160-200 (80%-100%)
	// and the percentage of retired ropes in each category (for one crew).
	// RETIREMENT CATEGORIES: "age", "use", "field_damage", "other_damage"
	//
	// INPUT:	crew	An integer Crew ID (from the database table `crews.id`)
	
	var requiredMajorVersion = 9;
	var requiredMinorVersion = 0;
	var requiredRevision = 45;
	
	if (AC_FL_RunContent == 0 || DetectFlashVer == 0) {
		alert("This page requires AC_RunActiveContent.js.");
	} else {
		var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
		if(hasRightVersion) { 
			AC_FL_RunContent(
				'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,45,0',
				'width', '250',
				'height', '250',
				'scale', 'noscale',
				'salign', 'TL',
				'bgcolor', '#777788',
				'wmode', 'opaque',
				'movie', 'includes/charts/charts',
				'src', 'includes/charts/charts',
				'FlashVars', 'library_path=includes/charts/charts_library&xml_source=includes/charts/rope_raps_before_retirement_data.php%3Fscope%3Dcrew%26crew%3D'+crew, 
				'id', 'my_chart',
				'name', 'my_chart',
				'menu', 'true',
				'allowFullScreen', 'true',
				'allowScriptAccess','sameDomain',
				'quality', 'high',
				'align', 'middle',
				'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
				'play', 'true',
				'devicefont', 'false'
				); 
		} else { 
			var alternateContent = 'This content requires the Adobe Flash Player. '
			+ '<u><a href=http://www.macromedia.com/go/getflash/>Get Flash</a></u>.';
			document.write(alternateContent); 
		}
	}
}

/************************************************************/
/************************************************************/
/************************************************************/
function region_rope_raps_before_retirement_chart(region) {
	// This creates a NORMALIZED BAR GRAPH showing the number of ropes (within this region) that get retired within 5 different ranges of use.
	// The rope MUST be retired after 200 rappels (no more than 100 on one end). This chart shows the distribution
	// of rope use before retirement.
	// The 5 'bins' are : 0-39 Rappels (0%-19% of max lifespan), 40-79 Rappels (20%-39%), 80-119 Rappels (40%-59%), 120-159 (60%-79%), 160-200 (80%-100%)
	// and the percentage of retired ropes in each category (for one crew).
	// RETIREMENT CATEGORIES: "age", "use", "field_damage", "other_damage"
	//
	// INPUT:	region	An integer Region ID (from the database table `crews.region`)
	
	var requiredMajorVersion = 9;
	var requiredMinorVersion = 0;
	var requiredRevision = 45;
	
	if (AC_FL_RunContent == 0 || DetectFlashVer == 0) {
		alert("This page requires AC_RunActiveContent.js.");
	} else {
		var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
		if(hasRightVersion) { 
			AC_FL_RunContent(
				'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,45,0',
				'width', '250',
				'height', '250',
				'scale', 'noscale',
				'salign', 'TL',
				'bgcolor', '#777788',
				'wmode', 'opaque',
				'movie', 'includes/charts/charts',
				'src', 'includes/charts/charts',
				'FlashVars', 'library_path=includes/charts/charts_library&xml_source=includes/charts/rope_raps_before_retirement_data.php%3Fscope%3Dregion%26region%3D'+region, 
				'id', 'my_chart',
				'name', 'my_chart',
				'menu', 'true',
				'allowFullScreen', 'true',
				'allowScriptAccess','sameDomain',
				'quality', 'high',
				'align', 'middle',
				'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
				'play', 'true',
				'devicefont', 'false'
				); 
		} else { 
			var alternateContent = 'This content requires the Adobe Flash Player. '
			+ '<u><a href=http://www.macromedia.com/go/getflash/>Get Flash</a></u>.';
			document.write(alternateContent); 
		}
	}
}

/************************************************************/
/************************************************************/
/************************************************************/
function rope_lifespan_chart(measurement, rope_id) {
	// This creates a PIE CHART showing how much life is left on the specified rope.
	// The pie chart will show either the time remaining (out of the max rope life of 5 years) or
	// the number of rappels remaining (out of the max of 200), depending on the MEASUREMENT specified.
	var source;
	if(measurement == 'time') source = 'includes/charts/rope_lifespan_time_data.php%3Frope_id%3D'+rope_id;
	else if(measurement == 'use') source = 'includes/charts/rope_lifespan_use_data.php%3Frope_id%3D'+rope_id;
	
	var requiredMajorVersion = 9;
	var requiredMinorVersion = 0;
	var requiredRevision = 45;
	
	if (AC_FL_RunContent == 0 || DetectFlashVer == 0) {
		alert("This page requires AC_RunActiveContent.js.");
	} else {
		var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
		if(hasRightVersion) { 
			AC_FL_RunContent(
				'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,45,0',
				'width', '250',
				'height', '250',
				'scale', 'noscale',
				'salign', 'TL',
				'bgcolor', '#777788',
				'wmode', 'opaque',
				'movie', 'includes/charts/charts',
				'src', 'includes/charts/charts',
				'FlashVars', 'library_path=includes/charts/charts_library&xml_source='+source, 
				'id', 'my_chart',
				'name', 'my_chart',
				'menu', 'true',
				'allowFullScreen', 'true',
				'allowScriptAccess','sameDomain',
				'quality', 'high',
				'align', 'middle',
				'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
				'play', 'true',
				'devicefont', 'false'
				); 
		} else { 
			var alternateContent = 'This content requires the Adobe Flash Player. '
			+ '<u><a href=http://www.macromedia.com/go/getflash/>Get Flash</a></u>.';
			document.write(alternateContent); 
		}
	}
	
}
