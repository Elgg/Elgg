<?php
/**
 * Save settings from the notifications/mute page
 */

$entity_guid = (int) get_input('entity_guid');
$recipient_guid = (int) get_input('recipient_guid');
$recipient = get_user($recipient_guid);

$muted_settings = (array) get_input('mute', []);
$hmac_token = get_input('hmac_token');

if (empty($entity_guid) || empty($recipient) || empty($muted_settings) || empty($hmac_token)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

// hmac to ensure no data was changed between the page and the action
$hmac = elgg_build_hmac([
	'entity_guid' => $entity_guid,
	'recipient_guid' => $recipient_guid,
]);

if (!$hmac->matchesToken($hmac_token)) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

elgg_call(ELGG_IGNORE_ACCESS, function() use ($muted_settings, $recipient_guid) {
	foreach ($muted_settings as $guid => $setting) {
		$entity = get_entity($guid);
		if (!$entity instanceof \ElggEntity) {
			continue;
		}
		
		if ($entity->hasMutedNotifications($recipient_guid) === (bool) $setting) {
			// no change in status
			continue;
		}
		
		if ((bool) $setting) {
			// mute
			$entity->muteNotifications($recipient_guid);
		} else {
			// unmute
			$entity->unmuteNotifications($recipient_guid);
		}
	}
});

return elgg_ok_response('', elgg_echo('notifications:mute:save:success'), elgg_get_site_url());
