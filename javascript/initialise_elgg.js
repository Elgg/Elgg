$(document).ready(function () {

	// elggtoolbar - close all drawer elements on pageload
	$('li.drawer ul').hide();
	
	// elggtoolbar
	$('h2.drawer-handle').click(function () {	
		$('li.drawer ul:visible').slideUp('medium').prev().removeClass('open');
		$(this).addClass('open').next().slideDown('fast');
	return false;
	});
	
	// toggle widget box contents
	$('a.toggle_box_contents').bind('click', toggleContent);
	
	// toggle widget box edit panel
	$('a.toggle_box_edit_panel').click(function () {
		$(this.parentNode.parentNode).children("[class=collapsable_box_editpanel]").slideToggle("fast");
		return false;
    });

    
	// toggle customise edit panel
	$('a.toggle_customise_edit_panel').click(function () {
		$('div#customise_editpanel').slideToggle("fast");
		return false;
    }); 
    
    

	//////////////////
	// WIDGET GALLERY
	// sortable widgets
	var els = ['#main_widgets', '#rightsidebar_widgets', '#widget_picker_gallery'];
	var $els = $(els.toString());
	
	$els.sortable({
		items: '.draggable_widget',
		handle: '.drag_handle',
		cursor: 'move',
		revert: true,
		opacity: 1.0,
		appendTo: 'body',
		placeholder: 'placeholder',
		connectWith: els,
		start:function(e,ui) {
			// prevent droppable drop function from running when re-sorting main lists
			//$('#rightsidebar_widgets').droppable("disable");
			//$('#main_widgets').droppable("disable");	
		},
		stop: function(e,ui) {	
			// refresh list before updating hidden fields with new widget order		
			$(this).sortable( "refresh" );
			var widgetNameRight = outputWidgetList('#rightsidebar_widgets');
			var widgetNameMain = outputWidgetList('#main_widgets');
			document.getElementById('debugField1').value = widgetNameMain;
			document.getElementById('debugField2').value = widgetNameRight;
		}
	});
	
    // bind more info buttons
    // it's wrapped in a function so it can be called when new widgets are created
    widget_moreinfo();

	// setup hover class for dragged widgets
	$("#rightsidebar_widgets").droppable({
		accept: ".draggable_widget",
		hoverClass: 'droppable-hover'
	});
	$("#main_widgets").droppable({
		accept: ".draggable_widget",
		hoverClass: 'droppable-hover'
	});
   
}); /* end document ready function */












jQuery.fn.makeDelimitedList = function(elementAttribute) {
	
	var delimitedListArray = new Array();

	var listDelimiter = "::";

	// Loop over each element in the stack and add the elementAttribute to the array
	this.each(function(e) {
			var listElement = $(this);
	
			// Add the attribute value to our values array
			delimitedListArray[delimitedListArray.length] = listElement.attr(elementAttribute);
		}
	);

	// Return value list by joining the array
	return(delimitedListArray.join(listDelimiter));
}




function outputWidgetList(forElement) {
	return( $("input[@name='handler'], input[@name='guid']", forElement ).makeDelimitedList("value") );	
}




// toggle widget box contents
var toggleContent = function(e) {
	var targetContent = $('div.collapsable_box_content', this.parentNode.parentNode);
		if (targetContent.css('display') == 'none') {
			targetContent.slideDown(400);
			$(this).html('-');
			$(this.parentNode).children("[class=toggle_box_edit_panel]").fadeIn('medium');
			
		} else {
			targetContent.slideUp(400);
			$(this).html('+');
			$(this.parentNode).children("[class=toggle_box_edit_panel]").fadeOut('medium');
			// make sure edit pane is closed
			$(this.parentNode.parentNode).children("[class=collapsable_box_editpanel]").hide();
		}
	return false;
};




// widget more info button
function setupMoreInfoButton() {
	$('img.more_info').click(function () {			
		// grab widget description from hidden field			
		//var widgetdescription = $("input[@name='description']", this.parentNode.parentNode.parentNode ).attr('value');
		
		//document.getElementById('debugField3').value = widgetdescription;
													
	return false;
	}); 	
}




function widget_moreinfo() {

	$("img.more_info").hover(function(e) {										  
		
		var widgetdescription = $("input[@name='description']", this.parentNode.parentNode.parentNode ).attr('value');

		$("body").append("<p id='widget_moreinfo'><b>"+ widgetdescription +" </b></p>");
		
		if (e.pageX < 900) {
			$("#widget_moreinfo")
				.css("top",(e.pageY + 10) + "px")
				.css("left",(e.pageX + 10) + "px")
				.fadeIn("medium");	
		}	
		else {
			$("#widget_moreinfo")
				.css("top",(e.pageY + 10) + "px")
				.css("left",(e.pageX - 210) + "px")
				.fadeIn("medium");		
		}			
				
    },
	function() {
		$("#widget_moreinfo").remove();
    });	
    
	$("img.more_info").mousemove(function(e) {
		//$("#widget_moreinfo")
		//	.css("top",(e.pageY + 10) + "px")
		//	.css("left",(e.pageX + 10) + "px");
	});	
		
};





































