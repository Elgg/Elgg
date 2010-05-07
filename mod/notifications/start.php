<?php

/**
 * Elgg notifications plugin
 *
 * @package ElggNotifications
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */


function notifications_plugin_init() {
	global $CONFIG;

	elgg_extend_view('css','notifications/css');

	register_page_handler('notifications', 'notifications_page_handler');

	register_elgg_event_handler('pagesetup', 'system', 'notifications_plugin_pagesetup');

	// Unset the default user settings hook
	if (isset($CONFIG->hooks['usersettings:save']['user'])) {
		foreach($CONFIG->hooks['usersettings:save']['user'] as $key => $function) {
			if ($function == 'notification_user_settings_save') {
				unset($CONFIG->hooks['usersettings:save']['user'][$key]);
			}
		}
	}
}

/**
 * Route page requests
 *
 * @param array $page Array of url parameters
 */
function notifications_page_handler($page) {
	global $CONFIG;

	// default to personal notifications
	if (!isset($page[0])) {
		$page[0] = 'personal';
	}

	switch ($page[0]) {
		case 'group':
			require $CONFIG->pluginspath . "notifications/groups.php";
			break;
		case 'personal':
		default:
			require $CONFIG->pluginspath . "notifications/index.php";
			break;
	}

	return TRUE;
}

/**
 * Notification settings page setup function
 *
 */
function notifications_plugin_pagesetup() {
	global $CONFIG;
	if (get_context() == 'settings') {
		add_submenu_item(elgg_echo('notifications:subscriptions:changesettings'), $CONFIG->wwwroot . "pg/notifications/personal");
		if (is_plugin_enabled('groups')) {
			add_submenu_item(elgg_echo('notifications:subscriptions:changesettings:groups'), $CONFIG->wwwroot . "pg/notifications/group");
		}
	}
}


register_elgg_event_handler('init', 'system', 'notifications_plugin_init', 1000);

// Register action
register_action("notificationsettings/save", FALSE, $CONFIG->pluginspath . "notifications/actions/save.php");
register_action("notificationsettings/groupsave", FALSE, $CONFIG->pluginspath . "notifications/actions/groupsave.php");
