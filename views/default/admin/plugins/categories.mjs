import 'jquery';

/**
 * Filters the plugin list based on a selected category
 *
 * @return void
 */
function filterPluginCategory(event) {
	event.preventDefault();
	
	// remove selected state from all buttons
	$('.elgg-admin-plugins-categories > a').removeClass('elgg-state-selected');

	// show plugins with the selected category
	$('.elgg-plugin').hide();
	$('.elgg-plugin-category-' + $(this).attr('rel')).show();
	$(this).addClass('elgg-state-selected');
};

$(document).on('click', '.elgg-admin-plugins-categories a', filterPluginCategory);
