/**
 * Ajax lists - replace behaviour
 */

import 'jquery';
import Ajax from 'elgg/Ajax';
import system_messages from 'elgg/system_messages';

// register click event
$(document).on('click', '.elgg-list-container-ajax-replace .elgg-pagination a', function (event) {
	event.preventDefault();

	var $link = $(this);
	
	var href = $link.attr('href');
	var $target = $link.closest('.elgg-list-container');

	var ajax = new Ajax();

	ajax.path(href, {
		data: {
			_elgg_ajax_list: 1, // ask for quick return from elgg_view_page()
		},
		success: function(result) {
			var id_selector = '#' + $target.attr('id');

			var $new_html = $(result).find(id_selector).addBack(id_selector);
			if ($new_html.length) {
				// update history
				window.history.pushState('', '', href);

				// replace previous list with new content
				$target.replaceWith($new_html);
				
				var $target_list = $target.find('> .elgg-list');
				$target_list.trigger('change');
				
				// scroll to top of new content
				$(id_selector)[0].scrollIntoView({behavior: 'smooth'});
			} else {
				import('elgg/i18n').then((i18n) => {
					system_messages.error(i18n.default.echo('ajax:pagination:no_data'));
				});
			}
		},
	});
});
