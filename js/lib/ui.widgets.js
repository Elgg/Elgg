elgg.provide('elgg.ui.widgets');

/**
 * Widgets initialization
 *
 * @return void
 */
elgg.ui.widgets.init = function() {
	$(".widget_column").sortable({
		items:                'div.widget',
		connectWith:          '.widget_column',
		handle:               'div.drag_handle',
		forcePlaceholderSize: true,
		placeholder:          'widget_placeholder',
		//containment:          '.widget_layout',
		opacity:              0.8,
		revert:               500,
		stop:                 function(event, ui) {
			elgg.action('widgets/move', {
				data: {
					// widget_<guid>
					guid: ui.item.attr('id').substring(7),
					// widget_col_<column>
					column: ui.item.parent().attr('id').substring(11),
					position: ui.item.index()
				}
			});
		}
	});

	$('#widget_add_button a').bind('click', function(event) {
		$('.widgets_add_panel').slideToggle('medium');
		event.preventDefault();
	});

	$('.widgets_add_panel a').bind('click', function(event) {
		elgg.action('widgets/add', {
			data: {
				handler: $(this).attr('id'),
				user_guid: elgg.get_loggedin_userid(),
				context: $("input[name='widget_context']").val()
			},
			success: function(json) {
				elgg.ui.widgets.insert(json.output);
			}
		});
		event.preventDefault();
	});

	$('a.widget_delete_button').bind('click', elgg.ui.widgets.remove);
	$('a.widget_edit_button').bind('click', elgg.ui.widgets.editToggle);
	$('.widget_edit > form ').bind('submit', elgg.ui.widgets.saveSettings);
	elgg.ui.widgets.equalHeight(".widget_column");
};

/**
 * Insert a new widget into the layout
 *
 * This always inserts the widget at the top of the first column.
 *
 * @param {String} html The HTML of the widget
 * @return void
 */
elgg.ui.widgets.insert = function(html) {
	$('#widget_col_1').prepend(html);
	$('#widget_col_1').children(":first").find('a.widget_delete_button').bind('click', elgg.ui.widgets.remove);
	$('#widget_col_1').children(":first").find('a.widget_edit_button').bind('click', elgg.ui.widgets.editToggle);
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
	$(this).parent().parent().parent().parent().remove();
	elgg.action('widgets/delete', {
		data: {
			// widget_delete_button_<guid>
			guid: $(this).attr('id').substring(21)
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
	$(this).parent().parent().parent().parent().find('.widget_edit').slideToggle('medium');
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
	var $widgetContent = $(this).parent().parent().children('.widget_content');
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

