<?php
/**
 * Site notifications
 * 
 * @todo check for notifications when setting topbar icon
 * @todo display read and unread notifications differently
 * @todo add button for setting the read status of a notification
 * @todo add timestamp to display of notification
 * @todo add JavaScript to Ajaxify setting read status and maybe viewing
 */

elgg_register_event_handler('init', 'system', 'site_notifications_init');

function site_notifications_init() {
	// register as a notification type
	elgg_register_notification_method('site');
	elgg_register_plugin_hook_handler('send', 'notification:site', 'site_notifications_send');

	elgg_register_page_handler('site_notifications', 'site_notifications_page_handler');

	elgg_extend_view('css/elgg', 'site_notifications/css');

	site_notifications_set_topbar();
}

/**
 * Page handler
 * 
 * /site_notifications/view/<username>
 * 
 * @param array $segments URL segments
 * @return boolean
 */
function site_notifications_page_handler($segments) {
	$base = elgg_get_plugins_path() . 'site_notifications/pages/site_notifications';

	gatekeeper();

	if (!isset($segments[1])) {
		$segments[1] = elgg_get_logged_in_user_entity()->username;
	}

	$user = get_user_by_username($segments[1]);
	if (!$user) {
		return false;
	}

	elgg_set_page_owner_guid($user->guid);

	require "$base/view.php";

	return true;
}

/**
 * Sets the topbar notify icon and text
 */
function site_notifications_set_topbar() {
	if (elgg_is_logged_in()) {
		elgg_register_menu_item('topbar', array(
			'name' => 'site_notifications',
			'href' => 'site_notifications/view/' . elgg_get_logged_in_user_entity()->username,
			'text' => elgg_view_icon('mail') . elgg_echo('site_notifications:topbar'),
			'priority' => 150,
			'section' => 'alt',
		));	
	}
}

/**
 * Create a site notification
 * 
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param bool   $result Has the notification been sent
 * @param array  $params Hook parameters
 */
function site_notifications_send($hook, $type, $result, $params) {
	/* @var Elgg_Notifications_Notification */
	$notification = $params['notification'];
	if ($notification->summary) {
		$recipient = $notification->getRecipient();
		$message = $notification->summary;
		$actor = $notification->getSender();
		$event = $params['event'];
		$object = $event->getObject();

		$ia = elgg_set_ignore_access(true);
		$note = SiteNotificationFactory::create($recipient, $message, $actor, $object);
		elgg_set_ignore_access($ia);
		if ($note) {
			return true;
		}
	}
}
