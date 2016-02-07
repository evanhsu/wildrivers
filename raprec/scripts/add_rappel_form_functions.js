	/*******************************************************************************************************/
	/* Copyright (C) 2012 Evan Hsu
       Permission is hereby granted, free of charge, to any person obtaining a copy of this software
	   and associated documentation files (the "Software"), to deal in the Software without restriction,
	   including without limitation the rights to use, copy, modify, merge, publish, distribute,
	   sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is
	   furnished to do so, subject to the following conditions:

       The above copyright notice and this permission notice shall be included in all copies or
	   substantial portions of the Software.

       THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT
	   NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
	   IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
	   WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
	   SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE. */
	/********************************************************************************************************/
var form_memory_key = []; //Create an array to temporarily remember the NAME of each form field
var form_memory_val = []; //Create an array to temporarily remember the VALUE of each form field


function updateForm() {
	//Store current field values - these will be used to repopulate the form after it is rewritten
	store_form();
	
	var type = get_operation_type();
	var incident_num_row_contents = "";
	var height_and_canopy_section_contents = "";
	var pilot_and_aircraft_section_contents = "";
	var rappeller_configuration_contents = "";
	var cargo_letdown_contents = "";
	
	if (type == "operational") {
		incident_num_row_contents = use_incident_number();
		height_and_canopy_section_contents = use_height_and_canopy_section();
		pilot_and_aircraft_section_contents = use_pilot_and_aircraft_section();
		rappeller_configuration_contents = load_rappeller_configuration();
		cargo_letdown_contents = create_cargo_letdown_section();
	}
	else if(type == "proficiency_live") {
		incident_num_row_contents = hide_incident_number();
		height_and_canopy_section_contents = use_height_and_canopy_section();
		pilot_and_aircraft_section_contents = use_pilot_and_aircraft_section();
		rappeller_configuration_contents = load_rappeller_configuration();
		cargo_letdown_contents = create_cargo_letdown_section();
	}
	else if(type == "proficiency_tower") {
		incident_num_row_contents = hide_incident_number();
		height_and_canopy_section_contents = hide_height_and_canopy_section();
		pilot_and_aircraft_section_contents = clear_pilot_and_aircraft_section();
		rappeller_configuration_contents = training_tower();
	}
	else if(type == "certification_new_aircraft") {
		incident_num_row_contents = hide_incident_number();
		height_and_canopy_section_contents = use_height_and_canopy_section();
		pilot_and_aircraft_section_contents = use_pilot_and_aircraft_section();
		rappeller_configuration_contents = load_rappeller_configuration();
		cargo_letdown_contents = create_cargo_letdown_section();
	}
	else if(type == "certification_new_hrap") {
		incident_num_row_contents = hide_incident_number();
		height_and_canopy_section_contents = use_height_and_canopy_section();
		pilot_and_aircraft_section_contents = use_pilot_and_aircraft_section();
		rappeller_configuration_contents = load_rappeller_configuration();
		cargo_letdown_contents = create_cargo_letdown_section();
	}
	else {
		incident_num_row_contents = hide_incident_number();
		height_and_canopy_section_contents = use_height_and_canopy_section();
		pilot_and_aircraft_section_contents = use_pilot_and_aircraft_section();
		rappeller_configuration_contents = load_rappeller_configuration();
		cargo_letdown_contents = create_cargo_letdown_section();
	}
	
	write_everything(incident_num_row_contents, height_and_canopy_section_contents, pilot_and_aircraft_section_contents, rappeller_configuration_contents, cargo_letdown_contents);
	repopulate_form();
	disableForeignCrewInputs();
}

function get_operation_type() {
	var type = "operational";
	var operationTypeElement = document.getElementById('operation_type_memory');
	if(operationTypeElement) type = operationTypeElement.value;
	
	return type;
}

function get_aircraft_type() {
	var aircraft = "bell_205";
	var aircraftTypeElement = document.getElementById('aircraft_type_memory');
	if(aircraftTypeElement) aircraft = aircraftTypeElement.value;

	return aircraft;
}

function get_configuration() {
	var config = "bench";
	var configurationElement = document.getElementById('configuration_memory');
	if(configurationElement) config = configurationElement.value;
	
	return config;
}

function set_operation_type() {
	document.getElementById('operation_type_memory').value = document.add_rappel_form.operation_type.value;
}

