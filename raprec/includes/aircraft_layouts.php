<?php
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

/*	This file contains the PHP version of each aircraft layout.  There is a separate Javascript version for each aircraft also.	*/
/*	The functions: create_spotter(), create_left_rap(), and create_right_rap() are defined separately in each of the scripts	*/
/*	that call this script since each version has slightly different needs (form vs plain text).									*/

/*******************************************************************************************************************************/
/*********************************** FUNCTION: num_suffix() ********************************************************************/
/*******************************************************************************************************************************/
function num_suffix($num) {
	// $num is an integer - this function generates a string where the number has an appropriate suffix added
	// i.e. for inputs of 1, 2, 3, 4, etc this function returns '1st', '2nd', '3rd', '4th', etc
	$last_digit = substr($num,strlen($num)-1,1);
	$suffix = '';
	
	if($last_digit == '1') $suffix = 'st';
	else if($last_digit == '2') $suffix = 'nd';
	else if($last_digit == '3') $suffix = 'rd';
	else $suffix = 'th';
	
	return $num.$suffix;
}
/*******************************************************************************************************************************/
/*********************************** FUNCTIONS: bell_205() *********************************************************************/
/*******************************************************************************************************************************/
function bell_205_bench() {
	$text= "<table class=\"form_table\">\n"
			."<tr><td>&nbsp;</td>\n"
				."<td>\n" . create_spotter('center') . "</td>\n"
				."<td>&nbsp;</td></tr>\n"
			."<tr><td style=\"vertical-align:top; text-align:right;\">\n" . create_left_rap('1') . "</td>\n"	/* Stick 1 - Left Side */
				."<td rowspan=\"3\" ><img src=\"images/schematics/bell_205_bench.png\"></td>\n" 				/* Aircraft Schematic Image */
				."<td style=\"vertical-align:top;text-align:left;\">\n" . create_right_rap('1') . "</td></tr>\n"/* Stick 1 - Right Side */
			."<tr><td style=\"vertical-align:top; text-align:right;\">\n" . create_left_rap('2') . "</td>\n"	/* Stick 2 - Left Side */
				."<td style=\"vertical-align:top;text-align:left;\">\n" . create_right_rap('2') . "</td></tr>\n"/* Stick 2 - Right Side */
			."<tr><td style=\"height:150px\">&nbsp;</td><td>&nbsp;</td></tr>\n"
		."</table>\n";
	return $text;
}
function bell_205_hellhole() {
	$text= "<table class=\"form_table\">\n"
			."<tr><td>&nbsp;</td>\n"
				."<td>\n" . create_spotter('center') . "</td>\n"									/* Spotter */
				."<td>&nbsp;</td></tr>\n"
			."<tr><td>\n" . create_left_rap('1') . "</td>"											/* Stick 1 - Left Side */
				."<td rowspan=\"4\" ><img src=\"images/schematics/bell_205_hellhole.png\"></td>"	/* Aircraft Schematic Image */
				."<td>\n" . create_right_rap('1') . "</td></tr>\n"									/* Stick 1 - Right Side */
			."<tr><td>\n" . create_left_rap('2') . "</td>"											/* Stick 2 - Left Side */
				."<td>\n" . create_right_rap('2') . "</td></tr>\n"									/* Stick 2 - Right Side */
			."<tr><td>\n" . create_left_rap('3') . "</td>"											/* Stick 3 - Left Side */
				."<td>\n" . create_right_rap('3') . "</td></tr>\n"									/* Stick 3 - Right Side */
			."<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>\n"
		."</table>\n";
	return $text;
}

/*******************************************************************************************************************************/
/*********************************** FUNCTIONS: bell_206() *********************************************************************/
/*******************************************************************************************************************************/
function bell_206_bench() {
	//There is no such thing as a 'hellhole' or a 'bench' configuration for a Type 3 (Light) helicopter.
	//The 'hollhole' and 'bench' functions are provided simply for consistency in the naming convention.
	$text= "<table class=\"form_table\">\n"
			."<tr>\n"
			."<td style=\"text-align:right; height:200px;\">\n" . create_spotter('left') . "</td>\n"			/* Spotter */
			."<td rowspan=\"2\" ><img src=\"images/schematics/bell_206.png\"></td>"								/* Aircraft Schematic Image */
			."<td>&nbsp;</td></tr>\n"																			/* Leave the Upper-Right cell blank */
			."<tr><td style=\"text-align:right; vertical-align:top;\">\n" . create_left_rap('1') . "</td>"		/* Stick 1 - Left Side */
			."<td style=\"text-align:left; vertical-align:top;\">\n" . create_right_rap('1') . "</td></tr>\n"	/* Stick 1 - Right Side */
			."<tr><td colspan=\"3\" style=\"height:100px;\">&nbsp;</td></tr>\n"
		."</table>\n";
	return $text;
}
function bell_206_hellhole() {
	//There is no such thing as a 'hellhole' or a 'bench' configuration for a Type 3 (Light) helicopter.
	//The 'hollhole' and 'bench' functions are provided simply for consistency in the naming convention.
	return bell_206_bench();
}

