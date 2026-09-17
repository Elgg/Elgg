<?php
/**
 * Body of the form for choosing restore destination.
 */

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Exceptions\Http\EntityPermissionsException;

elgg_gatekeeper();

$entity_guid = (int) get_input('entity_guid');
$entity = elgg_call(ELGG_SHOW_DELETED_ENTITIES, function() use ($entity_guid) {
	return get_entity($entity_guid);
});
if (!$entity instanceof \ElggEntity) {
	throw new EntityNotFoundException();
}

if (!$entity->canEdit()) {
	throw new EntityPermissionsException();
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'entity_guid',
	'value' => $entity->guid,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('save'),
	'confirm' => elgg_echo('restoreandmoveconfirm'),
]);

$actor = elgg_get_logged_in_user_entity();
$owner = $entity->getOwnerEntity();
if (!$owner instanceof \ElggUser && $actor->getGroups(['count' => true])) {
	echo elgg_view('output/longtext', [
		'value' => elgg_echo('trash:restore:container:choose'),
	]);
	
	echo elgg_view_field([
		'#type' => 'grouppicker',
		'#label' => elgg_echo('trash:restore:group'),
		'#help' => elgg_echo('trash:restore:group:help'),
		'name' => 'destination_container_guid',
		'options' => [
			'match_target' => $actor->guid,
			'match_membership' => !$actor->isAdmin(),
		],
		'limit' => 1,
		'save_as_array' => false,
	]);
} elseif ($owner instanceof \ElggUser && $owner->getGroups(['count' => true])) {
	echo elgg_view('output/longtext', [
		'value' => elgg_echo('trash:restore:container:choose'),
	]);
	
	echo elgg_view_field([
		'#type' => 'radio',
		'name' => 'destination_container_guid',
		'value' => 'group',
		'options_values' => [
			'group' => elgg_echo('trash:restore:container:group'),
		],
	]);
	
	echo elgg_view_field([
		'#type' => 'grouppicker',
		'#label' => elgg_echo('trash:restore:group'),
		'#help' => elgg_echo('trash:restore:group:help'),
		'name' => 'destination_container_guid',
		'options' => [
			'match_target' => $owner->guid,
			'match_membership' => !$actor->isAdmin(),
		],
		'limit' => 1,
		'save_as_array' => false,
	]);
	
	if ($owner->canWriteToContainer($owner->guid, $entity->type, $entity->subtype)) {
		echo elgg_view_field([
			'#type' => 'radio',
			'name' => 'destination_container_guid',
			'options_values' => [
				$owner->guid => elgg_echo('trash:restore:owner', [$owner->getDisplayName()]),
			],
		]);
	}
} elseif ($owner?->canWriteToContainer($owner->guid, $entity->type, $entity->subtype)) {
	echo elgg_view('output/longtext', [
		'value' => elgg_echo('trash:restore:container:owner'),
	]);
	
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'destination_container_guid',
		'value' => $owner->guid,
	]);
} else {
	echo elgg_view('output/longtext', [
		'value' => elgg_echo('trash:restore:container:unknown'),
	]);
	
	$footer = elgg_view_field([
		'#type' => 'reset',
		'text' => elgg_echo('cancel'),
		'onclick' => '$.colorbox.close();',
	]);
}

elgg_set_form_footer($footer);
