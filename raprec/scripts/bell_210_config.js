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
function bell_210_bench() {
	
/* Bell 210 - Bench Configuration */
var full_text= "<table class=\"form_table\">\n"
				+"<tr><td>&nbsp;</td>\n"
				+"<td>\n" + create_spotter('center') + "</td>\n"
				+"<td>&nbsp;</td></tr>\n"

				/* Stick 1 - Left Side */
				+"<tr><td style=\"vertical-align:top;\">\n" + create_left_rap('1') + "</td>"
	
				/* Aircraft Schematic Image */
				+"<td rowspan=\"3\" ><img src=\"images/schematics/bell_210_bench.png\"></td>"
	
				/* Stick 1 - Right Side */
				+"<td style=\"vertical-align:top;\">\n" + create_right_rap('1') + "</td></tr>\n"
	
				/* Stick 2 - Left Side */
				+"<tr><td style=\"vertical-align:top;\">\n" + create_left_rap('2') + "</td>"
	
				/* Stick 2 - Right Side */
				+"<td style=\"vertical-align:top;\">\n" + create_right_rap('2') + "</td></tr>\n"
					
				+"<tr><td style=\"height:150px\">&nbsp;</td><td>&nbsp;</td></tr>\n"
				+"</table>\n";

return full_text;
}

function bell_210_hellhole() {
	
/* Bell 210 - Hellhole Configuration */
var full_text= "<table class=\"form_table\">\n"
				+"<tr><td>&nbsp;</td>\n"
				+"<td>\n" + create_spotter('center') + "</td>\n"
				+"<td>&nbsp;</td></tr>\n"

				/* Stick 1 - Left Side */
				+"<tr><td>\n" + create_left_rap('1') + "</td>"
	
				/* Aircraft Schematic Image */
				+"<td rowspan=\"4\" ><img src=\"images/schematics/bell_210_hellhole.png\"></td>"
	
				/* Stick 1 - Right Side */
				+"<td>\n" + create_right_rap('1') + "</td></tr>\n"
	
				/* Stick 2 - Left Side */
				+"<tr><td>\n" + create_left_rap('2') + "</td>"
	
				/* Stick 2 - Right Side */
				+"<td>\n" + create_right_rap('2') + "</td></tr>\n"
	
				/* Stick 3 - Left Side */
				+"<tr><td>\n" + create_left_rap('3') + "</td>"
	
				/* Stick 3 - Right Side */
				+"<td>\n" + create_right_rap('3') + "</td></tr>\n"
					
				+"<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>\n"
				+"</table>\n";

return full_text;
}