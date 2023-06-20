/**
 * Ajax lists - replace behaviour
 */
define(['jquery', 'elgg/Ajax', 'elgg/system_messages'], function ($, Ajax, system_messages) {
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
					require(['elgg/i18n'], function(i18n) {
						system_messages.error(i18n.echo('ajax:pagination:no_data'));
					});
				}
			},
		});
	});
});
