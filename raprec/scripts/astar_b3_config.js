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
function astar_b3_hellhole() {

/* Astar B3 - Standard Configuration */
/* The function includes a configuration for consistency within the naming convention.  There is no difference between
	The 'hellhole' and 'bench' configurations for a Light (Type 3) helicopter	*/
	
			/* Spotter */
var full_text= "<table class=\"form_table\">\n"
				+"<tr>\n"
				+"<td style=\"text-align:right; height:200px;\">\n" + create_spotter('left') + "</td>\n"

				/* Aircraft Schematic Image */
				+"<td rowspan=\"2\" ><img src=\"images/schematics/astar_b3.png\"></td>"
				+"<td>&nbsp;</td></tr>\n" // Leave the Upper-Right cell blank

				/* Stick 1 - Left Side */
				+"<tr><td style=\"text-align:right; vertical-align:top;\">\n" + create_left_rap('1') + "</td>"
			
				/* Stick 1 - Right Side */
				+"<td style=\"text-align:left; vertical-align:top;\">\n" + create_right_rap('1') + "</td></tr>\n"

				+"<tr><td colspan=\"3\" style=\"height:100px;\">&nbsp;</td></tr>\n"
			+"</table>\n";

return full_text;
}

/*-------------------------------------------------------------------------------------------------------------------*/

function astar_b3_bench() {
	return astar_b3_hellhole();
}