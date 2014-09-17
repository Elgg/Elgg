<?php
/**
 * Process a set of site notifications
 */

$notification_guids = get_input('notification_id', array());

if (!$notification_guids) {
	register_error(elgg_echo('site_notifications:error:notifications_not_selected'));
	forward(REFERER);
}

$success_msg = elgg_echo('site_notifications:success:delete');
foreach ($notification_guids as $guid) {
	$notification = get_entity($guid);
	if (elgg_instanceof($notification, 'object', 'site_notification') && $notification->canEdit()) {
		$notification->delete();
	}
}

system_message($success_msg);
forward(REFERER);
