<?php

namespace Elgg\Notifications;

/**
 * Handles notifications when a user is unbanned
 *
 * @since 4.0
 */
class UnbanUserNotification {
	
	/**
	 * Add the user to the subscribers when (un)banning the account
	 *
	 * @param \Elgg\Hook $hook 'get', 'subscribers'
	 *
	 * @return void|array
	 */
	public static function getUnbanSubscribers(\Elgg\Hook $hook) {
		if (!_elgg_services()->config->security_notify_user_ban) {
			return;
		}
	
		$event = $hook->getParam('event');
		if (!$event instanceof \Elgg\Notifications\SubscriptionNotificationEvent) {
			return;
		}
	
		if ($event->getAction() !== 'unban') {
			return;
		}
	
		$user = $event->getObject();
		if (!$user instanceof \ElggUser) {
			return;
		}
	
		$return_value = $hook->getValue();
		
		$return_value[$user->guid] = ['email'];
	
		return $return_value;
	}
	
	/**
	 * Prepare the notification content for the user being unbanned
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'notification:unban:user:user'
	 *
	 * @return void|\Elgg\Notifications\Notification
	 */
	public static function prepareUnbanNotification(\Elgg\Hook $hook) {
		$return_value = $hook->getValue();
		if (!$return_value instanceof \Elgg\Notifications\Notification) {
			return;
		}
	
		$recipient = $hook->getParam('recipient');
		$object = $hook->getParam('object');
		$language = $hook->getParam('language');
	
		if (!($recipient instanceof \ElggUser) || !($object instanceof \ElggUser)) {
			return;
		}
	
		if ($recipient->guid !== $object->guid) {
			return;
		}
	
		$site = elgg_get_site_entity();
	
		$return_value->subject = elgg_echo('user:notification:unban:subject', [$site->getDisplayName()], $language);
		$return_value->body = elgg_echo('user:notification:unban:body', [
			$recipient->getDisplayName(),
			$site->getDisplayName(),
			$site->getURL(),
		], $language);
	
		$return_value->url = $recipient->getURL();
	
		return $return_value;
	}
}