function set_aircraft_type() {
	document.getElementById('aircraft_type_memory').value = document.add_rappel_form.aircraft_type.value;
}

function set_configuration() {
	document.getElementById('configuration_memory').value = document.add_rappel_form.configuration.value;
}

function load_rappeller_configuration() {
	
	var aircraft = get_aircraft_type();
	var config = get_configuration();
	var text = "";
	
	try {
		text = eval(aircraft + '_' + config + '()'); // Execute the appropriate function to build the requested Rappeller Configuration (These functions stored externally - e.g. bell_205_bench.js )
	} catch (error) {
		text = "<br><div class=\"error_msg\">That Aircraft is not currently available. Please change your selection</div><br><br>";
		text += "<div style=\"visibility:hidden; height:0px;\">" + bell_205_hellhole() + "</div>"; // Create the form so that values will be remembered, but hide it from view
	}

	return text;
}

function clear_pilot_and_aircraft_section() {
	var config = get_configuration();

	var text =	"There is no pilot or aircraft information needed for a tower rappel.\n"
				+"<input type=\"hidden\" name=\"pilot_name\" value=\"0\">\n"
				+"<input type=\"hidden\" name=\"tailnumber\" value=\"0\">\n"
				+"<input type=\"hidden\" name=\"aircraft_type\" value=\"tower\">\n"
				+"<input type=\"hidden\" name=\"configuration\" value=\""+config+"\">\n";
	return text;
}

