<?php
/**
 * Saves subscription record notification settings
 */

$guid = (int) get_input('guid');
$user = get_user($guid);

if (!$user || !$user->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'), '', 403);
}

$methods = elgg_get_notification_methods();
if (empty($methods)) {
	return elgg_error_response('', REFERER, 404);
}

$subscriptions = (array) get_input('subscriptions', []);
if (empty($subscriptions)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$targets = elgg_get_entities([
	'guids' => array_keys($subscriptions),
	'limit' => false,
	'batch' => true,
]);

/* @var $target \ElggEntity */
foreach ($targets as $target) {
	$keys = $subscriptions[$target->guid];
	
	foreach ($keys as $key => $preferred_methods) {
		list (, $type, $subtype, $action) = explode(':', $key);
		
		if (!is_array($preferred_methods)) {
			$preferred_methods = [];
		}
		
		foreach ($methods as $method) {
			if (in_array($method, $preferred_methods)) {
				$target->addSubscription($user->guid, $method, $type, $subtype, $action);
			} else {
				$target->removeSubscription($user->guid, $method, $type, $subtype, $action);
			}
		}
	}
}

return elgg_ok_response('', elgg_echo('notifications:subscriptions:success'));
