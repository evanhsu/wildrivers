/* ---------------------------- */
/* XMLHTTPRequest Enable 		*/
/* ---------------------------- */
function createObject() {
	var request_type;
	var browser = navigator.appName;
	if(browser == "Microsoft Internet Explorer"){
	request_type = new ActiveXObject("Microsoft.XMLHTTP");
	} else{
		request_type = new XMLHttpRequest();
	}
		return request_type;
}

var http = createObject();

/* -------------------------- */
/* SEARCH					 */
/* -------------------------- */
function autosuggest(return_field, search_type) {

q = document.getElementById(return_field + '_text').value;
setFocus(return_field);

// Set the random number to add to URL request to prevent the browser from caching the results
nocache = Math.random();
http.open('get', 'scripts/searchautosuggest/lib/search.php?q='+q+'&nocache='+nocache+'&return_field='+return_field+'&search_type='+search_type);
http.onreadystatechange = new Function("autosuggestReply('"+return_field+"')");
http.send(null);
}

function autosuggestReply(return_field) {
	if(http.readyState == 4) {
		var response = http.responseText;
		e = document.getElementById(return_field+'_results');
		if(response!="") {
			e.innerHTML=response;
			e.style.display="block";
		} else {
			e.style.display="none";
		}
	}
}

function populate_autosuggest_form_fields(return_field_base_name, return_id, return_text) {
	document.getElementById(return_field_base_name+'_id').value = return_id;
	document.getElementById(return_field_base_name+'_text').value = return_text;
	setBlur(return_field_base_name);
	if(return_field_base_name.substring(0,5) == 'name_') getHeadshotFromID(return_field_base_name.substring(5));
}

function setFocus(return_field) {
	document.getElementById(return_field+'_results').className = "results";
	//alert("Setting Focus to "+return_field+'_results');
}

function setBlur(return_field) {
	document.getElementById(return_field+'_results').className = "results_hidden";
}

function getHeadshotFromID(suffix) {
	var search_type = 'headshot';
	var hrap_id = document.getElementById('name_'+suffix+'_id').value;
	var return_field_img = suffix+'_headshot';
	var return_field_text= suffix+'_headshot_filename';
	
	nocache = Math.random();
	http.open('get', 'scripts/searchautosuggest/lib/search.php?q='+hrap_id+'&nocache='+nocache+'&search_type='+search_type);
	http.onreadystatechange = new Function("insert_headshot('"+return_field_img+"');");
	http.send(null);
}

function insert_headshot(return_field) {
	if(http.readyState == 4) {
		var response = http.responseText;
		e = document.getElementById(return_field);
		f = document.getElementById(return_field+'_filename');
		
		e.src = response;
		f.value = response;
	}
}