function use_pilot_and_aircraft_section() {
	var aircraft = get_aircraft_type();
	var config = get_configuration();
	
	var text =	"<table style=\"margin:0 auto 0 auto; width:500px;\">\n"
					+"<tr><td style=\"text-align:right;width:190px\">Pilot</td><td style=\"width:10px;\">:</td><td style=\"text-align:left;\"><input type=\"text\" name=\"pilot_name\" style=\"width:100px;\"></td></tr>\n"
					+"<tr><td style=\"text-align:right;\">Aircraft Type</td><td>:</td><td style=\"text-align:left;\">\n"
							
							+"<select name=\"aircraft_type\" onChange=\"set_aircraft_type(); updateForm()\">\n";
							
	var aircraft_type_array = [];
	aircraft_type_array['bell_205'] = {'fullname':'Bell 205', 'type':2};
	aircraft_type_array['bell_206'] = {'fullname':'Bell 206', 'type':3};
	aircraft_type_array['bell_210'] = {'fullname':'Bell 210', 'type':2};
	aircraft_type_array['bell_212'] = {'fullname':'Bell 212', 'type':2};
	aircraft_type_array['bell_407'] = {'fullname':'Bell 407', 'type':3};
	aircraft_type_array['astar_b3'] = {'fullname':'Astar B3', 'type':3};
	
	// Build the 'Aircraft Type' dropdown box.
	for(i in aircraft_type_array) {
		//alert(i+' ('+aircraft_type_array[i].fullname+') type '+aircraft_type_array[i].type);
		text += "<option value=\""+ i +"\"";
		if(aircraft == i) text += " selected";
		text	+=">"+ aircraft_type_array[i].fullname +"</option>\n";
	}
	
	text += "</select>\n";
	
	var style = "";
	// If the selected aircraft is a MEDIUM (Type 2) Helicopter, provide a dropdown box to choose the configuration (hellhole / bench)
	// Otherwise, hide the configuration selection box
	if(aircraft_type_array[aircraft].type != 2) style = "style=\"visibility:hidden;\" ";
	
	text += "<select name=\"configuration\" " + style + "onChange=\"set_configuration(); updateForm();\">\n";
	
	if(config == 'hellhole') text += "<option value=\"hellhole\" " + style + "selected>Hellhole</option>\n"
									+"<option value=\"bench\" " + style + ">Bench</option>\n";
	else					text += "<option value=\"hellhole\" " + style + ">Hellhole</option>\n"
									+"<option value=\"bench\" " + style + "selected>Bench</option>\n";

	text +=	"</select>\n</td></tr>\n";

	text +="<tr><td style=\"text-align:right;\">Tailnumber</td><td>:</td><td style=\"text-align:left;\"><input type=\"text\" name=\"tailnumber\" style=\"width:50px;text-transform:uppercase;\"></td></tr>\n"
			+"</table>\n";

	return text;
}
function use_incident_number() {
	var text =	 "<tr>\n"
				+"	<td style=\"text-align:right;\">Incident #</td>\n"
				+"	<td style=\"text-align:left;\">:</td>\n"
				+"	<td style=\"text-align:left;\">\n"
				+"		<input type=\"text\" name=\"inc_1\" style=\"width:25px;text-transform:uppercase;\">-\n"
				+"		<input type=\"text\" name=\"inc_2\" style=\"width:30px;text-transform:uppercase;\">-\n"
				+"		<input type=\"text\" name=\"inc_3\" style=\"width:60px;text-transform:uppercase;\">\n"
				+"	</td>\n"
				+"</tr>\n"
				+"<tr style=\"visibility:hidden\">\n"
				+"	<td colspan=\"3\"><input type=\"hidden\" name=\"location\" value=\"\"></td>\n"
				+"</tr>\n\n";

	return text;
}
function hide_incident_number() {
	var text =	 "<tr style=\"visibility:hidden;\">\n"
				+"	<td><input type=\"hidden\" name=\"inc_1\" value=\"\"></td>\n"
				+"	<td><input type=\"hidden\" name=\"inc_2\" value=\"\"></td>\n"
				+"	<td><input type=\"hidden\" name=\"inc_3\" value=\"\"></td>\n"
				+"</tr>\n"
				+"<tr>\n"
				+"	<td style=\"text-align:right;\">Location</td>\n"
				+"	<td style=\"text-align:left;\">:</td>\n"
				+"	<td style=\"text-align:left;\">\n"
				+"		<input type=\"text\" name=\"location\" style=\"width:150px;\"></td>\n"
				+"</tr>\n";
				
	return text;
}
function use_height_and_canopy_section() {
	var text =	 "<tr><td style=\"text-align:right;\">Height<br>(feet)</td>\n"
				+"	<td style=\"vertical-align:middle;\">:</td>\n"
				+"	<td style=\"text-align:left;\"><input type=\"text\" name=\"height\" style=\"width:50px\"></td></tr>\n"
				+"<tr><td style=\"text-align:right;\">Canopy Opening<br>(sq. feet)</td>\n"
				+"	<td style=\"vertical-align:middle; text-align:left;\">:</td>\n"
				+"	<td style=\"text-align:left;\"><input type=\"text\" name=\"canopy_opening\" style=\"width:50px\"></td></tr>\n";
	
	return text;
}
function hide_height_and_canopy_section() {
	var text =	 "<tr style=\"visibility:hidden;\">\n"
				+"	<td colspan=\"3\"><input type=\"hidden\" name=\"height\" value=\"50\"></td></tr>\n"
				+"	<td colspan=\"3\"><input type=\"hidden\" name=\"canopy_opening\" value=\"0\"></td></tr>\n\n";
	
	return text;
}
function create_operation_type_menu() {
	var operation_type = get_operation_type();
	text = "<select name=\"operation_type\" onChange=\"set_operation_type(); updateForm()\">"
	
		+"<option value=\"operational\"";
		if(operation_type == "operational") text += " selected";
		text += ">Operational</option>"
		
		+"<option value=\"proficiency_live\"";
		if(operation_type == "proficiency_live") text += " selected";
		text += ">Proficiency (Helicopter)</option>"
		
		+"<option value=\"proficiency_tower\"";
		if(operation_type == "proficiency_tower") text += " selected";
		text += ">Proficiency (Tower)</option>"
		
		+"<option value=\"certification_new_aircraft\"";
		if(operation_type == "certification_new_aircraft") text += " selected";
		text += ">Certification in New Aircraft</option>"
		
		+"<option value=\"certification_new_hrap\"";
		if(operation_type == "certification_new_hrap") text += " selected";
		text += ">Certification for New HRAP</option>"
	
	+"</select>";
	
	return text;
}

