<?php
/**
 * Save menu items.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$featured_urls = get_input('featured_urls', array());
$custom_item_names = get_input('custom_item_names', array());
$custom_item_urls = get_input('custom_item_urls', array());

// save the full information from the menu item into the config table
// this will be checked upon display that it is still valid (based upon url)
$menu_items = get_register('menu');
$menu_urls = array();
$featured_url_info = array();

foreach ($menu_items as $name => $info) {
	$menu_urls[$info->value->url] = $info;
}

foreach ($featured_urls as $url) {
	if (array_key_exists($url, $menu_urls)) {
		$featured_url_info[] = $menu_urls[$url];
	}
}

// save the custom items
$custom_count = count($custom_item_names);
$custom_items = array();
for ($i=0; $i<$custom_count; $i++) {
	if (isset($custom_item_names[$i]) && isset($custom_item_names[$i])) {
		$name = $custom_item_names[$i];
		$url = $custom_item_urls[$i];

		if ($name && $url) {
			$custom_items[$url] = $name;
		}
	}
}


// set_config() always returns 0 so can't check for failures
set_config('menu_items_featured_urls', $featured_url_info);
set_config('menu_items_custom_items', $custom_items);

system_message(elgg_echo('admin:menu_items:saved'));

forward($_SERVER['HTTP_REFERER']);
