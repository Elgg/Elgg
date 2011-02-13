<?php
/**
 * Elgg user settings functions.
 * Functions for adding and manipulating options on the user settings panel.
 *
 * @package Elgg.Core
 * @subpackage Settings.User
 */

/**
 * Set up the page for user settings
 *
 * @return void
 */
function usersettings_pagesetup() {
	if (elgg_get_context() == "settings" && elgg_get_logged_in_user_guid()) {
		$user = elgg_get_logged_in_user_entity();

		$params = array(
			'name' => '1_account',
			'title' => elgg_echo('usersettings:user:opt:linktext'),
			'url' => "pg/settings/user/{$user->username}",
		);
		elgg_register_menu_item('page', $params);
		$params = array(
			'name' => '1_plugins',
			'title' => elgg_echo('usersettings:plugins:opt:linktext'),
			'url' => "pg/settings/plugins/{$user->username}",
		);
		elgg_register_menu_item('page', $params);
		$params = array(
			'name' => '1_statistics',
			'title' => elgg_echo('usersettings:statistics:opt:linktext'),
			'url' => "pg/settings/statistics/{$user->username}",
		);
		elgg_register_menu_item('page', $params);
	}
}

/**
 * Page handler for user settings
 *
 * @param array $page Pages array
 *
 * @return void
 */
function usersettings_page_handler($page) {
	global $CONFIG;

	if (!isset($page[0])) {
		$page[0] = 'user';
	}

	switch ($page[0]) {
		case 'statistics':
			$path = $CONFIG->path . "pages/settings/statistics.php";
			break;
		case 'plugins':
			$path = $CONFIG->path . "pages/settings/tools.php";
			break;
		case 'user':
		default:
			$path = $CONFIG->path . "pages/settings/account.php";
			break;
	}

	if ($page[1]) {
		$user = get_user_by_username($page[1]);
		elgg_set_page_owner_guid($user->guid);
	} else {
		elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
	}

	require($path);
}

/**
 * Initialise the admin page.
 *
 * @return void
 */
function usersettings_init() {
	// Page handler
	register_page_handler('settings', 'usersettings_page_handler');
}

/// Register init function
elgg_register_event_handler('init', 'system', 'usersettings_init');
elgg_register_event_handler('pagesetup', 'system', 'usersettings_pagesetup');
