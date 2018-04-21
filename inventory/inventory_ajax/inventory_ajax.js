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

//var http = createObject();



//--------------------------------------------------------------------------------------------
function changeItemQty(itemID, delta) {
	// itemID	:	The database id for the current item
	// delta	:	The amount to change this item's quantity (either 1 or -1)
	
	var qty = document.getElementById('item_'+itemID+'_qty').innerHTML;
	var newQty = parseInt(qty) + parseInt(delta);
	
	// Set the random number to add to URL request to prevent the browser from caching the results
	nocache = Math.random();
	http.open('get', 'http://inventory.siskiyourappellers.com/inventory_ajax/inventory_ajax_query.php?function=change_qty&itemID='+itemID+'&nocache='+nocache+'&newQty='+newQty);
	
	http.onreadystatechange = new Function("qtyUpdateReply('"+itemID+"')");
	http.send(null);
}

function qtyUpdateReply(itemID) {
	// Change the quantity in the table cell designated by 'qtyElementID'
	// Show or Hide the minus-sign image designated by minusImgID
	if(http.readyState == 4) {
		var qty = http.responseText;
		
		var qtyElement = document.getElementById('item_'+itemID+'_qty');
		var imgElement = document.getElementById('item_'+itemID+'_minus_img');
		var rowElement = document.getElementById('item_'+itemID+'_row');
		var restockTrigger = document.getElementById('item_'+itemID+'_restock_trigger').innerHTML;
		
		if(qty != "") {
			qtyElement.innerHTML = qty;
			
			if(parseInt(qty) > parseInt(restockTrigger)) {
				rowElement.setAttribute("class", "odd");
				rowElement.setAttribute("className", "odd"); // for IE (doesn't recognize 'class')
			}
			else {
				rowElement.setAttribute("class", "low_quantity_item");
				rowElement.setAttribute("className", "low_quantity_item"); // for IE (doesn't recognize 'class')
			}
			
			if(parseInt(qty) > 0) imgElement.style.visibility = 'visible';
			else imgElement.style.visibility = 'hidden';
			
		} else {
			//Do nothing
		}
	}
}

function checkIn(itemID) {
	// Check in a piece of equipment.
	// itemID	:	The database id for the current item

	var data = {"itemID": itemID}

	showLoading("item_"+itemID+"_button"); //Show the throbber image

	$.ajax({
		type: "POST",
		dataType: "text",
		url: "http://inventory.siskiyourappellers.com/inventory_ajax/inventory_ajax_query.php?function=check_in",
		data: data,
	
		success: function(data,status,jqXHR) {
			hideLoading("item_"+itemID+"_button");	//Hide the throbber image
			$("#item_"+data+"_row").hide(500);		//Remove this row from the screen because it has been removed
		}
	});

	return false;
} //End function checkIn()

function showLoading(elementID) {
	$("#"+elementID).html("<img src=\"/images/pulse_loader.gif\" />");
}

function hideLoading(elementID) {
	$("#"+elementID).html("");
}