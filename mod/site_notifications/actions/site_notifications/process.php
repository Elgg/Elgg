<?php
/**
 * Process a set of site notifications
 */

$notification_guids = get_input('notification_id', []);

if (!$notification_guids) {
	return elgg_error_response(elgg_echo('site_notifications:error:notifications_not_selected'));
}

/** @var \ElggBatch $batch */
$batch = elgg_get_entities([
	'type' => 'object',
	'subtype' => 'site_notification',
	'guids' => $notification_guids,
	'limit' => false,
	'batch' => true,
	'batch_inc_offset' => false,
]);
/** @var \SiteNotification $entity */
foreach ($batch as $entity) {
	if (!$entity->canDelete()) {
		$batch->reportFailure();
		continue;
	}
	
	if (!$entity->delete()) {
		$batch->reportFailure();
	}
}

return elgg_ok_response('', elgg_echo('site_notifications:success:delete'));
