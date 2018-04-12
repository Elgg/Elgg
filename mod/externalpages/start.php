<?php
/**
 * Plugin for creating web pages for your site
 */

/**
 * External pages init
 *
 * @return void
 */
function expages_init() {

	elgg_register_plugin_hook_handler('register', 'menu:expages', 'expages_menu_register_hook');

	// add a menu item for the admin edit page
	elgg_register_menu_item('page', [
		'name' => 'configure_utilities:expages',
		'text' => elgg_echo('admin:configure_utilities:expages'),
		'href' => 'admin/configure_utilities/expages',
		'section' => 'configure',
		'parent_name' => 'configure_utilities',
		'context' => 'admin',
	]);

	// add footer links
	expages_setup_footer_menu();
}

/**
 * Setup the links to site pages
 *
 * @return void
 */
function expages_setup_footer_menu() {
	$pages = ['about', 'terms', 'privacy'];
	foreach ($pages as $page) {
		elgg_register_menu_item('walled_garden', [
			'name' => $page,
			'text' => elgg_echo("expages:$page"),
			'href' => $page,
		]);

		elgg_register_menu_item('footer', [
			'name' => $page,
			'text' => elgg_echo("expages:$page"),
			'href' => $page,
			'section' => 'meta',
		]);
	}
}

/**
 * Adds menu items to the expages edit form
 *
 * @param string $hook   'register'
 * @param string $type   'menu:expages'
 * @param array  $return current menu items
 * @param array  $params parameters
 *
 * @return array
 */
function expages_menu_register_hook($hook, $type, $return, $params) {
	$type = elgg_extract('type', $params);
		
	$pages = ['about', 'terms', 'privacy'];
	foreach ($pages as $page) {
		$return[] = ElggMenuItem::factory([
			'name' => $page,
			'text' => elgg_echo("expages:$page"),
			'href' => "admin/configure_utilities/expages?type=$page",
			'selected' => $page === $type,
		]);
	}
	return $return;
}

return function() {
	elgg_register_event_handler('init', 'system', 'expages_init');
};
