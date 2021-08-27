/**
 * Ajax lists - replace behaviour
 */
define(['jquery', 'elgg', 'elgg/Ajax'], function ($, elgg, Ajax) {
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

				$new_html = $(result).find(id_selector).addBack(id_selector);
				if ($new_html.length) {
					// update history
					window.history.pushState('', '', href);

					// replace previous list with new content
					$target.replaceWith($new_html);
					
					var $target_list = $target.find('> .elgg-list');
					$target_list.trigger('change');
					
					// scroll to top of new content
					$('html, body').animate({
						scrollTop: $(id_selector).offset().top
					}, 500);
				} else {
					elgg.register_error(elgg.echo('ajax:pagination:no_data'));
				}
			},
		});
	});
});
