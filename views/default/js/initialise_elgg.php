$(document).ready(function () {
	
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

	// toggle plugin's more info on admin tools admin
	$('a.manifest_details').click(function () {
		$(this.parentNode.parentNode).children("[class=manifest_file]").slideToggle("fast");
		return false;
	});
  
	//////////////////
	// WIDGET GALLERY
	// sortable widgets
	var els = ['#leftcolumn_widgets', '#middlecolumn_widgets', '#rightcolumn_widgets', '#widget_picker_gallery' ];
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
			//$('#middlecolumn_widgets').droppable("disable");
			//$('#leftcolumn_widgets').droppable("disable");	
		},
		stop: function(e,ui) {	
			// refresh list before updating hidden fields with new widget order		
			$(this).sortable( "refresh" );
			
			var widgetNamesLeft = outputWidgetList('#leftcolumn_widgets');
			var widgetNamesMiddle = outputWidgetList('#middlecolumn_widgets');
			var widgetNamesRight = outputWidgetList('#rightcolumn_widgets');
			
			document.getElementById('debugField1').value = widgetNamesLeft;
			document.getElementById('debugField2').value = widgetNamesMiddle;
			document.getElementById('debugField3').value = widgetNamesRight;
			
		}
	});
	
    // bind more info buttons
    // it's wrapped in a function so it can be called when new widgets are created
    widget_moreinfo();

	// setup hover class for dragged widgets
	$("#rightcolumn_widgets").droppable({
		accept: ".draggable_widget",
		hoverClass: 'droppable-hover'
	});
	$("#middlecolumn_widgets").droppable({
		accept: ".draggable_widget",
		hoverClass: 'droppable-hover'
	});
	$("#leftcolumn_widgets").droppable({
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


// widget panel collapsed/expanded state
function widget_state(forWidget) {

	var thisWidgetState = $.cookie(forWidget);

	if (thisWidgetState == 'collapsed') {
	
		forWidget = "#" + forWidget;
		
		$(forWidget).find("div.collapsable_box_content").hide();
		$(forWidget).find("a.toggle_box_contents").html('+');
		$(forWidget).find("a.toggle_box_edit_panel").fadeOut('medium');
	};	
}


// toggle widget box contents
var toggleContent = function(e) {
	var targetContent = $('div.collapsable_box_content', this.parentNode.parentNode);
		if (targetContent.css('display') == 'none') {
			targetContent.slideDown(400);
			$(this).html('-');
			$(this.parentNode).children("[class=toggle_box_edit_panel]").fadeIn('medium');
			
			// set cookie for widget panel open-state
			var thisWidgetName = $(this.parentNode.parentNode.parentNode).attr('id');
			$.cookie(thisWidgetName, 'expanded', { expires: 365 });
			
			
		} else {
			targetContent.slideUp(400);
			$(this).html('+');
			$(this.parentNode).children("[class=toggle_box_edit_panel]").fadeOut('medium');
			// make sure edit pane is closed
			$(this.parentNode.parentNode).children("[class=collapsable_box_editpanel]").hide();
			
			// set cookie for widget panel closed-state
			var thisWidgetName = $(this.parentNode.parentNode.parentNode).attr('id');
			$.cookie(thisWidgetName, 'collapsed', { expires: 365 });			
		}
	return false;
};




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


jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};
