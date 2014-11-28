elgg.provide('elgg.ui.widgets');

/**
 * Widgets initialization
 *
 * @return void
 * @requires jqueryui.sortable
 */
elgg.ui.widgets.init = function() {

	// widget layout?
	if ($(".elgg-widgets").length === 0) {
		return;
	}

	$(".elgg-widgets").sortable({
		items:                'div.elgg-module-widget.elgg-state-draggable',
		connectWith:          '.elgg-widgets',
		handle:               '.elgg-widget-handle',
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

	elgg.ui.widgets.setMinHeight(".elgg-widgets");
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
	var type = $(this).data('elgg-widget-type');

	// if multiple instances not allow, disable this widget type add button
	var multiple = $(this).attr('class').indexOf('elgg-widget-multiple') != -1;
	if (multiple === false) {
		$(this).addClass('elgg-state-unavailable');
		$(this).removeClass('elgg-state-available');
		$(this).unbind('click', elgg.ui.widgets.add);
	}

	elgg.action('widgets/add', {
		data: {
			handler: type,
			page_owner_guid: elgg.get_page_owner_guid(),
			context: $("input[name='widget_context']").val(),
			show_access: $("input[name='show_access']").val(),
			default_widgets: $("input[name='default_widgets']").val() || 0
		},
		success: function(json) {
			$('#elgg-widget-col-1').prepend(json.output);
		}
	});
	event.preventDefault();
};

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
};

/**
 * Removes a widget from the layout
 *
 * Event callback the uses Ajax to delete the widget and removes its HTML
 *
 * @param {Object} event
 * @return void
 */
elgg.ui.widgets.remove = function(event) {
	if (confirm(elgg.echo('deleteconfirm')) === false) {
		event.preventDefault();
		return;
	}

	var $widget = $(this).closest('.elgg-module-widget');

	// if widget type is single instance type, enable the add buton
	var type = $(this).data('elgg-widget-type');
	$container = $(this).parents('.elgg-layout-widgets').first();
	$button = $('[data-elgg-widget-type="' + type + '"]', $container);
	var multiple = $button.attr('class').indexOf('elgg-widget-multiple') != -1;
	if (multiple === false) {
		$button.addClass('elgg-state-available');
		$button.removeClass('elgg-state-unavailable');
		$button.unbind('click', elgg.ui.widgets.add); // make sure we don't bind twice
		$button.click(elgg.ui.widgets.add);
	}

	$widget.remove();

	// delete the widget through ajax
	elgg.action($(this).attr('href'));

	event.preventDefault();
};

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
};

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
			if (typeof(json.title) != "undefined") {
				var $widgetTitle = $widgetContent.parent().parent().find('.elgg-widget-title');
				$widgetTitle.html(json.title);
			}
		}
	});
	event.preventDefault();
};

/**
 * Set the min-height so that all widget column bottoms are the same
 *
 * This addresses the issue of trying to drag a widget into a column that does
 * not have any widgets or many fewer widgets than other columns.
 *
 * @param {String} selector
 * @return void
 */
elgg.ui.widgets.setMinHeight = function(selector) {
	var maxBottom = 0;
	$(selector).each(function() {
		var bottom = parseInt($(this).offset().top + $(this).height());
		if (bottom > maxBottom) {
			maxBottom = bottom;
		}
	});
	$(selector).each(function() {
		var bottom = parseInt($(this).offset().top + $(this).height());
		if (bottom < maxBottom) {
			var newMinHeight = parseInt($(this).height() + (maxBottom - bottom));
			$(this).css('min-height', newMinHeight + 'px');
		}
	});
};

elgg.register_hook_handler('init', 'system', elgg.ui.widgets.init);
