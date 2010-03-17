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
$hide_toolbar_entries = get_input('menu_items_hide_toolbar_entries', 'yes');
$featured_url_info = array();

// save the full information from the menu item into the config table
// this will be checked upon display that it is still valid (based upon url)
$menu_items = get_register('menu');
$menu_urls = array();

foreach ($menu_items as $name => $info) {
	$menu_urls[$info->value->url] = $info;
}

foreach ($featured_urls as $url) {
	if (array_key_exists($url, $menu_urls)) {
		$featured_url_info[] = $menu_urls[$url];
	}
}

// set_config() always returns 0 so can't check for failures
set_config('menu_items_featured_urls', $featured_url_info);
set_config('menu_items_hide_toolbar_entries', $hide_toolbar_entries);

system_message(elgg_echo('admin:menu_items:saved'));

forward($_SERVER['HTTP_REFERER']);