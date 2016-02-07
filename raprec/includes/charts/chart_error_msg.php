<?php
//	This function will display a blank chart with the specified error message.
//	This function should be called when the intended chart contains no data.

function chart_error_msg($error_msg) {
	$output	="<draw><text transition='slide_left'
					delay='0.5'
					duration='1'
					x='0'
					y='50' 
					width='250'  
					height='15' 
					h_align='center' 
					v_align='top' 
					rotation='0' 
					size='15' 
					color='bbbbbb' 
					alpha='100'
					>".$error_msg."</text></draw>\n";
					
	$data		="<chart_data><row><null/><string>junk</string></row><row><null/><number>0</number></row></chart_data>\n";
	
	$legend			="<legend x='300' y='300' />\n";
									
	echo "<chart>\n<chart_type>pie</chart_type>\n".$legend.$data.$output."</chart>\n";
}

?>