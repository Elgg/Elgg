<?php
/**
 * Site notifications
 *
 * @todo check for notifications when setting topbar icon
 * @todo add a remove visible and all notifications button
 */

/**
 * Site notifications init
 *
 * @return void
 */
function site_notifications_init() {
	// register as a notification type
	elgg_register_notification_method('site');
	elgg_register_plugin_hook_handler('send', 'notification:site', 'site_notifications_send');
	
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'site_notifications_register_entity_menu');

	elgg_extend_view('elgg.css', 'site_notifications/css');

	$js = elgg_get_simplecache_url('site_notifications.js');
	elgg_register_js('elgg.site_notifications', $js, 'footer');

	site_notifications_set_topbar();
}

/**
 * Sets the topbar notify icon and text
 *
 * @return void
 */
function site_notifications_set_topbar() {
	
	if (!elgg_is_logged_in()) {
		return;
	}
	
	elgg_register_menu_item('topbar', [
		'name' => 'site_notifications',
		'href' => 'site_notifications/owner/' . elgg_get_logged_in_user_entity()->username,
		'text' => elgg_echo('site_notifications:topbar'),
		'icon' => 'bell',
		'priority' => 100,
	]);
}

/**
 * Create a site notification
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param bool   $result Has the notification been sent
 * @param array  $params Hook parameters
 *
 * @return void|true
 */
function site_notifications_send($hook, $type, $result, $params) {
	/* @var Elgg\Notifications\Notification */
	$notification = elgg_extract('notification', $params);
	if ($notification->summary) {
		$message = $notification->summary;
	} else {
		$message = $notification->subject;
	}

	if (isset($params['event'])) {
		$event = $params['event'];
		$object = $event->getObject();
	} else {
		$object = null;
	}

	$actor = $notification->getSender();
	$recipient = $notification->getRecipient();
	$url = $notification->url;
	
	$ia = elgg_set_ignore_access(true);
	$note = SiteNotificationFactory::create($recipient, $message, $actor, $object, $url);
	elgg_set_ignore_access($ia);
	if ($note) {
		return true;
	}
}

/**
 * Fixes unwanted menu items on the entity menu
 *
 * @param \Elgg\Hook $hook Hook
 *
 * @return void|\ElggMenuItem[]
 */
function site_notifications_register_entity_menu(\Elgg\Hook $hook) {
	$entity = $hook->getEntityParam();
	if (!($entity instanceof SiteNotification)) {
		return;
	}
	
	$return = $hook->getValue();
	foreach ($return as $index => $menu_item) {
		if ($menu_item->getName() === 'edit') {
			unset($return[$index]);
			continue;
		}
		
		if ($menu_item->getName() === 'delete') {
			$menu_item->setLinkClass('site-notifications-delete');
			$menu_item->{"data-entity-ref"} = 'elgg-object-' . $entity->guid;
		}
	}
	
	return $return;
}

return function() {
	elgg_register_event_handler('init', 'system', 'site_notifications_init');
};
