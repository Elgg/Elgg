<?php
/**
 * Action for choosing destination to restore a post to.
 */

$guid = (int) get_input('entity_guid');
$destination_container_guid = (array) get_input('destination_container_guid');
$destination_container_guid = array_filter($destination_container_guid, function($value) {
	return is_numeric($value);
});
$destination_container_guid = array_map(function($value) {
	return (int) $value;
}, $destination_container_guid);

if (empty($guid) || empty($destination_container_guid)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$entity = elgg_call(ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES, function () use ($guid) {
	return get_entity($guid);
});
if (!$entity instanceof \ElggEntity || !$entity->isDeleted()) {
	return elgg_error_response(elgg_echo('entity:restore:item_not_found'));
}

$new_container = get_entity($destination_container_guid[0]);
if (!$new_container instanceof \ElggEntity) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if (!$new_container->canWriteToContainer(0, $entity->type, $entity->subtype)) {
	return elgg_error_response(elgg_echo('entity:restore:container_permission', [$new_container->getDisplayName()]));
}

// determine what name to show on success
$display_name = $entity->getDisplayName() ?: elgg_echo('entity:restore:item');

if (!$entity->restore()) {
	return elgg_error_response(elgg_echo('entity:restore:fail', [$display_name]));
}

$entity->container_guid = $new_container->guid;
if (!$entity->save()) {
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
