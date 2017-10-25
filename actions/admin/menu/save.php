<?php
/**
 * Save menu items.
 */

// featured menu items
$featured_names = (array) get_input('featured_menu_names', []);
$featured_names = array_unique($featured_names);
if (in_array(' ', $featured_names)) {
	unset($featured_names[array_search(' ', $featured_names)]);
}
elgg_save_config('site_featured_menu_names', $featured_names);

// custom menu items
$custom_menu_titles = get_input('custom_menu_titles', []);
$custom_menu_urls = get_input('custom_menu_urls', []);
$num_menu_items = count($custom_menu_titles);
$custom_menu_items = [];
for ($i = 0; $i < $num_menu_items; $i++) {
	if (trim($custom_menu_urls[$i]) && trim($custom_menu_titles[$i])) {
		$url = $custom_menu_urls[$i];
		$title = $custom_menu_titles[$i];
		$custom_menu_items[$title] = $url;
	}
}
elgg_save_config('site_custom_menu_items', $custom_menu_items);

return elgg_ok_response('', elgg_echo('admin:menu_items:saved'));
