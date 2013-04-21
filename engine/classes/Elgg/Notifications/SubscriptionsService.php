<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Notifications
 * @since      1.9.0
 */
class Elgg_Notifications_SubscriptionsService {

	/**
	 * Get the subscriptions for this notification event
	 *
	 * The return array is of the form:
	 *
	 * array(
	 *     <user guid> => array('email', 'sms', 'ajax'),
	 * );
	 *
	 * @param Elgg_Notifications_Event $event Notification event
	 * @return array
	 */
	public function getSubscriptions(Elgg_Notifications_Event $event) {
		// @todo not implemented
		$users = elgg_get_entities(array('type' => 'user'));
		$subscriptions = array();
		foreach ($users as $user) {
			$subscriptions[$user->guid] = array('site');
		}
		return $subscriptions;
	}
}
