elgg.provide('elgg.ui.widgets');

/**
 * Widgets initialization
 *
 * @return void
 */
elgg.ui.widgets.init = function() {

	// widget layout?
	if ($(".elgg-widgets").length == 0) {
		return;
	}

	$(".elgg-widgets").sortable({
		items:                'div.elgg-module-widget.elgg-state-draggable',
		connectWith:          '.elgg-widgets',
		handle:               'div.elgg-head',
		forcePlaceholderSize: true,
		placeholder:          'elgg-widget-placeholder',
		opacity:              0.8,
		revert:               500,
		stop:                 elgg.ui.widgets.move
	});

	$('.elgg-widgets-add-panel li.elgg-state-available').click(elgg.ui.widgets.add);

	$('a.elgg-widget-delete-button').live('click', elgg.ui.widgets.remove);
	$('.elgg-widget-edit > form ').live('submit', elgg.ui.widgets.saveSettings);
	$('a.elgg-widget-collapse-button').live('click', elgg.ui.widgets.collapseToggle);

	elgg.ui.widgets.equalHeight(".elgg-widgets");
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
	// elgg-widget-type-<type>
	var type = $(this).attr('id');
	type = type.substr(type.indexOf('elgg-widget-type-') + "elgg-widget-type-".length);

	// if multiple instances not allow, disable this widget type add button
	var multiple = $(this).attr('class').indexOf('elgg-widget-multiple') != -1;
	if (multiple == false) {
		$(this).addClass('elgg-state-unavailable');
		$(this).removeClass('elgg-state-available');
		$(this).unbind('click', elgg.ui.widgets.add);
	}

	elgg.action('widgets/add', {
		data: {
			handler: type,
			owner_guid: elgg.get_page_owner_guid(),
			context: $("input[name='widget_context']").val(),
			default_widgets: $("input[name='default_widgets']").val() || 0
		},
		success: function(json) {
			$('#elgg-widget-col-1').prepend(json.output);
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

	// elgg-widget-<guid>
	var guidString = ui.item.attr('id');
	guidString = guidString.substr(guidString.indexOf('elgg-widget-') + "elgg-widget-".length);

	// elgg-widget-col-<column>
	var col = ui.item.parent().attr('id');
	col = col.substr(col.indexOf('elgg-widget-col-') + "elgg-widget-col-".length);

	elgg.action('widgets/move', {
		data: {
			widget_guid: guidString,
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
	// elgg-widget-instance-<type>
	type = type.substr(type.indexOf('elgg-widget-instance-') + "elgg-widget-instance-".length);
	$button = $('#elgg-widget-type-' + type);
	var multiple = $button.attr('class').indexOf('elgg-widget-multiple') != -1;
	if (multiple == false) {
		$button.addClass('elgg-state-available');
		$button.removeClass('elgg-state-unavailable');
		$button.unbind('click', elgg.ui.widgets.add); // make sure we don't bind twice
		$button.click(elgg.ui.widgets.add);
	}

	$widget.remove();

	// elgg-widget-delete-button-<guid>
	var id = $(this).attr('id');
	id = id.substr(id.indexOf('elgg-widget-delete-button-') + "elgg-widget-delete-button-".length);

	elgg.action('widgets/delete', {
		data: {
			widget_guid: id
		}
	});
	event.preventDefault();
}

/**
 * Toggle the collapse state of the widget
 *
 * @param {Object} event
 * @return void
 */
elgg.ui.widgets.collapseToggle = function(event) {
	$(this).toggleClass('elgg-widget-collapsed');
	$(this).parent().parent().find('.elgg-body').slideToggle('medium');
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
	var $widgetContent = $(this).parent().parent().children('.elgg-widget-content');

	// stick the ajax loader in there
	var $loader = $('#elgg-widget-loader').clone();
	$loader.attr('id', '#elgg-widget-active-loader');
	$loader.removeClass('hidden');
	$widgetContent.html($loader);

	var default_widgets = $("input[name='default_widgets']").val() || 0;
	if (default_widgets) {
		$(this).append('<input type="hidden" name="default_widgets" value="1">');
	}

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

elgg.register_hook_handler('init', 'system', elgg.ui.widgets.init);