function create_right_rap(stick) {
	//The input string 'stick' should be one of the following: '1', '2' or '3'
	var suffix = "stick"+stick+"_right";
	var text =	"<table style=\"margin:0 auto 0 auto; border:2px dashed #555555; width:200px;\">\n"
					+"<tr><td style=\"text-align:left;vertical-align:top;\">"
								+"<h3>"+num_suffix(stick)+" Stick</h3><br>"
								+"<img src=\"images/hrap_headshots/nobody.jpg\" id=\""+suffix+"_headshot\" style=\"border:2px solid #555555; width:75px; height:75px;\">\n"
								+"<input type=\"hidden\" id=\""+suffix+"_headshot_filename\" name=\""+suffix+"_headshot_filename\" value=\"images/hrap_headshots/nobody.jpg\"></td>\n"
								
						+"<td rowspan=\"2\" style=\"text-align:left; vertical-align:top;\">\n";
	if(stick > 1) {
		// Add a javascript to the hidden field that will copy the Rope info from the 1st stick into this
		// stick after a rappeller is selected for this stick.
		text += "Name:<br><input type=\"text\" name=\"name_"+suffix+"_text\" id=\"name_"+suffix+"_text\" value=\"\" "
				+"style=\"width:100px;margin-bottom:5px;\" "
				+"onkeyup=\"javascript:autosuggest('name_"+suffix+"','hrap_for_rappel');\" onFocus=\"this.select()\" "
				+"onChange=\"copyRopeFrom1stStick("+stick+",'right');\"><br>\n";
	}
	else {
		// If this is the 1st stick, omit the javascript to copy rope data.
		text += "Name:<br><input type=\"text\" name=\"name_"+suffix+"_text\" id=\"name_"+suffix+"_text\" value=\"\" "
				+"style=\"width:100px;margin-bottom:5px;\" "
				+"onkeyup=\"javascript:autosuggest('name_"+suffix+"','hrap_for_rappel');\" onFocus=\"this.select()\"><br>\n";
	}
	
	text += "<input type=\"hidden\" name=\"name_"+suffix+"_id\" id=\"name_"+suffix+"_id\" value=\"\">\n"
								+"<div id=\"name_"+suffix+"_results\" class=\"results\"></div>\n\n"
								
								+"Genie:<br><input type=\"text\" name=\"genie_"+suffix+"_text\" id=\"genie_"+suffix+"_text\" style=\"width:75px;\" "
											+"onkeyup=\"javascript:autosuggest('genie_"+suffix+"','genie');\"  onFocus=\"this.select()\" >"
								+"<input type=\"hidden\" name=\"genie_"+suffix+"_id\" id=\"genie_"+suffix+"_id\" value=\"\">\n"
								+"<div id=\"genie_"+suffix+"_results\" class=\"results\"></div>\n\n"
								
								+"Rope:<br><input type=\"text\" name=\"rope_"+suffix+"_text\" id=\"rope_"+suffix+"_text\" style=\"width:75px;margin-bottom:5px;\" "
											+"onkeyup=\"javascript:autosuggest('rope_"+suffix+"','rope');\"  onFocus=\"this.select()\" ><br>\n"
								+"<input type=\"hidden\" name=\"rope_"+suffix+"_id\" id=\"rope_"+suffix+"_id\" value=\"\">\n"
								+"<div id=\"rope_"+suffix+"_results\" class=\"results\"></div>\n\n"
								
								+"Rope End:<br>"
									+"A<input type=\"radio\" name=\"rope_"+suffix+"_end\" id=\"rope_"+suffix+"_end_a\" value=\"a\"> &nbsp;"
									+"B<input type=\"radio\" name=\"rope_"+suffix+"_end\" id=\"rope_"+suffix+"_end_b\" value=\"b\"></td></tr>\n\n"
									
					+"<tr><td style=\"text-align:right;\">\n"
								+"Knot:<input type=\"checkbox\" name=\"knot_"+suffix+"\" id=\"knot_"+suffix+"\" value=\"1\" tabindex=\"-1\"><br>\n"
								+"ETO:<input type=\"checkbox\" name=\"eto_"+suffix+"\" id=\"eto_"+suffix+"\" value=\"1\" tabindex=\"-1\"></td></tr>\n"	
							
					+"<tr><td colspan=\"2\" style=\"text-align:left;\">\n"
								+"Comments: <input type=\"text\" name=\"comments_"+suffix+"\" id=\"comments_"+suffix+"\" tabindex=\"-1\" style=\"width:99%\"></td></tr>\n"
				+"</table>\n";
	return text;
}

