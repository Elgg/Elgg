<?php
/**
 * Handle subscribing to an entity
 */

$entity_guid = (int) get_input('guid');
$user_guid = (int) get_input('user_guid', elgg_get_logged_in_user_guid());
$methods = (array) get_input('methods', []);
$type = get_input('type');
$subtype = get_input('subtype');
$action = get_input('action');

$forward_url = get_input('forward_url');
$show_success = (bool) get_input('show_success', true);

$entity = get_entity($entity_guid);
if (!$entity instanceof \ElggEntity) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$user = get_user($user_guid);
if (!$user instanceof \ElggUser || !$user->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

$display_name = $entity->getDisplayName() ?: elgg_echo('item');

if (!$entity->addSubscription($user->guid, $methods, $type, $subtype, $action)) {
	return elgg_error_response(elgg_echo('entity:subscribe:fail', [$display_name]));
}

$success_keys = [
	"entity:subscribe:{$entity->type}:{$entity->subtype}:success",
	"entity:subscribe:{$entity->type}:success",
	'entity:subscribe:success',
];

$message = '';
if ($show_success) {
	foreach ($success_keys as $success_key) {
		if (elgg_language_key_exists($success_key)) {
			$message = elgg_echo($success_key, [$display_name]);
			break;
		}
	}
}

return elgg_ok_response('', $message, $forward_url);
