/**
 * @module elgg/widgets
 */
define(['elgg', 'jquery', 'elgg/ready'], function (elgg, $) {

	var widgets = {};

	/**
	 * Widgets initialization
	 *
	 * @return void
	 * @requires jqueryui.sortable
	 */
	widgets.init = function () {

		// widget layout?
		if ($(".elgg-widgets").length === 0) {
			return;
		}

		$(".elgg-widgets").sortable({
			items: 'div.elgg-module-widget.elgg-state-draggable',
			connectWith: '.elgg-widgets',
			handle: '.elgg-widget-handle',
			forcePlaceholderSize: true,
			placeholder: 'elgg-widget-placeholder',
			opacity: 0.8,
			revert: 500,
			stop: widgets.move
		});

		$(document).on('click', 'a.elgg-widget-delete-button', widgets.remove);
		$(document).on('submit', '.elgg-widget-edit > form ', widgets.saveSettings);
		$(document).on('click', 'a.elgg-widget-collapse-button', widgets.collapseToggle);
	};

	/**
	 * Persist the widget's new position
	 *
	 * @param {Object} event
	 * @param {Object} ui
	 *
	 * @return void
	 */
	widgets.move = function (event, ui) {

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
	widgets.remove = function (event) {
		if (confirm(elgg.echo('deleteconfirm')) === false) {
			event.preventDefault();
			return;
		}

		$(this).closest('.elgg-module-widget').remove();

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
	widgets.collapseToggle = function (event) {
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
	widgets.saveSettings = function (event) {
		$(this).parent().slideToggle('medium');
		var $widgetContent = $(this).parent().parent().children('.elgg-widget-content');

		// stick the ajax loader in there
		var $loader = $('#elgg-widget-loader').clone();
		$loader.attr('id', '#elgg-widget-active-loader');
		$loader.removeClass('hidden');
		$widgetContent.html($loader);

		elgg.action('widgets/save', {
			data: $(this).serialize(),
			success: function (json) {
				$widgetContent.html(json.output.content);
				if (typeof (json.output.title) != "undefined") {
					var $widgetTitle = $widgetContent.parent().parent().find('.elgg-widget-title');
					$widgetTitle.html(json.title);
				}
			}
		});
		event.preventDefault();
	};

	return widgets;
});

