function add_vip_menu_if_needed(func) {
	if(func == 'add') {
		name	= '';
		contact	= '';
	}
	else if(func == 'update') {
		name	= document.forms["item_info_form"].hidden_vip_name.value;
		contact	= document.forms["item_info_form"].hidden_vip_contact.value;
	}
	else {
		name	= '';
		contact	= '';
	}
	
	//Check to see if "VIP" has been selected in the 'checked_out_to' dropdown box
	//If it has, display the VIP menu
	if(document.forms["item_info_form"].checked_out_to_id.value == -2) {
		//User has selected "VIP" from the dropdown menu, ADD VIP MENU
		vip_menu =	"	VIP Name<br>"
				+	"	<input type=\"text\" name=\"vip_name\" value=\""+name+"\" style=\"width:100px;\" class=\"entry_cell\"><br><br>"
				+	"	Contact Info<br>"
				+	"	<textarea name=\"vip_contact\" rows=\"5\" cols=\"25\" class=\"entry_cell\">"+decodeURI(contact)+"</textarea>";
	}
	else {
		//User has selected something other than "VIP" from the dropdown menu, HIDE VIP MENU
		vip_menu =	"	<input type=\"hidden\" name=\"vip_name\" value=\"\">"
				+	"	<input type=\"hidden\" name=\"vip_contact\" value=\"\">";
	}
	writit(vip_menu,'vip_menu_div');
	
}// End function add_vip_menu_if_needed()

function add_vip_menu_if_needed2(name,contact) {
	if(name == null)	name	= '';
	if(contact == null)	contact	= '';
	
	//Check to see if "VIP" has been selected in the 'checked_out_to' dropdown box
	//If it has, display the VIP menu
	if(document.forms["item_info_form"].checked_out_to_id.value == -2) {
		//User has selected "VIP" from the dropdown menu, ADD VIP MENU
		vip_menu =	"	VIP Name<br>"
				+	"	<input type=\"text\" name=\"vip_name\" value=\""+unescape(name)+"\" style=\"width:100px;\" class=\"entry_cell\"><br><br>"
				+	"	Contact Info<br>"
				+	"	<textarea name=\"vip_contact\" rows=\"5\" cols=\"25\" class=\"entry_cell\">"+unescape(contact.replace(/\\n/g, "\n"))+"</textarea>";
	}
	else {
		//User has selected something other than "VIP" from the dropdown menu, HIDE VIP MENU
		vip_menu =	"	<input type=\"hidden\" name=\"vip_name\" value=\"\">"
				+	"	<input type=\"hidden\" name=\"vip_contact\" value=\"\">";
	}
	writit(vip_menu,'vip_menu_div');
	
}// End function add_vip_menu_if_needed()

function writit(text,id)
{
	if (document.getElementById)
	{
		x = document.getElementById(id);
		x.innerHTML = '';
		x.innerHTML = text;
	}
	else if (document.all)
	{
		x = document.all[id];
		x.innerHTML = text;
	}
}// End function writit()

function test() {
	alert('testing function');
}