function create_left_rap(stick) {
	//The input string 'stick' should be one of the following: '1', '2' or '3'
	var suffix = "stick"+stick+"_left";

	var text =	"<table style=\"margin:0 auto 0 auto; border:2px dashed #555555; width:200px;\">\n"
					+"<tr><td rowspan=\"2\" style=\"text-align:left; vertical-align:top;\">\n";
	
	if(stick > 1) {
		// Add a javascript to the hidden field that will copy the Rope info from the 1st stick into this
		// stick after a rappeller is selected for this stick.
		text += "Name:<br><input type=\"text\" name=\"name_"+suffix+"_text\" id=\"name_"+suffix+"_text\" value=\"\" "
				+"style=\"width:100px;margin-bottom:5px;\" "
				+"onkeyup=\"javascript:autosuggest('name_"+suffix+"','hrap_for_rappel');\" onFocus=\"this.select()\" "
				+"onChange=\"copyRopeFrom1stStick("+stick+",'left');\"><br>\n";
	}
	else {
		// If this is the 1st stick, omit the javascript to copy rope data.
		text += "Name:<br><input type=\"text\" name=\"name_"+suffix+"_text\" id=\"name_"+suffix+"_text\" value=\"\" "
				+"style=\"width:100px;margin-bottom:5px;\" "
				+"onkeyup=\"javascript:autosuggest('name_"+suffix+"','hrap_for_rappel');\" onFocus=\"this.select()\"><br>\n";
	}
	
	text += "<input type=\"hidden\" name=\"name_"+suffix+"_id\" id=\"name_"+suffix+"_id\" value=\"\">\n"
								+"<div id=\"name_"+suffix+"_results\" class=\"results\"></div>\n\n"
								
								+"Genie:<br><input type=\"text\" name=\"genie_"+suffix+"_text\" id=\"genie_"+suffix+"_text\" style=\"width:75px;\" "
											+"onkeyup=\"javascript:autosuggest('genie_"+suffix+"','genie');\"  onFocus=\"this.select()\" >"
								+"<input type=\"hidden\" name=\"genie_"+suffix+"_id\" id=\"genie_"+suffix+"_id\" value=\"\">\n"
								+"<div id=\"genie_"+suffix+"_results\" class=\"results\"></div>\n\n"
								
								+"Rope:<br><input type=\"text\" name=\"rope_"+suffix+"_text\" id=\"rope_"+suffix+"_text\" style=\"width:75px;margin-bottom:5px;\" "
											+"onkeyup=\"javascript:autosuggest('rope_"+suffix+"','rope');\"  onFocus=\"this.select()\" ><br>\n"
								+"<input type=\"hidden\" name=\"rope_"+suffix+"_id\" id=\"rope_"+suffix+"_id\" value=\"\">\n"
								+"<div id=\"rope_"+suffix+"_results\" class=\"results\"></div>\n\n"
								
								+"Rope End:<br>"
									+"A<input type=\"radio\" name=\"rope_"+suffix+"_end\" id=\"rope_"+suffix+"_end_a\" value=\"a\"> &nbsp;"
									+"B<input type=\"radio\" name=\"rope_"+suffix+"_end\" id=\"rope_"+suffix+"_end_b\" value=\"b\"></td>\n\n"
								
						+"<td style=\"text-align:right;vertical-align:top;\">"
								+"<h3>"+num_suffix(stick)+" Stick</h3><br>"
								+"<img src=\"images/hrap_headshots/nobody.jpg\" id=\""+suffix+"_headshot\" style=\"border:2px solid #555555; width:75px; height:75px;\">\n"
								+"<input type=\"hidden\" id=\""+suffix+"_headshot_filename\" name=\""+suffix+"_headshot_filename\" value=\"images/hrap_headshots/nobody.jpg\"></td></tr>\n"
					+"<tr><td style=\"text-align:right;\">\n"
								+"Knot:<input type=\"checkbox\" name=\"knot_"+suffix+"\" id=\"knot_"+suffix+"\" value=\"1\" tabindex=\"-1\">\n"
								+"ETO:<input type=\"checkbox\" name=\"eto_"+suffix+"\" id=\"eto_"+suffix+"\" value=\"1\" tabindex=\"-1\"></td></tr>\n"
					+"<tr><td colspan=\"2\" style=\"text-align:left;\">\n"
								+"Comments: <input type=\"text\" name=\"comments_"+suffix+"\" id=\"comments_"+suffix+"\" tabindex=\"-1\" style=\"width:99%\"></td></tr>\n"
				+"</table>\n";
	return text;
}

