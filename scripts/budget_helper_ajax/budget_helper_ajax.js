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



//--------------------------------------------------------------------------------------------
function setBudgetItemStatus(splitID, elementID, user) {

var currentImageFileArray = document.getElementById(elementID).src.split("/");
var currentImageFile = currentImageFileArray[currentImageFileArray.length-1];
var updateType = '';
var newStatus = '';

switch(currentImageFile) {
	case 'reconciled_no.png':
		updateType = 'reconciled';
		newStatus = 'checked';
		break;
	
	case 'reconciled_yes.png':
		updateType = 'reconciled';
		newStatus = 'NULL';
		break;
	
	case 'received_no.png':
		updateType = 'received';
		newStatus = 'checked';
		break;
	
	case 'received_yes.png':
		updateType = 'received';
		newStatus = 'NULL';
		break;

	case 'approved_no.png':
		updateType = 'approved';
		newStatus = user;
		break;

	case 'approved_yes.png':
		updateType = 'approved';
		newStatus = '';
		break;

}

// Set the random number to add to URL request to prevent the browser from caching the results
nocache = Math.random();
http.open('get', 'scripts/budget_helper_ajax/budget_helper_query.php?splitID='+splitID+'&elementID='+elementID+'&nocache='+nocache+'&updateType='+updateType+'&newStatus='+newStatus);

http.onreadystatechange = new Function("budgetUpdateReply('"+elementID+"')");
http.send(null);
}

function budgetUpdateReply(elementID) {
	// Change the image of the element designated by 'elementID'
	if(http.readyState == 4) {
		var response = http.responseText;
		var output = '';
		
		e = document.getElementById(elementID);
		if(response!="") {
			output = response.split(';');
			e.src = output[0];
			e.title = output[1];
		} else {
			//Do nothing
			//e.src='';
		}
	}
}
