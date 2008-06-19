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
    
    
    // bind more info buttons (wrapped in a function so it can be called when new widgets are dragged/created)
    //setupMoreInfoButton();
    widget_moreinfo();
    
    // widget hover class
	$("table.draggable_widget").bind("mouseenter mouseleave", function(e){
		$(this).toggleClass("draggable_widget_over");
	});
       
    
	// remove widget button
	$('img.remove_me').click(function () {
		$(this.parentNode.parentNode.parentNode.parentNode.parentNode).fadeOut("medium", function () {
			$(this).remove();
			
			var widgetNameMain = outputWidgetList('#main_widgets');
			document.getElementById('debugField1').value = widgetNameMain;
			
			var widgetNameRight = outputWidgetList('#rightsidebar_widgets');
			document.getElementById('debugField2').value = widgetNameRight;

		  });

		return false;
    });

	// draggable, droppable, and sortable elements
	
	// sortable widgets
	var els = ['#main_widgets', '#rightsidebar_widgets'];
	var $els = $(els.toString());
	
	$els.sortable({
		items: '.draggable_widget',
		handle: '.drag_handle',
		cursor: 'move',
		revert: true,
		opacity: 0.8,
		appendTo: 'body',
		placeholder: 'placeholder',
		connectWith: els,
		start:function(e,ui) {	// prevent droppable drop function from running when resorting main lists
			$('#rightsidebar_widgets').droppable("disable");
			$('#main_widgets').droppable("disable");		
		},
		stop: function(e,ui) {			
			$(this).sortable( "refresh" );
			
			var widgetNameRight = outputWidgetList('#rightsidebar_widgets');
			var widgetNameMain = outputWidgetList('#main_widgets');
			document.getElementById('debugField1').value = widgetNameMain;
			document.getElementById('debugField2').value = widgetNameRight;
		}
	});
	
	// define draggable widgets from gallery
	$("#widget_picker_gallery .draggable_widget").draggable({
		helper: 'clone',
		containment: '#customise_editpanel',
		start: function(ev, ui) {
			$('#rightsidebar_widgets').droppable("enable");
			$('#main_widgets').droppable("enable");
		}
	});
	
	// define what happens when new widgets are dragged from the gallery 
	$("#rightsidebar_widgets").droppable({
		accept: ".draggable_widget",
		hoverClass: 'droppable-hover',
		drop: function(ev, ui) {
			$(this).append($(ui.draggable).clone() ); 
			$(this).droppable("disable");
			
			$('img.remove_me').click(function () {
					$(this.parentNode.parentNode.parentNode.parentNode.parentNode).fadeOut("medium", function () {
					$(this).remove();
					
					var widgetNameRight = outputWidgetList('#rightsidebar_widgets');
					document.getElementById('debugField2').value = widgetNameRight;
				});
			return false;
			});
			
			widget_moreinfo(); 
			
			$els.sortable( "refresh" );
			
			var widgetNameRight = outputWidgetList('#rightsidebar_widgets');
			document.getElementById('debugField2').value = widgetNameRight;
		 }
	});
	$("#main_widgets").droppable({
		accept: ".draggable_widget",
		hoverClass: 'droppable-hover',
		drop: function(ev, ui) {
			$(this).append($(ui.draggable).clone() ); 
			$(this).droppable("disable");
			
			$('img.remove_me').click(function () {
					$(this.parentNode.parentNode.parentNode.parentNode.parentNode).fadeOut("medium", function () {
					$(this).remove();
					
					var widgetNameMain = outputWidgetList('#main_widgets');
					document.getElementById('debugField1').value = widgetNameMain;
				});
			return false;
			});
			
			widget_moreinfo();
			
			$els.sortable( "refresh" );
			
			var widgetNameMain = outputWidgetList('#main_widgets');
			document.getElementById('debugField1').value = widgetNameMain;
		 }
	});

    
});


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

		$("body").append("<p id='widget_moreinfo'><b>"+ widgetdescription +" </b><br />and there is room for a slightly <br />longer description spanning <br />several lines.</p>");

		$("#widget_moreinfo")
			.css("top",(e.pageY + 10) + "px")
			.css("left",(e.pageX + 10) + "px")
			.fadeIn("medium");		
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