function create_spotter(position) {
	// Input 'position' must be one of the following: 'left', 'center', 'right'
	// This value describes which side of the aircraft schematic the spotter's info will appear on
	var margin = '0 auto 0 auto';
	var align = 'left';
	
	if(position == 'left') {
		margin = '0 0 0 auto';
		align = 'right';
	}
	else if(position == 'right') {
		margin = '0 auto 0 0';
		align = 'left';
	}

	var text = "<table style=\"margin:"+margin+"; border:2px dashed #555555;\">\n"
					+"<tr><td colspan=\"2\" style=\"text-align:"+align+";\"><h3>Spotter</h3></td></tr>\n"
					+"<tr><td style=\"text-align:"+align+";\"><img src=\"images/hrap_headshots/nobody.jpg\" id=\"spotter_headshot\" style=\"border:2px solid #555555; width:75px;height:75px;\">\n"
					+"<input type=\"hidden\" id=\"spotter_headshot_filename\" name=\"spotter_headshot_filename\" value=\"images/hrap_headshots/nobody.jpg\"></td></tr>\n"
					+"<tr><td colspan=\"2\" style=\"text-align:"+align+";\">Name:<br><input type=\"text\" name=\"name_spotter_text\" id=\"name_spotter_text\" "
							+"style=\"width:98%;\" onkeyup=\"javascript:autosuggest('name_spotter','spotter');\" onFocus=\"this.select()\" ><br>\n"
					+"<input type=\"hidden\" name=\"name_spotter_id\" id=\"name_spotter_id\" value=\"\" >\n"
					+"<div id=\"name_spotter_results\" class=\"results\"></div>\n\n"
					+"</td></tr>\n"
				+"</table>\n";
	return text;
}

function create_cargo_letdown_section() {
	var text =	"<div id=\"letdown_section\" style=\"text-align:left; width:200px; margin:0 auto 0 auto;\">\n"
			+"				Letdown #1: "
						+" <input type=\"text\" name=\"letdown_1_text\" id=\"letdown_1_text\" style=\"width:100px;\" onkeyup=\"javascript:autosuggest('letdown_1','letdown');\" onFocus=\"this.select()\" ><br>\n"
						+" <input type=\"hidden\" name=\"letdown_1_id\" id=\"letdown_1_id\" >\n"
						+"<div id=\"letdown_1_results\" class=\"results\"></div>\n\n"
			+"				Letdown #2: "
						+" <input type=\"text\" name=\"letdown_2_text\" id=\"letdown_2_text\" style=\"width:100px;\" onkeyup=\"javascript:autosuggest('letdown_2','letdown');\" onFocus=\"this.select()\" ><br>\n"
						+" <input type=\"hidden\" name=\"letdown_2_id\" id=\"letdown_2_id\" >\n"
						+"<div id=\"letdown_2_results\" class=\"results\"></div>\n\n"
			+"				Letdown #3: "
						+" <input type=\"text\" name=\"letdown_3_text\" id=\"letdown_3_text\" style=\"width:100px;\" onkeyup=\"javascript:autosuggest('letdown_3','letdown');\" onFocus=\"this.select()\" ><br>\n"
						+" <input type=\"hidden\" name=\"letdown_3_id\" id=\"letdown_3_id\" >\n"
						+"<div id=\"letdown_3_results\" class=\"results\"></div>\n\n"
			+"				Letdown #4: "
						+" <input type=\"text\" name=\"letdown_4_text\" id=\"letdown_4_text\" style=\"width:100px;\" onkeyup=\"javascript:autosuggest('letdown_4','letdown');\" onFocus=\"this.select()\" ><br>\n"
						+" <input type=\"hidden\" name=\"letdown_4_id\" id=\"letdown_4_id\" >\n"
						+"<div id=\"letdown_4_results\" class=\"results\"></div>\n\n"
			+"				Letdown #5: "
						+" <input type=\"text\" name=\"letdown_5_text\" id=\"letdown_5_text\" style=\"width:100px;\" onkeyup=\"javascript:autosuggest('letdown_5','letdown');\" onFocus=\"this.select()\" ><br>\n"
						+" <input type=\"hidden\" name=\"letdown_5_id\" id=\"letdown_5_id\" >\n"
						+"<div id=\"letdown_5_results\" class=\"results\"></div>\n\n"
			+"				Letdown #6: "
						+" <input type=\"text\" name=\"letdown_6_text\" id=\"letdown_6_text\" style=\"width:100px;\" onkeyup=\"javascript:autosuggest('letdown_6','letdown');\" onFocus=\"this.select()\" ><br>\n"
						+" <input type=\"hidden\" name=\"letdown_6_id\" id=\"letdown_6_id\" >\n"
						+"<div id=\"letdown_6_results\" class=\"results\"></div>\n\n"
			+"		</div>\n";
	return text;
}

