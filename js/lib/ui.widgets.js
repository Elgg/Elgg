elgg.provide('elgg.ui.widgets');

/**
 * Widgets initialization
 *
 * @return void
 */
elgg.ui.widgets.init = function() {

	// widget layout?
	if ($(".widget-column").length == 0) {
		return;
	}

	$(".widget-column").sortable({
		items:                'div.widget',
		connectWith:          '.widget-column',
		handle:               'div.drag-handle',
		forcePlaceholderSize: true,
		placeholder:          'widget-placeholder',
		opacity:              0.8,
		revert:               500,
		stop:                 elgg.ui.widgets.move
	});

	$('#widget-add-button a').bind('click', function(event) {
		$('.widgets-add-panel').slideToggle('medium');
		event.preventDefault();
	});

	$('.widgets-add-panel li.widget-available').click(elgg.ui.widgets.add);
	$('a.widget-delete-button').click(elgg.ui.widgets.remove);
	$('a.widget-edit-button').click(elgg.ui.widgets.editToggle);
	$('.widget-edit > form ').submit(elgg.ui.widgets.saveSettings);
	$('a.widget-collapse-button').click(elgg.ui.widgets.collapseToggle);

	elgg.ui.widgets.equalHeight(".widget-column");
};

/**
 * Adds a new widget
 *
 * Makes Ajax call to persist new widget and inserts the widget html
 *
 * @param {Object} event
 * @return void
 */
elgg.ui.widgets.add = function(event) {
	// widget-type-<type>
	var type = $(this).attr('id');
	type = type.substr(type.indexOf('widget-type-') + "widget-type-".length);

	// if multiple instances not allow, disable this widget type add button
	var multiple = $(this).attr('class').indexOf('widget-multiple') != -1;
	if (multiple == false) {
		$(this).addClass('widget-unavailable');
		$(this).removeClass('widget-available');
		$(this).unbind('click', elgg.ui.widgets.add);
	}

	elgg.action('widgets/add', {
		data: {
			handler: type,
			user_guid: elgg.get_loggedin_userid(),
			context: $("input[name='widget-context']").val()
		},
		success: function(json) {
			$('#widget-col-1').prepend(json.output);
			var $widget = $('#widget-col-1').children(":first");
			$widget.find('a.widget-delete-button').click(elgg.ui.widgets.remove);
			$widget.find('a.widget-edit-button').click(elgg.ui.widgets.editToggle);
			$widget.find('a.widget-collapse-button').click(elgg.ui.widgets.collapseToggle);
			$widget.find('.widget-edit > form ').submit(elgg.ui.widgets.saveSettings);
		}
	});
	event.preventDefault();
}

/**
 * Persist the widget's new position
 *
 * @param {Object} event
 * @param {Object} ui
 *
 * @return void
 */
elgg.ui.widgets.move = function(event, ui) {

	// widget-<guid>
	var guidString = ui.item.attr('id');
	guidString = guidString.substr(guidString.indexOf('widget-') + "widget-".length);

	// widget-col-<column>
	var col = ui.item.parent().attr('id');
	col = col.substr(col.indexOf('widget-col-') + "widget-col-".length);

	elgg.action('widgets/move', {
		data: {
			guid: guidString,
			column: col,
			position: ui.item.index()
		}
	});

	// @hack fixes jquery-ui/opera bug where draggable elements jump
	ui.item.css('top', 0);
	ui.item.css('left', 0);
}

/**
 * Removes a widget from the layout
 *
 * Event callback the uses Ajax to delete the widget and removes its HTML
 *
 * @param {Object} event
 * @return void
 */
elgg.ui.widgets.remove = function(event) {
	var $widget = $(this).parent().parent();

	// if widget type is single instance type, enable the add buton
	var type = $widget.attr('class');
	// widget-instance-<type>
	type = type.substr(type.indexOf('widget-instance-') + "widget-instance-".length);
	$button = $('#widget-type-' + type);
	var multiple = $button.attr('class').indexOf('widget-multiple') != -1;
	if (multiple == false) {
		$button.addClass('widget-available');
		$button.removeClass('widget-unavailable');
		$button.unbind('click', elgg.ui.widgets.add); // make sure we don't bind twice
		$button.click(elgg.ui.widgets.add);
	}

	$widget.remove();

	// widget-delete-button-<guid>
	var id = $(this).attr('id');
	id = id.substr(id.indexOf('widget-delete-button-') + "widget-delete-button-".length);

	elgg.action('widgets/delete', {
		data: {
			guid: id
		}
	});
	event.preventDefault();
}

