<?php

	/**
	 * Elgg notifications plugin save action
	 * 
	 * @package ElggNotifications
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	// Restrict to logged in users
		gatekeeper();
		
	// Get people
		$subscriptions = get_input('subscriptions');
		
	// Clear existing subscriptions
		global $SESSION;
		remove_entity_relationships($SESSION['user']->guid,'notify',false);
		
	// Add new ones
		if (is_array($subscriptions) && !empty($subscriptions)) {
			foreach($subscriptions as $subscription) {
				register_notification_interest($SESSION['user']->guid, $subscription);
			}
		}
		
		system_message(elgg_echo('notifications:subscriptions:success'));
		
		forward($_SERVER['HTTP_REFERER']);

?>