/*******************************************************************************************************************************/
/*********************************** FUNCTIONS: bell_210() *********************************************************************/
/*******************************************************************************************************************************/
function bell_210_bench() {
	$text= "<table class=\"form_table\">\n"
			."<tr><td>&nbsp;</td>\n"
				."<td>\n" . create_spotter('center') . "</td>\n"
				."<td>&nbsp;</td></tr>\n"
			."<tr><td style=\"vertical-align:top; text-align:right;\">\n" . create_left_rap('1') . "</td>\n"	/* Stick 1 - Left Side */
				."<td rowspan=\"3\" ><img src=\"images/schematics/bell_210_bench.png\"></td>\n" 				/* Aircraft Schematic Image */
				."<td style=\"vertical-align:top;\">\n" . create_right_rap('1') . "</td></tr>\n"				/* Stick 1 - Right Side */
			."<tr><td style=\"vertical-align:top; text-align:right;\">\n" . create_left_rap('2') . "</td>\n"	/* Stick 2 - Left Side */
				."<td style=\"vertical-align:top;\">\n" . create_right_rap('2') . "</td></tr>\n"				/* Stick 2 - Right Side */
			."<tr><td style=\"height:150px\">&nbsp;</td><td>&nbsp;</td></tr>\n"
		."</table>\n";
	return $text;
}
function bell_210_hellhole() {
	$text= "<table class=\"form_table\">\n"
			."<tr><td>&nbsp;</td>\n"
				."<td>\n" . create_spotter('center') . "</td>\n"									/* Spotter */
				."<td>&nbsp;</td></tr>\n"
			."<tr><td>\n" . create_left_rap('1') . "</td>"											/* Stick 1 - Left Side */
				."<td rowspan=\"4\" ><img src=\"images/schematics/bell_210_hellhole.png\"></td>"	/* Aircraft Schematic Image */
				."<td>\n" . create_right_rap('1') . "</td></tr>\n"									/* Stick 1 - Right Side */
			."<tr><td>\n" . create_left_rap('2') . "</td>"											/* Stick 2 - Left Side */
				."<td>\n" . create_right_rap('2') . "</td></tr>\n"									/* Stick 2 - Right Side */
			."<tr><td>\n" . create_left_rap('3') . "</td>"											/* Stick 3 - Left Side */
				."<td>\n" . create_right_rap('3') . "</td></tr>\n"									/* Stick 3 - Right Side */
			."<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>\n"
		."</table>\n";
	return $text;
}

/*******************************************************************************************************************************/
/*********************************** FUNCTIONS: bell_212() *********************************************************************/
/*******************************************************************************************************************************/
function bell_212_bench() {
	$text= "<table class=\"form_table\">\n"
			."<tr><td>&nbsp;</td>\n"
			."<td>\n" . create_spotter('center') . "</td>\n"									/* Spotter */
			."<td>&nbsp;</td></tr>\n"
			."<tr><td style=\"vertical-align:top;\">\n" . create_left_rap('1') . "</td>"		/* Stick 1 - Left Side */
			."<td rowspan=\"3\" ><img src=\"images/schematics/bell_212_bench.png\"></td>"		/* Aircraft Schematic Image */
			."<td style=\"vertical-align:top;\">\n" . create_right_rap('1') . "</td></tr>\n"	/* Stick 1 - Right Side */
			."<tr><td style=\"vertical-align:top;\">\n" . create_left_rap('2') . "</td>"		/* Stick 2 - Left Side */
			."<td style=\"vertical-align:top;\">\n" . create_right_rap('2') . "</td></tr>\n"	/* Stick 2 - Right Side */
			."<tr><td style=\"height:150px\">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>\n"
		."</table>\n";
	return $text;
}
function bell_212_hellhole() {
	$text= "<table class=\"form_table\">\n"
			."<tr><td>&nbsp;</td>\n"
			."<td>\n" . create_spotter('center') . "</td>\n"									/* Spotter */
			."<td>&nbsp;</td></tr>\n"
			."<tr><td>\n" . create_left_rap('1') . "</td>"										/* Stick 1 - Left Side */
			."<td rowspan=\"4\" ><img src=\"images/schematics/bell_212_hellhole.png\"></td>"	/* Aircraft Schematic Image */
			."<td>\n" . create_right_rap('1') . "</td></tr>\n"									/* Stick 1 - Right Side */
			."<tr><td>\n" . create_left_rap('2') . "</td>"										/* Stick 2 - Left Side */
			."<td>\n" . create_right_rap('2') . "</td></tr>\n"									/* Stick 2 - Right Side */
			."<tr><td>\n" . create_left_rap('3') . "</td>"										/* Stick 3 - Left Side */
			."<td>\n" . create_right_rap('3') . "</td></tr>\n"									/* Stick 3 - Right Side */
			."<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>\n"
		."</table>\n";
	return $text;
}

