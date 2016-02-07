	$(document).ready(function() {     
		//Helper function to keep table row from collapsing when being sorted
		var fixHelperModified = function(e, tr) {         
			var $originals = tr.children();         
			var $helper = tr.clone();         
			$helper.children().each(function(index)         
			{           
				$(this).width($originals.eq(index).width())         
			});         
			return $helper;     
		};     
/*
		//Make wishlist table sortable     
		$("#wishlist_table tbody").sortable({         
			helper: fixHelperModified,         
			stop: function(event,ui) {renumber_table('#wishlist_table')}     
		}).disableSelection();     
*/
		$(function() {
		    $( "#wishlist_table tbody" ).sortable({
			    //placeholder: "ui-state-highlight",
			    revert: true,
			    cursor: "move",
			    //containment: "#frequency_list_drag_boundary",
			    helper: //function (e,elem) {
				//return $(elem).clone().css({"color":"#000000"});
			        fixHelperModified,
			    start: function (e, ui) {
				ui.placeholder.css({"font-weight":"bold", "font-size":"1.1em", "color":"#000000"})
			    },
			    stop: function(event,ui) {
				renumber_table('#wishlist_table');
			    },

			    update: function(){
				$.ajax({
				type: 'post',
				data: {ids: $("#wishlist_table").find("tr").map(
						function() {if(this.id != "") return this.id}
						).get().join(",")
					},
				//dataType: 'script',
				url: 'admin/savesort.php?list=wishlist'})
				}
		    });
});
	});
 	
	//Renumber table rows
	function renumber_table(tableID) {     
		$(tableID + " tr").each(function() {         
		count = $(this).parent().children().index($(this)) + 1;         
		$(this).find('.priority').html(count);     
	}); }