/**
 * Toggle the edit panel of a widget
 *
 * Yes, I'm quite bad at selectors.
 *
 * @param {Object} event
 * @return void
 */
elgg.ui.widgets.editToggle = function(event) {
	$(this).parent().parent().find('.widget-edit').slideToggle('medium');
	event.preventDefault();
}

/**
 * Toogle the collapse state of the widget
 *
 * @param {Object} event
 * @return void
 */
elgg.ui.widgets.collapseToggle = function(event) {
	$(this).toggleClass('widget-collapsed');
	$(this).parent().parent().find('.widget-container').slideToggle('medium');
	event.preventDefault();
}

/**
 * Save a widget's settings
 *
 * Uses Ajax to save the settings and updates the HTML.
 *
 * @param {Object} event
 * @return void
 */
elgg.ui.widgets.saveSettings = function(event) {
	$(this).parent().slideToggle('medium');
	var $widgetContent = $(this).parent().parent().children('.widget-content');
	// @todo - change to ajax loader
	$widgetContent.html('loading');
	elgg.action('widgets/save', {
		data: $(this).serialize(),
		success: function(json) {
			$widgetContent.html(json.output);
		}
	});
	event.preventDefault();
}

/**
 * Make all elements have the same min-height
 *
 * This addresses the issue of trying to drag a widget into a column that does
 * not have any widgets.
 *
 * @param {String} selector
 * @return void
 */
elgg.ui.widgets.equalHeight = function(selector) {
	var maxHeight = 0;
	$(selector).each(function() {
		if ($(this).height() > maxHeight) {
			maxHeight = $(this).height();
		}
	})
	$(selector).css('min-height', maxHeight);
}

elgg.register_event_handler('init', 'system', elgg.ui.widgets.init);


// @todo look into removing the below functions - maybe a compatibility plugin

//List active widgets for each page column
elgg.ui.widgets.outputList = function(forElement) {
	return $("input[name='handler'], input[name='guid']", forElement).makeDelimitedList("value");
};

//Read each widgets collapsed/expanded state from cookie and apply
elgg.ui.widgets.state = function(forWidget) {
	var thisWidgetState = elgg.session.cookie(forWidget);

	if (thisWidgetState === 'collapsed') {
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
		$("body").append("<p id='widget_moreinfo'><b>" + widgetdescription + " </b></p>");

		if (e.pageX < 900) {
			$("#widget_moreinfo")
				.css("top", (e.pageY + 10) + "px")
				.css("left", (e.pageX + 10) + "px")
				.fadeIn("medium");
		} else {
			$("#widget_moreinfo")
				.css("top", (e.pageY + 10) + "px")
				.css("left", (e.pageX - 210) + "px")
				.fadeIn("medium");
		}
	}, function() {
		$("#widget_moreinfo").remove();
	});
};

//Toggle widgets contents and save to a cookie
elgg.ui.widgets.toggleContent = function(e) {
	var thisWidgetName,
		targetContent = $('div.collapsable_box_content', this.parentNode.parentNode);
	
	if (targetContent.css('display') === 'none') {
		targetContent.slideDown(400);
		$(this).html('-');
		$(this.parentNode).children(".toggle_box_edit_panel").fadeIn('medium');

		// set cookie for widget panel open-state
		thisWidgetName = $(this.parentNode.parentNode.parentNode).attr('id');
		elgg.session.cookie(thisWidgetName, 'expanded', {expires: 365});

	} else {
		targetContent.slideUp(400);
		$(this).html('+');
		$(this.parentNode).children(".toggle_box_edit_panel").fadeOut('medium');
		// make sure edit pane is closed
		$(this.parentNode.parentNode).children(".collapsable_box_editpanel").hide();

		// set cookie for widget panel closed-state
		thisWidgetName = $(this.parentNode.parentNode.parentNode).attr('id');
		elgg.session.cookie(thisWidgetName, 'collapsed', {expires: 365});
	}
	return false;
};

/**
 * @deprecated Use elgg.ui.widgets.*
 */
var toggleContent =    elgg.ui.widgets.toggleContent,
    widget_moreinfo =  elgg.ui.widgets.moreinfo,
    widget_state =     elgg.ui.widgets.state,
    outputWidgetList = elgg.ui.widgets.outputList;

