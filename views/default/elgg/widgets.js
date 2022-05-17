define(['jquery', 'elgg/Ajax', 'elgg/i18n', 'jquery-ui/widgets/sortable'], function ($, Ajax, i18n) {

	/**
	 * Persist the widget's new position
	 *
	 * @param {Object} event
	 * @param {Object} ui
	 *
	 * @return void
	 */
	function moveWidget(event, ui) {
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
	};

	/**
	 * Removes a widget from the layout
	 *
	 * Event callback the uses Ajax to delete the widget and removes its HTML
	 *
	 * @param {Object} event
	 * @return void
	 */
	function removeWidget(event) {
		event.preventDefault();
		
		if (confirm(i18n.echo('deleteconfirm')) === false) {
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
	function toggleWidget(event) {
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
	function saveWidgetSettings(event) {
		event.preventDefault();
		
		$(this).parent().slideToggle('medium');
		var $widgetContent = $(this).parent().parent().children('.elgg-widget-content');

		// stick the ajax loader in there
		$widgetContent.html('<div class="elgg-ajax-loader"></div>');

		var ajax = new Ajax(false);
		ajax.action('widgets/save', {
			data: $(this).serialize(),
			success: function (result) {
				$widgetContent.html(result.content);
				if (result.title !== '') {
					var $widgetTitle = $widgetContent.parent().parent().find('.elgg-widget-title');
					
					var newWidgetTitle = result.title;
					if (result.href !== '') {
						newWidgetTitle = "<a href='" + result.href + "' class='elgg-anchor'><span class='elgg-anchor-label'>" + newWidgetTitle + "</span></a>";
					}
					
					$widgetTitle.html(newWidgetTitle);
				}
			}
		});
	};

	$('.elgg-widgets').sortable({
		items: 'div.elgg-module-widget.elgg-state-draggable',
		connectWith: '.elgg-widgets',
		handle: '.elgg-widget-handle',
		forcePlaceholderSize: true,
		placeholder: 'elgg-widget-placeholder',
		opacity: 0.8,
		revert: 500,
		stop: moveWidget
	});

	$(document).on('click', 'a.elgg-widget-delete-button', removeWidget);
	$(document).on('submit', '.elgg-widget-edit > form ', saveWidgetSettings);
	$(document).on('click', 'a.elgg-widget-collapse-button', toggleWidget);
});
