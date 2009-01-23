<?php

	/**
	 * Elgg notifications plugin
	 * 
	 * @package ElggNotifications
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */


	/**
	 * Notification settings page setup function
	 *
	 */
		function notifications_plugin_pagesetup() {
			global $CONFIG;
			if (get_context() == 'settings')
				add_submenu_item(elgg_echo('notifications:subscriptions:changesettings'), $CONFIG->wwwroot . "mod/notifications/",'notifications');
		}

		register_elgg_event_handler('pagesetup','system','notifications_plugin_pagesetup',1000);

	// Register action
		global $CONFIG;
		register_action("notificationsettings/save",false,$CONFIG->pluginspath . "notifications/actions/save.php");
		
?>