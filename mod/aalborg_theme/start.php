<?php
/**
 * Aalborg theme plugin
 *
 * @package AalborgTheme
 */

elgg_register_event_handler('init','system','aalborg_theme_init');

function aalborg_theme_init() {

	elgg_register_event_handler('pagesetup', 'system', 'aalborg_theme_pagesetup', 1000);

	// theme specific CSS
	elgg_extend_view('css/elgg', 'aalborg_theme/css');

	elgg_register_plugin_hook_handler('head', 'page', 'aalborg_theme_setup_head');

	elgg_register_js('respond', 'mod/aalborg_theme/vendors/js/respond.min.js');
	elgg_load_js('respond');

	if (!elgg_is_logged_in()) {
		elgg_unregister_plugin_hook_handler('output:before', 'layout', 'elgg_views_add_rss_link');
	}

}

function aalborg_theme_pagesetup() {

	elgg_unextend_view('page/elements/header', 'search/header');
	if (elgg_is_logged_in()) {
		elgg_extend_view('page/elements/sidebar', 'search/header', 0);
	}

	elgg_unregister_menu_item('topbar', 'dashboard');
	if (elgg_is_active_plugin('dashboard')) {
		elgg_register_menu_item('site', array(
			'name' => 'dashboard',
			'href' => 'dashboard',
			'text' => elgg_echo('dashboard'),
		));
	}

	if (elgg_is_logged_in()) {

		$user = elgg_get_logged_in_user_entity();

		elgg_register_menu_item('topbar', array(
			'name' => 'account',
			'text' => elgg_echo('account'),
			'href' => "#",
			'priority' => 100,
			'section' => 'alt',
			'link_class' => 'elgg-topbar-dropdown',
		));

		elgg_unregister_menu_item('topbar', 'usersettings');
		elgg_register_menu_item('topbar', array(
			'name' => 'usersettings',
			'parent_name' => 'account',
			'href' => "/settings/user/$user->username",
			'text' => elgg_echo('settings'),
			'priority' => 103,
			'section' => 'alt',
		));

		elgg_unregister_menu_item('topbar', 'logout');
		elgg_register_menu_item('topbar', array(
			'name' => 'logout',
			'parent_name' => 'account',
			'href' => '/action/logout',
			'is_action' => TRUE,
			'text' => elgg_echo('logout'),
			'priority' => 104,
			'section' => 'alt',
		));

		elgg_unregister_menu_item('topbar', 'administration');
		if (elgg_is_admin_logged_in()) {
			elgg_register_menu_item('topbar', array(
				'name' => 'administration',
				'parent_name' => 'account',
				'href' => 'admin',
				'text' => elgg_echo('admin'),
				'priority' => 101,
				'section' => 'alt',
			));
		}

		elgg_unregister_menu_item('footer', 'report_this');
		if (elgg_is_active_plugin('reportedcontent')) {
			$href = "javascript:elgg.forward('reportedcontent/add'";
			$href .= "+'?address='+encodeURIComponent(location.href)";
			$href .= "+'&title='+encodeURIComponent(document.title));";

			elgg_register_menu_item('extras', array(
				'name' => 'report_this',
				'href' => $href,
				'title' => elgg_echo('reportedcontent:this:tooltip'),
				'text' => elgg_view_icon('report-this'),
				'priority' => 500,
			));
		}
	}
}

/**
 * Register items for the html head
 *
 * @param string $hook Hook name ('head')
 * @param string $type Hook type ('page')
 * @param array  $data Array of items for head
 * @return array
 */
function aalborg_theme_setup_head($hook, $type, $data) {
	$data['metas'][] = array(
		'name' => 'viewport',
		'content' => 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0',
	);

	$data['links'][] = array(
		'rel' => 'apple-touch-icon',
		'href' => elgg_normalize_url('mod/aalborg_theme/graphics/homescreen.png'),
	);

	return $data;
}
