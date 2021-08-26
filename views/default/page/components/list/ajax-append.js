/**
 * Ajax lists - append behaviour
 */
define(['jquery', 'elgg', 'elgg/Ajax'], function ($, elgg, Ajax) {
	// register click event
	$(document).on('click', '.elgg-list-container-ajax-append .elgg-pagination a', function (event) {
		event.preventDefault();

		var $link = $(this);

		var href = $link.attr('href');
		var position = $link.closest('li').hasClass('elgg-pagination-previous') ? 'before' : 'after';
		var $target = $link.closest('.elgg-list-container');
		var $pagination = $link.closest('.elgg-pagination');
		
		$pagination.html('<div class="elgg-ajax-loader"></div>');

		var ajax = new Ajax(false);

		ajax.path(href, {
			data: {
				_elgg_ajax_list: 1, // ask for quick return from elgg_view_page()
			},
			success: function(result) {
				var id_selector = '#' + $target.attr('id');

				$new_html = $(result).find(id_selector).addBack(id_selector);
				if ($new_html.length) {
					var list_items = $new_html.find('> .elgg-list').html();
					var $target_list = $target.find('> .elgg-list');

					$pagination.replaceWith($new_html.find('> .elgg-pagination-' + position))
					if (position === 'before') {
						$target_list.prepend(list_items);
					} else {
						$target_list.append(list_items);
					}
					
					$target_list.trigger('change');
				} else {
					elgg.register_error(elgg.echo('ajax:pagination:no_data'));
				}
			},
		});
	});
});
