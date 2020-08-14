<?php

namespace Elgg\Notifications;

use Elgg\Database\QueryBuilder;

/**
 * Handles notifications when a users admin status changes
 *
 * @since 4.0
 */
class ChangeAdminNotification {
	
	/**
	 * Add the current site admins to the subscribers when making/removing an admin user
	 *
	 * @param \Elgg\Hook $hook 'get', 'subscribers'
	 *
	 * @return void|array
	 */
	public static function addSiteAdminSubscribers(\Elgg\Hook $hook) {
		
		if (!_elgg_services()->config->security_notify_admins) {
			return;
		}
		
		$event = $hook->getParam('event');
		if (!$event instanceof \Elgg\Notifications\SubscriptionNotificationEvent) {
			return;
		}
		
		if (!in_array($event->getAction(), ['make_admin', 'remove_admin'])) {
			return;
		}
		
		$user = $event->getObject();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		/* @var $admin_batch \ElggBatch */
		$admin_batch = elgg_get_admins([
			'limit' => false,
			'wheres' => [
				function (QueryBuilder $qb, $main_alias) use ($user) {
					return $qb->compare("{$main_alias}.guid", '!=', $user->guid, ELGG_VALUE_GUID);
				},
			],
			'batch' => true,
		]);
		
		$return_value = $hook->getValue();
		
		/* @var $admin \ElggUser */
		foreach ($admin_batch as $admin) {
			$return_value[$admin->guid] = ['email'];
		}
		
		return $return_value;
	}
	
	/**
	 * Prepare the notification content for site admins about making a site admin
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'notification:make_admin:user:user'
	 *
	 * @return void|\Elgg\Notifications\Notification
	 */
	public static function prepareMakeAdminNotificationToAdmin(\Elgg\Hook $hook) {
		
		$return_value = $hook->getValue();
		if (!$return_value instanceof \Elgg\Notifications\Notification) {
			return;
		}
		
		$recipient = $hook->getParam('recipient');
		$object = $hook->getParam('object');
		$actor = $hook->getParam('sender');
		$language = $hook->getParam('language');
		
		if (!($recipient instanceof \ElggUser) || !($object instanceof \ElggUser) || !($actor instanceof \ElggUser)) {
			return;
		}
		
		if ($recipient->guid === $object->guid) {
			// recipient is the user being acted on, this is handled elsewhere
			return;
		}
		
		$site = elgg_get_site_entity();
		
		$return_value->subject = elgg_echo('admin:notification:make_admin:admin:subject', [$site->getDisplayName()], $language);
		$return_value->body = elgg_echo('admin:notification:make_admin:admin:body', [
			$recipient->getDisplayName(),
			$actor->getDisplayName(),
			$object->getDisplayName(),
			$site->getDisplayName(),
			$object->getURL(),
			$site->getURL(),
		], $language);
	
		$return_value->url = elgg_normalize_url('admin/users/admins');
		
		return $return_value;
	}
	
	/**
	 * Prepare the notification content for site admins about removing a site admin
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'notification:remove_admin:user:user'
	 *
	 * @return void|\Elgg\Notifications\Notification
	 */
	public static function prepareRemoveAdminNotificationToAdmin(\Elgg\Hook $hook) {
		
		$return_value = $hook->getValue();
		if (!$return_value instanceof \Elgg\Notifications\Notification) {
			return;
		}
		
		$recipient = $hook->getParam('recipient');
		$object = $hook->getParam('object');
		$actor = $hook->getParam('sender');
		$language = $hook->getParam('language');
		
		if (!($recipient instanceof \ElggUser) || !($object instanceof \ElggUser) || !($actor instanceof \ElggUser)) {
			return;
		}
		
		if ($recipient->guid === $object->guid) {
			// recipient is the user being acted on, this is handled elsewhere
			return;
		}
		
		$site = elgg_get_site_entity();
		
		$return_value->subject = elgg_echo('admin:notification:remove_admin:admin:subject', [$site->getDisplayName()], $language);
		$return_value->body = elgg_echo('admin:notification:remove_admin:admin:body', [
			$recipient->getDisplayName(),
			$actor->getDisplayName(),
			$object->getDisplayName(),
			$site->getDisplayName(),
			$object->getURL(),
			$site->getURL(),
		], $language);
	
		$return_value->url = elgg_normalize_url('admin/users/admins');
		
		return $return_value;
	}
	
	/**
	 * Add the user to the subscribers when making/removing the admin role
	 *
	 * @param \Elgg\Hook $hook 'get', 'subscribers'
	 *
	 * @return void|array
	 */
	public static function addUserSubscriber(\Elgg\Hook $hook) {
		
		if (!_elgg_services()->config->security_notify_user_admin) {
			return;
		}
		
		$event = $hook->getParam('event');
		if (!$event instanceof \Elgg\Notifications\SubscriptionNotificationEvent) {
			return;
		}
		
		if (!in_array($event->getAction(), ['make_admin', 'remove_admin'])) {
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
	 * Prepare the notification content for the user being made as a site admins
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'notification:make_admin:user:user'
	 *
	 * @return void|\Elgg\Notifications\Notification
	 */
	public static function prepareMakeAdminNotificationToUser(\Elgg\Hook $hook) {
		
		$return_value = $hook->getValue();
		if (!$return_value instanceof \Elgg\Notifications\Notification) {
			return;
		}
		
		$recipient = $hook->getParam('recipient');
		$object = $hook->getParam('object');
		$actor = $hook->getParam('sender');
		$language = $hook->getParam('language');
		
		if (!($recipient instanceof \ElggUser) || !($object instanceof \ElggUser) || !($actor instanceof \ElggUser)) {
			return;
		}
		
		if ($recipient->guid !== $object->guid) {
			// recipient is some other user, this is handled elsewhere
			return;
		}
		
		$site = elgg_get_site_entity();
		
		$return_value->subject = elgg_echo('admin:notification:make_admin:user:subject', [$site->getDisplayName()], $language);
		$return_value->body = elgg_echo('admin:notification:make_admin:user:body', [
			$recipient->getDisplayName(),
			$actor->getDisplayName(),
			$site->getDisplayName(),
			$site->getURL(),
		], $language);
	
		$return_value->url = elgg_normalize_url('admin');
		
		return $return_value;
	}
	
	/**
	 * Prepare the notification content for the user being removed as a site admins
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'notification:remove_admin:user:user'
	 *
	 * @return void|\Elgg\Notifications\Notification
	 */
	public static function prepareRemoveAdminNotificationToUser(\Elgg\Hook $hook) {
		
		$return_value = $hook->getValue();
		if (!$return_value instanceof \Elgg\Notifications\Notification) {
			return;
		}
		
		$recipient = $hook->getParam('recipient');
		$object = $hook->getParam('object');
		$actor = $hook->getParam('sender');
		$language = $hook->getParam('language');
		
		if (!($recipient instanceof \ElggUser) || !($object instanceof \ElggUser) || !($actor instanceof \ElggUser)) {
			return;
		}
		
		if ($recipient->guid !== $object->guid) {
			// recipient is some other user, this is handled elsewhere
			return;
		}
		
		$site = elgg_get_site_entity();
		
		$return_value->subject = elgg_echo('admin:notification:remove_admin:user:subject', [$site->getDisplayName()], $language);
		$return_value->body = elgg_echo('admin:notification:remove_admin:user:body', [
			$recipient->getDisplayName(),
			$actor->getDisplayName(),
			$site->getDisplayName(),
			$site->getURL(),
		], $language);
	
		$return_value->url = '';
		
		return $return_value;
	}
}