/*******************************************************************************************************************************/
/*********************************** FUNCTIONS: bell_407() *********************************************************************/
/*******************************************************************************************************************************/
function bell_407_bench() {
	//There is no such thing as a 'hellhole' or a 'bench' configuration for a Type 3 (Light) helicopter.
	//The 'hollhole' and 'bench' functions are provided simply for consistency in the naming convention.
	$text= "<table class=\"form_table\">\n"
			."<tr>\n"
			."<td style=\"text-align:right;\">\n" . create_spotter('left') . "</td>\n"		/* Spotter */
			."<td rowspan=\"2\" ><img src=\"images/schematics/bell_407.png\"></td>"			/* Aircraft Schematic Image */
			."<td>&nbsp;</td></tr>\n"														/* Leave the Upper-Right cell blank */
			."<tr><td style=\"text-align:right;\">\n" . create_left_rap('1') . "</td>"		/* Stick 1 - Left Side */
			."<td style=\"text-align:left;\">\n" . create_right_rap('1') . "</td></tr>\n"	/* Stick 1 - Right Side */
			."<tr><td colspan=\"3\" style=\"height:100px;\">&nbsp;</td></tr>\n"
		."</table>\n";
	return $text;
}
function bell_407_hellhole() {
	//There is no such thing as a 'hellhole' or a 'bench' configuration for a Type 3 (Light) helicopter.
	//The 'hollhole' and 'bench' functions are provided simply for consistency in the naming convention.
	return bell_407_bench();
}

/*******************************************************************************************************************************/
/*********************************** FUNCTIONS: astar_b3() *********************************************************************/
/*******************************************************************************************************************************/
function astar_b3_bench() {
	//There is no such thing as a 'hellhole' or a 'bench' configuration for a Type 3 (Light) helicopter.
	//The 'hollhole' and 'bench' functions are provided simply for consistency in the naming convention.
	$text= "<table class=\"form_table\">\n"
			."<tr>\n"
			."<td style=\"text-align:right;\">\n" . create_spotter('left') . "</td>\n"		/* Spotter */
			."<td rowspan=\"2\" ><img src=\"images/schematics/astar_b3.png\"></td>"			/* Aircraft Schematic Image */
			."<td>&nbsp;</td></tr>\n"														/* Leave the Upper-Right cell blank */
			."<tr><td style=\"text-align:right;\">\n" . create_left_rap('1') . "</td>"		/* Stick 1 - Left Side */
			."<td style=\"text-align:left;\">\n" . create_right_rap('1') . "</td></tr>\n"	/* Stick 1 - Right Side */
			."<tr><td colspan=\"3\" style=\"height:100px;\">&nbsp;</td></tr>\n"
		."</table>\n";
	return $text;
}
function astar_b3_hellhole() {
	//There is no such thing as a 'hellhole' or a 'bench' configuration for a Type 3 (Light) helicopter.
	//The 'hollhole' and 'bench' functions are provided simply for consistency in the naming convention.
	return astar_b3_bench();
}

/*******************************************************************************************************************************/
/*********************************** FUNCTION: training_tower() ****************************************************************/
/*******************************************************************************************************************************/
function training_tower() {
	$text= "<table class=\"form_table\">\n"
			."<td style=\"width:209px;\">&nbsp;</td>\n"
			."<td><img src=\"images/schematics/tower.png\"></td>\n"
			."<td style=\"vertical-align:top;\">\n"
				. create_right_rap('1')
			."</td>\n"
		."</table>\n\n";
	return $text;
}


?>