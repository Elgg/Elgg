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

	elgg_register_external_file('js', 'elgg.site_notifications', elgg_get_simplecache_url('site_notifications.js'), 'footer');

	site_notifications_set_topbar();
}

/**
 * Sets the topbar notify icon and text
 *
 * @return void
 */
function site_notifications_set_topbar() {
	
	$user = elgg_get_logged_in_user_entity();
	if (empty($user)) {
		return;
	}
	
	$count = elgg_count_entities([
		'type' => 'object',
		'subtype' => 'site_notification',
		'owner_guid' => $user->guid,
		'metadata_name_value_pairs' => [
			'read' => false,
		],
	]);
	
	elgg_register_menu_item('topbar', [
		'name' => 'site_notifications',
		'href' => elgg_generate_url('collection:object:site_notification:owner', [
			'username' => $user->username
		]),
		'text' => elgg_echo('site_notifications:topbar'),
		'icon' => 'bell',
		'badge' => $count ?: null,
		'priority' => 100,
	]);
}

/**
 * Create a site notification
 *
 * @param \Elgg\Hook $hook 'send', 'notification:site'
 *
 * @return void|true
 */
function site_notifications_send(\Elgg\Hook $hook) {
	/* @var Elgg\Notifications\Notification */
	$notification = $hook->getParam('notification');
	if ($notification->summary) {
		$message = $notification->summary;
	} else {
		$message = $notification->subject;
	}

	$object = null;
	$event = $hook->getParam('event');
	if (isset($event)) {
		$object = $event->getObject();
	}

	$actor = $notification->getSender();
	$recipient = $notification->getRecipient();
	$url = $notification->url;
	
	$note = elgg_call(ELGG_IGNORE_ACCESS, function() use ($recipient, $message, $actor, $object, $url) {
		return SiteNotificationFactory::create($recipient, $message, $actor, $object, $url);
	});
	
	if ($note instanceof SiteNotification) {
		return true;
	}
}

/**
 * Fixes unwanted menu items on the entity menu
 *
 * @param \Elgg\Hook $hook Hook
 *
 * @return void|\Elgg\Menu\MenuItems
 */
function site_notifications_register_entity_menu(\Elgg\Hook $hook) {
	$entity = $hook->getEntityParam();
	if (!$entity instanceof SiteNotification) {
		return;
	}
	
	/* @var $return \Elgg\Menu\MenuItems */
	$return = $hook->getValue();
	
	$return->remove('edit');
	
	$delete = $return->get('delete');
	if ($delete instanceof ElggMenuItem) {
		$delete->setLinkClass('site-notifications-delete');
		$delete->{"data-entity-ref"} = 'elgg-object-' . $entity->guid;
	}
	
	return $return;
}

return function() {
	elgg_register_event_handler('init', 'system', 'site_notifications_init');
};
