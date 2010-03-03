<?php

	/**
	 * Elgg notifications
	 * 
	 * @package ElggNotifications
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	// Restrict to logged in users
		gatekeeper();
		
		global $SESSION;
		
		global $NOTIFICATION_HANDLERS;
		foreach($NOTIFICATION_HANDLERS as $method => $foo) {
			$subscriptions[$method] = get_input($method.'subscriptions');
			$personal[$method] = get_input($method.'personal');
			$collections[$method] = get_input($method.'collections');
			
			$metaname = 'collections_notifications_preferences_' . $method;
			$_SESSION['user']->$metaname = $collections[$method];
			set_user_notification_setting($_SESSION['user']->guid, $method, ($personal[$method] == '1') ? true : false);
			remove_entity_relationships($SESSION['user']->guid,'notify' . $method, false, 'user');
		}
		
	// Add new ones
		foreach($subscriptions as $key => $subscription)
		if (is_array($subscription) && !empty($subscription)) {
			foreach($subscription as $subscriptionperson) {
				add_entity_relationship($_SESSION['user']->guid, 'notify' . $key, $subscriptionperson);
			}
		}
		
		system_message(elgg_echo('notifications:subscriptions:success'));
		
		forward($_SERVER['HTTP_REFERER']);

?>