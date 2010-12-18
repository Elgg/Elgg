<?php
/**
 * Elgg user settings functions.
 * Functions for adding and manipulating options on the user settings panel.
 *
 * @package Elgg.Core
 * @subpackage Settings.User
 */

/**
 * Register a user settings page with the admin panel.
 * This function extends the view "usersettings/main" with the provided view.
 * This view should provide a description and either a control or a link to.
 *
 * Usage:
 * 	- To add a control to the main admin panel then extend usersettings/main
 *  - To add a control to a new page create a page which renders a view
 *    usersettings/subpage (where subpage is your new page -
 *    nb. some pages already exist that you can extend), extend the main view
 *    to point to it, and add controls to your new view.
 *
 * At the moment this is essentially a wrapper around elgg_extend_view().
 *
 * @param string $new_settings_view The view associated with the control you're adding
 * @param string $view              The view to extend, by default this is 'usersettings/main'.
 * @param int    $priority          Optional priority to govern the appearance in the list.
 *
 * @return bool
 * @deprecated 1.8 Extend oone of the views in core/settings
 */
function extend_elgg_settings_page($new_settings_view, $view = 'usersettings/main',
$priority = 500) {
	// see views: /core/settings
	elgg_deprecated_notice("extend_elgg_settings_page has been deprecated. Extend on of the settings views instead", 1.8);

	return elgg_extend_view($view, $new_settings_view, $priority);
}

/**
 * Set up the page for user settings
 *
 * @return void
 */
function usersettings_pagesetup() {
	if (elgg_get_context() == "settings" && get_loggedin_userid()) {
		$user = get_loggedin_user();

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
		elgg_set_page_owner_guid(get_loggedin_userid());
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