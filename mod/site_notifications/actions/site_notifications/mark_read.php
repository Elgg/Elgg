<?php
/**
 * Process a set of site notifications to mark as read
 */

$notification_guids = (array) get_input('notification_id', []);

if (!$notification_guids) {
	return elgg_error_response(elgg_echo('site_notifications:error:notifications_not_selected'));
}

/* @var $batch \ElggBatch */
$batch = elgg_get_entities([
	'type' => 'object',
	'subtype' => 'site_notification',
	'guids' => $notification_guids,
	'limit' => false,
	'batch' => true,
]);
/* @var $entity \SiteNotification */
foreach ($batch as $entity) {
	if (!$entity->canEdit()) {
		$batch->reportFailure();
		continue;
	}
	
	$entity->read = true;
}

return elgg_ok_response('', elgg_echo('site_notifications:success:mark_read'));
