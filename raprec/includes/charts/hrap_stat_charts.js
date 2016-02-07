
function hrap_numRappels_chart() {

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
				'FlashVars', 'library_path=includes/charts/charts_library&xml_source=includes/charts/hrap_numRappels_data.php', 
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

function hrap_buddies_chart() {

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
				'FlashVars', 'library_path=includes/charts/charts_library&xml_source=includes/charts/hrap_rapBuddies_data.php', 
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