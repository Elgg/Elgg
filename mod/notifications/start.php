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


	/**
	 * Notification settings page setup function
	 *
	 */
		function notifications_plugin_pagesetup() {
			global $CONFIG;
			if (get_context() == 'settings') {
				add_submenu_item(elgg_echo('notifications:subscriptions:changesettings'), $CONFIG->wwwroot . "mod/notifications/");
				if (is_plugin_enabled('groups'))
					add_submenu_item(elgg_echo('notifications:subscriptions:changesettings:groups'), $CONFIG->wwwroot . "mod/notifications/groups.php");
			}
		}
		
		function notifications_plugin_init() {
			elgg_extend_view('css','notifications/css');
			global $CONFIG;
			
			// Unset the default user settings hook
			if (isset($CONFIG->hooks['usersettings:save']['user']))
				foreach($CONFIG->hooks['usersettings:save']['user'] as $key => $function) {
					if ($function == 'notification_user_settings_save')
						unset($CONFIG->hooks['usersettings:save']['user'][$key]); 
				}
		}

		register_elgg_event_handler('pagesetup','system','notifications_plugin_pagesetup',1000);
		register_elgg_event_handler('init','system','notifications_plugin_init',1000);

	// Register action
		global $CONFIG;
		register_action("notificationsettings/save",false,$CONFIG->pluginspath . "notifications/actions/save.php");
		register_action("notificationsettings/groupsave",false,$CONFIG->pluginspath . "notifications/actions/groupsave.php");
		
?>