function copyRopeFrom1stStick(stick, side) {
	// This function will copy the rope number and rope_end from the 1st stick into the requested stick/side spot.
	// This is intended as a convenience when entering rappels, since the 2nd stick usually uses the same rope as
	// the 1st stick.  This function should be called when the 2nd stick rappeller is selected (since we don't want
	// to populate rope info for the 2nd stick until we're sure that the 2nd stick existed).
	var rope_stick_1_text = document.getElementById("rope_stick1_"+side+"_text").value;
	var rope_stick_1_id = document.getElementById('rope_stick1_'+side+'_id').value;
	var rope_stick_1_end_a = document.getElementById('rope_stick1_'+side+'_end_a').checked; // true or false
	var rope_stick_1_end_b = document.getElementById('rope_stick1_'+side+'_end_b').checked; // true or false
	
	document.getElementById('rope_stick'+stick+'_'+side+'_text').value = rope_stick_1_text;
	document.getElementById('rope_stick'+stick+'_'+side+'_id').value = rope_stick_1_id;
	if(rope_stick_1_end_a) document.getElementById('rope_stick'+stick+'_'+side+'_end_a').checked = true;
	else if(rope_stick_1_end_b) document.getElementById('rope_stick'+stick+'_'+side+'_end_b').checked = true;
	
}

function num_suffix(num) {
	// num is an integer - this function generates a string where the number has an appropriate suffix added
	// i.e. for inputs of 1, 2, 3, 4, etc this function returns '1st', '2nd', '3rd', '4th', etc
	var last_digit = num.charAt(num.length-1);
	var suffix = '';
	
	if(last_digit == '1') suffix = 'st';
	else if(last_digit == '2') suffix = 'nd';
	else if(last_digit == '3') suffix = 'rd';
	else suffix = 'th';
	
	return num+suffix;
}

function store_form() {
	var FormName = "add_rappel_form";
	
	for(i=0; i<document.forms[FormName].elements.length; i++) {
		//if(form_memory_key.indexOf(document.forms[FormName].elements[i].name) == -1) {
		if(index_of(document.forms[FormName].elements[i].name,form_memory_key) == -1) {
			switch(document.forms[FormName].elements[i].type) {
				case 'radio':
				case 'checkbox':
					if(document.forms[FormName].elements[i].checked == true) {
						form_memory_key.push(document.forms[FormName].elements[i].name); // Add this name to memory if NOT already there (avoid duplicates)
						form_memory_val.push(document.forms[FormName].elements[i].value); // Only add CHECKED radio buttons to the form_memory array
					}
					break;

				case 'select-one':
					form_memory_key.push(document.forms[FormName].elements[i].name); // Add this name to memory if NOT already there (avoid duplicates)
					form_memory_val.push(document.forms[FormName].elements[i].selectedIndex); //Store the index of the SELECTED option
					break;

				default:
					form_memory_key.push(document.forms[FormName].elements[i].name); // Add this name to memory if NOT already there (avoid duplicates)
					form_memory_val.push(document.forms[FormName].elements[i].value);
					break;
				
			} // End: switch()
		} // End: if()
	} // End: for()
}

