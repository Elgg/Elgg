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

	// add a menu item for the admin edit page
	elgg_register_admin_menu_item('configure', 'expages', 'appearance');

	// add footer links
	expages_setup_footer_menu();

	// register action
	$actions_base = elgg_get_plugins_path() . 'externalpages/actions';
	elgg_register_action("expages/edit", "$actions_base/edit.php", 'admin');
}

/**
 * Extend the public pages range
 *
 */
function expages_public($hook, $handler, $return, $params){
	$pages = array('about', 'terms', 'privacy');
	return array_merge($pages, $return);
}

/**
 * Setup the links to site pages
 */
function expages_setup_footer_menu() {
	$pages = array('about', 'terms', 'privacy');
	foreach ($pages as $page) {
		$url = "$page";
		$wg_item = new ElggMenuItem($page, elgg_echo("expages:$page"), $url);
		elgg_register_menu_item('walled_garden', $wg_item);

		$footer_item = clone $wg_item;
		elgg_register_menu_item('footer', $footer_item);
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
		expages_url_forwarder($page[1]);
	}
	$type = strtolower($handler);

	$title = elgg_echo("expages:$type");
	$header = elgg_view_title($title);

	$object = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => $type,
		'limit' => 1,
	));
	if ($object) {
		$content .= elgg_view('output/longtext', array('value' => $object[0]->description));
	} else {
		$content .= elgg_echo("expages:notset");
	}
	$content = elgg_view('expages/wrapper', array('content' => $content));

	if (elgg_is_logged_in() || !elgg_get_config('walled_garden')) {
		$body = elgg_view_layout('one_column', array('title' => $title, 'content' => $content));
		echo elgg_view_page($title, $body);
	} else {
		elgg_load_css('elgg.walled_garden');
		$body = elgg_view_layout('walled_garden', array('content' => $header . $content));
		echo elgg_view_page($title, $body, 'walled_garden');
	}
	return true;
}

/**
 * Forward to the new style of URLs
 *
 * @param string $page
 */
function expages_url_forwarder($page) {
	global $CONFIG;
	$url = "{$CONFIG->wwwroot}{$page}";
	forward($url);
}
