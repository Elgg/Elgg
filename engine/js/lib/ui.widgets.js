elgg.provide('elgg.ui.widgets');

elgg.ui.widgets.init = function() {
	// COLLAPSABLE WIDGETS (on Dashboard & Profile pages)
	$('a.toggle_box_contents').live('click', elgg.ui.widgets.toggleContent);
	$('a.toggle_box_edit_panel').live('click', elgg.ui.widgets.toggleEditPanel);
	$('a.toggle_customise_edit_panel').live('click', elgg.ui.widgets.toggleCustomizeEditPanel);
	
	// WIDGET GALLERY EDIT PANEL
	// Sortable widgets
	var els = [
		'#leftcolumn_widgets',
		'#middlecolumn_widgets',
		'#rightcolumn_widgets',
		'#widget_picker_gallery'
	].join(',');
	
	$(els).sortable({
		items: '.draggable_widget',
		handle: '.drag_handle',
		forcePlaceholderSize: true,
		placeholder: 'ui-state-highlight',
		cursor: 'move',
		opacity: 0.9,
		appendTo: 'body',
		connectWith: els,
		stop: function(e,ui) {
			// refresh list before updating hidden fields with new widget order
			$(this).sortable("refresh");

			var widgetNamesLeft = outputWidgetList('#leftcolumn_widgets');
			var widgetNamesMiddle = outputWidgetList('#middlecolumn_widgets');
			var widgetNamesRight = outputWidgetList('#rightcolumn_widgets');

			$('#debugField1').val(widgetNamesLeft);
			$('#debugField2').val(widgetNamesMiddle);
			$('#debugField3').val(widgetNamesRight);
		}
	});

	// bind more info buttons - called when new widgets are created
	elgg.ui.widgets.moreinfo();
};

//List active widgets for each page column
elgg.ui.widgets.outputList = function(forElement) {
	return( $("input[name='handler'], input[name='guid']", forElement ).makeDelimitedList("value") );
};

//Read each widgets collapsed/expanded state from cookie and apply
elgg.ui.widgets.state = function(forWidget) {

	var thisWidgetState = elgg.session.cookie(forWidget);

	if (thisWidgetState == 'collapsed') {
		forWidget = "#" + forWidget;
		$(forWidget).find("div.collapsable_box_content").hide();
		$(forWidget).find("a.toggle_box_contents").html('+');
		$(forWidget).find("a.toggle_box_edit_panel").fadeOut('medium');
	}
};

//More info tooltip in widget gallery edit panel
elgg.ui.widgets.moreinfo = function() {
	$("img.more_info").hover(function(e) {
		var widgetdescription = $("input[name='description']", this.parentNode.parentNode.parentNode).val();
		$("body").append("<p id='widget_moreinfo'><b>"+ widgetdescription +" </b></p>");

		if (e.pageX < 900) {
			$("#widget_moreinfo")
				.css("top",(e.pageY + 10) + "px")
				.css("left",(e.pageX + 10) + "px")
				.fadeIn("medium");
		} else {
			$("#widget_moreinfo")
				.css("top",(e.pageY + 10) + "px")
				.css("left",(e.pageX - 210) + "px")
				.fadeIn("medium");
		}
	}, function() {
		$("#widget_moreinfo").remove();
	});
};

//Toggle widgets contents and save to a cookie
elgg.ui.widgets.toggleContent = function(e) {
	var targetContent = $('div.collapsable_box_content', this.parentNode.parentNode);
	if (targetContent.css('display') == 'none') {
		targetContent.slideDown(400);
		$(this).html('-');
		$(this.parentNode).children(".toggle_box_edit_panel").fadeIn('medium');

		// set cookie for widget panel open-state
		var thisWidgetName = $(this.parentNode.parentNode.parentNode).attr('id');
		$.cookie(thisWidgetName, 'expanded', { expires: 365 });

	} else {
		targetContent.slideUp(400);
		$(this).html('+');
		$(this.parentNode).children(".toggle_box_edit_panel").fadeOut('medium');
		// make sure edit pane is closed
		$(this.parentNode.parentNode).children(".collapsable_box_editpanel").hide();

		// set cookie for widget panel closed-state
		var thisWidgetName = $(this.parentNode.parentNode.parentNode).attr('id');
		$.cookie(thisWidgetName, 'collapsed', { expires: 365 });
	}
	return false;
};

// toggle widget box edit panel
elgg.ui.widgets.toggleEditPanel = function () {
	$(this.parentNode.parentNode).children(".collapsable_box_editpanel").slideToggle("fast");
	return false;
};

// toggle customise edit panel
elgg.ui.widgets.toggleCustomizeEditPanel = function () {
	$('#customise_editpanel').slideToggle("fast");
	return false;
};

/**
 * @deprecated Use elgg.ui.widgets.*
 */
var toggleContent =    elgg.ui.widgets.toggleContent,
    widget_moreinfo =  elgg.ui.widgets.moreinfo,
    widget_state =     elgg.ui.widgets.state,
    outputWidgetList = elgg.ui.widgets.outputList;

$(function() {
	elgg.ui.widgets.init();
});
