<?php
/**
 * Holds helper functions for file plugin
 */

/**
 * Adds a toggle to filter menu for switching between list and gallery views
 *
 * @return void
 */
function file_register_toggle(): void {

	if (get_input('list_type', 'list') == 'list') {
		$list_type = 'gallery';
		$icon = elgg_view_icon('grid');
	} else {
		$list_type = 'list';
		$icon = elgg_view_icon('list');
	}

	$url = elgg_http_add_url_query_elements(elgg_get_current_url(), ['list_type' => $list_type]);
	
	elgg_register_menu_item('filter:filter', [
		'name' => 'file_list',
		'text' => $icon,
		'href' => $url,
		'title' => elgg_echo("file:list:$list_type"),
		'priority' => 1000,
	]);
}
