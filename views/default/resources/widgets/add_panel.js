define(['jquery', 'elgg', 'elgg/Ajax'], function($, elgg, Ajax) {
	
	/**
	 * Adds a new widget
	 *
	 * Makes Ajax call to add new widget and inserts the widget html
	 *
	 * @param {Object} event
	 * @return void
	 */
	function addWidget(event) {
		event.preventDefault();
		
		var $item = $(this).closest('li');

		// if multiple instances not allow, disable this widget type add button
		if (!$item.is('.elgg-widget-multiple')) {
			$item.toggleClass('elgg-state-unavailable elgg-state-available');
		}
		
		var href = $(this).attr('href');
		var ajax = new Ajax();
		
		ajax.path(href).done(function(output) {
			var query_parts = elgg.parse_url(href, 'query', true);
			var selector = '';
			var context = query_parts['context'];
			var page_owner_guid = query_parts['page_owner_guid'];
			var new_widget_column = query_parts['new_widget_column'] || 1;
			var new_widget_position = query_parts['new_widget_position'] || 'top';
			
			if (context && page_owner_guid) {
				// target the correct widget layout
				selector = '.elgg-layout-widgets-' + context + '[data-page-owner-guid="' + page_owner_guid + '"] #elgg-widget-col-' + new_widget_column;
			} else {
				selector = '#elgg-widget-col-' + new_widget_column;
			}
			
			if (new_widget_position === 'top') {
				$(selector).prepend(output);
			} else {
				$(selector).append(output);
			}
		});
	};
	
	$(document).on('click', '.elgg-widgets-add-panel .elgg-widgets-add-actions .elgg-button-submit', addWidget);
	
	$(document).on('keyup', '.elgg-widgets-add-panel input[name="widget_search"]', function() {
		var $container = $('.elgg-widgets-add-panel');
		var $items = $container.find('> .elgg-body > ul > li');
		var q = $(this).val();

		if (q === '') {
			$items.show();
		} else {
			$items.hide();
			$items.filter(function () {
				return $(this).text().toUpperCase().indexOf(q.toUpperCase()) >= 0;
			}).show();
		}
	});
});
