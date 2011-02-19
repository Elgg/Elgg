<?php
/**
 * Elgg developer tools
 */

elgg_register_event_handler('init', 'system', 'developers_init');

function developers_init() {
	elgg_register_event_handler('pagesetup', 'system', 'developers_setup_menu');

	elgg_extend_view('css/admin', 'developers/css');

	elgg_register_page_handler('theme_preview', 'developers_theme_preview_controller');

	$action_base = elgg_get_plugins_path() . 'developers/actions/developers';
	elgg_register_action('developers/settings', "$action_base/settings.php", 'admin');
}

function developers_setup_menu() {
	if (elgg_in_context('admin')) {
		elgg_add_admin_menu_item('developers', elgg_echo('admin:developers'));
		elgg_add_admin_menu_item('settings', elgg_echo('admin:developers:settings'), 'developers');
		elgg_add_admin_menu_item('preview', elgg_echo('admin:developers:preview'), 'developers');
	}
}

/**
 * Serve the theme preview pages
 *
 * @param array $page
 */
function developers_theme_preview_controller($page) {
	if (!isset($page[0])) {
		forward('pg/theme_preview/general');
	}

	$pages = array(
		'general', 
		'navigation', 
		'forms', 
		'objects', 
		'grid', 
		'widgets', 
		'icons',
	);
	
	foreach ($pages as $page_name) {
		elgg_register_menu_item('page', array(
			'name' => $page_name,
			'title' => elgg_echo("theme_preview:$page_name"),
			'url' => "pg/theme_preview/$page_name",
		));
	}

	$title = elgg_echo("theme_preview:{$page[0]}");
	$body =  elgg_view("theme_preview/{$page[0]}");

	echo elgg_view_page($title, $body, 'theme_preview');
}
