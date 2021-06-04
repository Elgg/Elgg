/**
 * Ajax lists
 */
define(['jquery', 'elgg', 'elgg/Ajax'], function ($, elgg, Ajax) {

	var ajax = new Ajax();

	/**
	 * Loads a new page
	 *
	 * @param $target jquery element
	 * @param href    string to load via ajax
	 *
	 * @returns void
	 */
	function loadNewPage($target, href) {
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
					
					// scroll to top of new content
					$('html, body').animate({
						scrollTop: $(id_selector).offset().top
					}, 500);
				} else {
					elgg.register_error(elgg.echo('ajax:pagination:no_data'));
				}
			},
		});
	}

	// register click event
	$(document).on('click', '.elgg-list-container-ajax-replace .elgg-pagination a', function (event) {
		event.preventDefault();

		loadNewPage($(this).closest('.elgg-list-container'), $(this).attr('href'));
	});
});
