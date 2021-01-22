/**
 * @module elgg/widgets
 */
define(['jquery', 'elgg', 'elgg/Ajax', 'elgg/ready'], function ($, elgg, Ajax) {

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
		
		var ajax = new Ajax(false);
		ajax.action('widgets/move', {
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
		event.preventDefault();
		
		if (confirm(elgg.echo('deleteconfirm')) === false) {
			return;
		}

		$(this).closest('.elgg-module-widget').remove();

		// delete the widget through ajax
		var ajax = new Ajax(false);
		ajax.action($(this).attr('href'));
	};

	/**
	 * Toggle the collapse state of the widget
	 *
	 * @param {Object} event
	 * @return void
	 */
	widgets.collapseToggle = function (event) {
		event.preventDefault();
		
		$(this).toggleClass('elgg-widget-collapsed');
		$(this).parent().parent().find('.elgg-body').slideToggle('medium');
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
		event.preventDefault();
		
		$(this).parent().slideToggle('medium');
		var $widgetContent = $(this).parent().parent().children('.elgg-widget-content');

		// stick the ajax loader in there
		var $loader = $('#elgg-widget-loader').clone();
		$loader.attr('id', '#elgg-widget-active-loader');
		$loader.removeClass('hidden');
		$widgetContent.html($loader);

		var ajax = new Ajax(false);
		ajax.action('widgets/save', {
			data: $(this).serialize(),
			success: function (result) {
				$widgetContent.html(result.content);
				if (typeof (result.title) != "undefined") {
					var $widgetTitle = $widgetContent.parent().parent().find('.elgg-widget-title');
					
					var newWidgetTitle = result.title;
					if (typeof (result.href) != "undefined") {
						newWidgetTitle = "<a href='" + result.href + "' class='elgg-anchor'><span class='elgg-anchor-label'>" + newWidgetTitle + "</span></a>";
					}
					
					$widgetTitle.html(newWidgetTitle);
				}
			}
		});
	};

	return widgets;
});

