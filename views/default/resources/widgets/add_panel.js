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
		event.preventDefault();
		
		var $item = $(this).closest('li');

		// if multiple instances not allow, disable this widget type add button
		if (!$item.is('.elgg-widget-multiple')) {
			$item.toggleClass('elgg-state-unavailable elgg-state-available');
		}

		var href = $(this).attr('href');
		ajax.path(href).done(function(output) {
			var query_parts = elgg.parse_url(href, 'query', true);
			var selector = '';

			if (query_parts['context'] && query_parts['page_owner_guid']) {
				// target the correct widget layout
				selector = '.elgg-layout-widgets-' + query_parts['context'] + '[data-page-owner-guid="' + query_parts['page_owner_guid'] + '"] #elgg-widget-col-1';
			} else {
				selector = '#elgg-widget-col-1';
			}

			$(selector).prepend(output);
		});
	};
	
	$(document).on('click', '.elgg-widgets-add-panel .elgg-widgets-add-actions .elgg-button-submit', add);
});
