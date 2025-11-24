import 'jquery';
import 'jquery-ui';
import Ajax from 'elgg/Ajax';
import lightbox from 'elgg/lightbox';
import popup from 'elgg/popup';

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
	guidString = guidString.substring(guidString.indexOf('elgg-widget-') + "elgg-widget-".length);

	var ajax = new Ajax(false);
	ajax.action('widgets/move', {
		data: {
			widget_guid: guidString,
			column: ui.item.parent().data('widgetColumn'),
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

	// close the dropdown menu
	popup.close();

	$('#elgg-widget-' + $(this).data().widgetGuid).remove();

	// delete the widget through ajax
	var ajax = new Ajax(false);
	ajax.action($(this).attr('href'));
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
			
	var $widgetContent = $('#elgg-widget-content-' + $(this).find('input[name="guid"]').val());

	var ajax = new Ajax();
	ajax.action('widgets/save', {
		data: ajax.objectify(this),
		success: function (result) {
			lightbox.close();
			
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
	items: '.elgg-module-widget.elgg-state-draggable',
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
