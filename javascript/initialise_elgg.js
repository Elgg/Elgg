

$(document).ready(function () {

	// close all drawer elements
	$('li.drawer ul').hide();
	
	// register click handler for elggtoolbar and define onclick function
	$('h2.drawer-handle').click(function () {	
		$('li.drawer ul:visible').slideUp('medium').prev().removeClass('open');
		$(this).addClass('open').next().slideDown('fast');
	return false;
	});
	
	// register click function for toggling box contents
	$('a.toggle_box_contents').bind('click', toggleContent);
	
	// click function for box contents edit panel
	$('a.toggle_box_edit_panel').click(function () {
		$(this.parentNode.parentNode).children("[class=collapsable_box_editpanel]").slideToggle("fast");
		return false;
    });
    
	// click function for customise edit panel
	$('a.toggle_customise_edit_panel').click(function () {
		$('div#customise_editpanel').slideToggle("fast");
		return false;
    });
 
 	// temp. function to close panel on save
	$('input.submit_button').click(function () {
		$('div#customise_editpanel').slideToggle("fast");
		return false;
    });       
    
	// click function for customise panel - remove widget
	$('img.remove_me').click(function () {
		$(this.parentNode.parentNode.parentNode.parentNode.parentNode).fadeOut("medium");
		return false;
    });
    
	var els = ['#widget_picker_gallery', '#main_widgets', '#rightsidebar_widgets'];
	var $els = $(els.toString());
	
	$els.sortable({
		items: '.draggable_widget',
		handle: '.drag_handle',
		cursor: 'move',
		revert: true,
		opacity: 0.8,
		appendTo: 'body',
		placeholder: 'placeholder',
		connectWith: els
	});
    
});

// toggle box content
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
