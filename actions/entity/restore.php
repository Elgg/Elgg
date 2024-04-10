<?php
/**
 * Default entity restore action
 */

$guid = (int) get_input('guid');
$entity = elgg_call(ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES, function() use ($guid) {
	return get_entity($guid);
});
if (!$entity instanceof \ElggEntity || !$entity->isDeleted()) {
	return elgg_error_response(elgg_echo('entity:restore:item_not_found'));
}

if (!$entity->canEdit()) {
	return elgg_error_response(elgg_echo('entity:restore:permission_denied'));
}

// determine what name to show on success
$display_name = $entity->getDisplayName() ?: elgg_echo('entity:restore:item');

if (!$entity->restore()) {
	return elgg_error_response(elgg_echo('entity:restore:fail', [$display_name]));
}

$success_keys = [
	"entity:restore:{$entity->type}:{$entity->subtype}:success",
	"entity:restore:{$entity->type}:success",
	'entity:restore:success',
];

$message = '';
if (get_input('show_success', true)) {
	foreach ($success_keys as $success_key) {
		if (elgg_language_key_exists($success_key)) {
			$message = elgg_echo($success_key, [$display_name]);
			break;
		}
	}
}

return elgg_ok_response('', $message);
