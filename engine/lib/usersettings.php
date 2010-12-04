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
 */
function extend_elgg_settings_page($new_settings_view, $view = 'usersettings/main',
$priority = 500) {

	return elgg_extend_view($view, $new_settings_view, $priority);
}

/**
 * Set up the page for user settings
 *
 * @return void
 */
function usersettings_pagesetup() {
	// Get config
	global $CONFIG;

	// Menu options
	if (elgg_get_context() == "settings") {
		$user = get_loggedin_user();
		add_submenu_item(elgg_echo('usersettings:user:opt:linktext'),
			$CONFIG->wwwroot . "pg/settings/user/{$user->username}/");

		add_submenu_item(elgg_echo('usersettings:plugins:opt:linktext'),
			$CONFIG->wwwroot . "pg/settings/plugins/{$user->username}/");

		add_submenu_item(elgg_echo('usersettings:statistics:opt:linktext'),
			$CONFIG->wwwroot . "pg/settings/statistics/{$user->username}/");
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

	$path = $CONFIG->path . "pages/settings/index.php";

	if ($page[0]) {
		switch ($page[0]) {
			case 'user':
				$path = $CONFIG->path . "pages/settings/user.php";
				break;
			case 'statistics':
				$path = $CONFIG->path . "pages/settings/statistics.php";
				break;
			case 'plugins':
				$path = $CONFIG->path . "pages/settings/plugins.php";
				break;
		}
	}

	if ($page[1]) {
		set_input('username', $page[1]);
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