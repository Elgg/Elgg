<?php
/**
 * Plugin for creating web pages for your site
 */

elgg_register_event_handler('init', 'system', 'expages_init');

function expages_init() {

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('about', 'expages_page_handler');
	elgg_register_page_handler('terms', 'expages_page_handler');
	elgg_register_page_handler('privacy', 'expages_page_handler');
	elgg_register_page_handler('expages', 'expages_page_handler');

	// Register public external pages
	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'expages_public');

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
 * Extend the public pages range
 *
 */
function expages_public($hook, $handler, $return, $params) {
	$pages = ['about', 'terms', 'privacy'];
	return array_merge($pages, $return);
}

/**
 * Setup the links to site pages
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
 * External pages page handler
 *
 * @param array  $page    URL segements
 * @param string $handler Handler identifier
 * @return bool
 */
function expages_page_handler($page, $handler) {
	if ($handler == 'expages') {
		forward($page[1]);
	}
	$type = strtolower($handler);

	$title = elgg_echo("expages:$type");

	$object = elgg_get_entities([
		'type' => 'object',
		'subtype' => $type,
		'limit' => 1,
	]);
	
	$description = $object ? $object[0]->description : elgg_echo('expages:notset');
	$description = elgg_view('output/longtext', ['value' => $description]);
	
	$content = elgg_view('expages/wrapper', [
		'content' => $description,
	]);
	
	if (elgg_is_admin_logged_in()) {
		elgg_register_menu_item('title', [
			'name' => 'edit',
			'text' => elgg_echo('edit'),
			'href' => "admin/configure_utilities/expages?type=$type",
			'link_class' => 'elgg-button elgg-button-action',
		]);
	}
	
	$shell = 'default';
	if (elgg_get_config('walled_garden') && !elgg_is_logged_in()) {
		$shell = 'walled_garden';
	}
	$body = elgg_view_layout('default', [
		'content' => $content,
		'title' => $title,
		'sidebar' => false,
	]);
	echo elgg_view_page($title, $body, $shell);
	
	return true;
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
