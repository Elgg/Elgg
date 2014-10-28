<?php
/**
 * Site notifications
 *
 * @todo check for notifications when setting topbar icon
 * @todo add a remove visible and all notifications button
 */

elgg_register_event_handler('init', 'system', 'site_notifications_init');

function site_notifications_init() {
	// register as a notification type
	elgg_register_notification_method('site');
	elgg_register_plugin_hook_handler('send', 'notification:site', 'site_notifications_send');

	elgg_register_page_handler('site_notifications', 'site_notifications_page_handler');

	elgg_extend_view('css/elgg', 'site_notifications/css');

	$js = elgg_get_simplecache_url('js', 'site_notifications');
	elgg_register_js('elgg.site_notifications', $js, 'footer');

	site_notifications_set_topbar();

	$actions_base = elgg_get_plugins_path() . 'site_notifications/actions/site_notifications';
	elgg_register_action('site_notifications/delete', "$actions_base/delete.php");
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

	elgg_gatekeeper();

	if (!isset($segments[1])) {
		$segments[1] = elgg_get_logged_in_user_entity()->username;
	}

	$user = get_user_by_username($segments[1]);
	if (!$user) {
		return false;
	}

	elgg_set_page_owner_guid($user->guid);
	elgg_load_js('elgg.site_notifications');

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
			'text' => elgg_view_icon('info') . elgg_echo('site_notifications:topbar'),
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

	$ia = elgg_set_ignore_access(true);
	$note = SiteNotificationFactory::create($recipient, $message, $actor, $object);
	elgg_set_ignore_access($ia);
	if ($note) {
		return true;
	}
}