function repopulate_form() {
	var FormName = 'add_rappel_form';
	var i; // An index in the document.FormName.elements array
	var j; // An index in the form_memory arrays
	
	for(i=0; i<document.forms[FormName].elements.length; i++) {
		//if(form_memory_key.indexOf(document.forms[FormName].elements[i].name) != -1) { // Find the current form field in the form_memory array
		if(index_of(document.forms[FormName].elements[i].name,form_memory_key) != -1) {
			//j = form_memory_key.indexOf(document.forms[FormName].elements[i].name);
			j = index_of(document.forms[FormName].elements[i].name,form_memory_key);
			//alert(document.forms[FormName].elements[i].name);
			//alert("Form: "+document.forms[FormName].elements[i].name+"; Memory: "+form_memory_key[j]+"; Value: "+form_memory_val[j]);
			switch(document.forms[FormName].elements[i].type) {
			case 'radio':
			case 'checkbox':
				if(document.forms[FormName].elements[i].value == form_memory_val[j]) document.forms[FormName].elements[i].checked = true;
				break;

			case 'select-one':
				document.forms[FormName].elements[i].options[form_memory_val[j]].selected = true;
				break;

			default:
				document.forms[FormName].elements[i].value = form_memory_val[j];
				break;
			} // End: switch()
			
		} // End: if
	} // End: for
	
	//Repopulate headshot images
	var field_names = [	'spotter_headshot',
					   	'stick1_left_headshot',	'stick1_right_headshot',
					   	'stick2_left_headshot',	'stick2_right_headshot',
						'stick3_left_headshot',	'stick3_right_headshot'	];
	for(i=0; i<field_names.length;i++) {
		if(document.getElementById(field_names[i])) {
			document.getElementById(field_names[i]).src = document.getElementById(field_names[i]+'_filename').value;
		}
	}
	
	form_memory_key = [];
	form_memory_val = [];
}

function index_of(element,inArray) {
	// Provide the 'array.indexOf' method for IE. (IE doesn't natively support this method for some reason...)
	for(var i=0; i<inArray.length; i++){
		if(inArray[i]==element){
			return i;
		}
	}
	return -1;
}

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}


function write_everything(incident_num_row_contents, height_and_canopy_section_contents, pilot_and_aircraft_section_contents, rappeller_configuration_contents, cargo_letdown_contents) {
	var text =	"	<table id=\"form_table\" class=\"form_table\" style=\"margin:0 auto 0 auto; border:3px solid #bbbbbb;\">\n"
				+"	<tr><th colspan=\"3\" style=\"text-align:left\">Operation Info</th></tr>\n"
				+"	<tr><td colspan=\"3\" style=\"text-align:center\">\n"
				+"			<table style=\"margin:0 auto 0 auto;\" border=0>\n"
				+"			<tr><td style=\"text-align:right;width:125px;\">Date</td>\n"
				+"				<td style=\"text-align:left;\">:</td>\n"
				+"				<td style=\"text-align:left;\"><input type=\"text\" name=\"date\" style=\"width:70px;\" readonly=\"readonly\" "
									+ "onFocus=\"this.select();\" onSelect=\"updateYear();\" > "
									+ "<small><a href=\"javascript:showCal('Calendar_operation_date')\">Select Date</a></small></td></tr>\n"
				+"			<tr><td style=\"text-align:right;\">Type</td>\n"
				+"				<td style=\"text-align:left;\">:</td>\n"
				+"				<td style=\"text-align:left;\">\n"
				+ 					create_operation_type_menu()
				+"				</td></tr>\n"
				+ 			incident_num_row_contents
				+			height_and_canopy_section_contents
				+"			</table></td>\n"
				+"	<tr><th colspan=\"3\" style=\"text-align:left;\">Pilot & Aircraft</th></tr>\n"
				+"	<tr><td colspan=\"3\" style=\"text-align:center\">\n" + pilot_and_aircraft_section_contents + "\n</td></tr>\n\n"
				+"	<tr><th colspan=\"3\" style=\"text-align:left;\">Rappeller Configuration</th></tr>\n"
				+"	<tr><td colspan=\"3\" style=\"text-align:center;\">\n" + rappeller_configuration_contents + "\n</td></tr>\n\n"
				+"	<tr><th colspan=\"3\" style=\"text-align:left;\" >Cargo Letdown</th></tr>\n"
				+"	<tr><td colspan=\"3\" style=\"border-bottom:2px solid #bbbbbb;\">\n" + cargo_letdown_contents + "\n</td></tr>\n\n"
				+"	<tr><td colspan=\"3\" style=\"text-align:center;\" >\n"
				+"			<input type=\"button\" value=\"Save\" class=\"form_button\" style=\"width:10em; height:2em; vertical-align:middle; font-size:1.5em;\" onClick=\"submit();\"></td></tr>\n"
				+"	</table>";

	var newdiv = document.createElement("div");
	newdiv.innerHTML = text;

	var container = document.getElementById("add_rappel_form");

	if(container.innerHTML != "") container.innerHTML = "";
	container.appendChild(newdiv);
}
