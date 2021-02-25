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

foreach ($subscriptions as $target_guid => $keys) {
	foreach ($keys as $key => $preferred_methods) {
		list (, $type, $subtype, $action) = explode(':', $key);
		
		if (!is_array($preferred_methods)) {
			$preferred_methods = [];
		}
		
		elgg_log("{$target_guid}:{$key}:{$type}:{$subtype}:{$action} => " . var_export($preferred_methods, true), 'ERROR');
		foreach ($methods as $method) {
			if (in_array($method, $preferred_methods)) {
				elgg_add_subscription($user->guid, $method, $target_guid, $type, $subtype, $action);
			} else {
				elgg_remove_subscription($user->guid, $method, $target_guid, $type, $subtype, $action);
			}
		}
	}
}

return elgg_ok_response('', elgg_echo('notifications:subscriptions:success'));
