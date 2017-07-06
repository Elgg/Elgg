define(['jquery', 'elgg/Ajax'], function($, Ajax) {
	var ajax = new Ajax();
	
	/**
	 * Adds a new widget
	 *
	 * Makes Ajax call to add new widget and inserts the widget html
	 *
	 * @param {Object} event
	 * @return void
	 */
	var add = function (event) {
		var $item = $(this).closest('li');

		// if multiple instances not allow, disable this widget type add button
		if (!$item.is('.elgg-widget-multiple')) {
			$item.toggleClass('elgg-state-unavailable elgg-state-available');
		}

		ajax.path($(this).attr('href')).done(function(output) {
			$('#elgg-widget-col-1').prepend(output);
		});
		event.preventDefault();
	};
	
	$(document).on('click', '.elgg-widgets-add-panel .elgg-widgets-add-actions .elgg-button-submit', add);
	
});
