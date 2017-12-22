<?php
/**
 * Process a set of site notifications
 */

$notification_guids = get_input('notification_id', []);

if (!$notification_guids) {
	return elgg_error_response(elgg_echo('site_notifications:error:notifications_not_selected'));
}

foreach ($notification_guids as $guid) {
	$notification = get_entity($guid);
	if ($notification instanceof SiteNotification && $notification->canDelete()) {
		$notification->delete();
	}
}

return elgg_ok_response('', elgg_echo('site_notifications:success:delete'));
