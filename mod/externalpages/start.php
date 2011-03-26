<?php
/**
 * Plugin for creating web pages for your site
 */

register_elgg_event_handler('init', 'system', 'expages_init');

function expages_init() {

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('about', 'about_page_handler');
        elgg_register_page_handler('terms', 'terms_page_handler');
        elgg_register_page_handler('privacy', 'privacy_page_handler');

	// add a menu item for the admin edit page
	elgg_register_admin_menu_item('configure', 'expages', 'site');

	// add footer links
	expages_setup_footer_menu();

	// register action
	$actions_base = elgg_get_plugins_path() . 'externalpages/actions';
	elgg_register_action("expages/edit", "$actions_base/edit.php", 'admin');
}

/**
 * Setup the links to site pages
 */
function expages_setup_footer_menu() {
    $pages = array('about', 'terms', 'privacy');
    foreach ($pages as $page) {
        $url = "$page";
        $item = new ElggMenuItem($page, elgg_echo("expages:$page"), $url);
		$item->setSection('alt');
        elgg_register_menu_item('footer', $item);
    }
}

/**
 * External pages page handler
 *
 * 
 */
function about_page_handler() {
	$type = 'about';
	
	$title = elgg_echo("expages:$type");
	$content = elgg_view_title($title);

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

	$body = elgg_view_layout("one_sidebar", array('content' => $content));
	echo elgg_view_page($title, $body);
}

function terms_page_handler() {
	$type = 'terms';
	
	$title = elgg_echo("expages:$type");
	$content = elgg_view_title($title);

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

	$body = elgg_view_layout("one_sidebar", array('content' => $content));
	echo elgg_view_page($title, $body);
}

function privacy_page_handler() {
	$type = 'privacy';
	
	$title = elgg_echo("expages:$type");
	$content = elgg_view_title($title);

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

	$body = elgg_view_layout("one_sidebar", array('content' => $content));
	echo elgg_view_page($title, $body);
}
