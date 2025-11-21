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

$owner = $entity->getOwnerEntity();
if ($owner instanceof \ElggUser && $owner->getGroups(['count' => true])) {
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
			'match_membership' => !elgg_is_admin_logged_in(),
		],
		'limit' => 1,
		'save_as_array' => false,
	]);
	
	echo elgg_view_field([
		'#type' => 'radio',
		'name' => 'destination_container_guid',
		'options_values' => [
			$owner->guid => elgg_echo('trash:restore:owner', [$owner->getDisplayName()]),
		],
	]);
} else {
	echo elgg_view('output/longtext', [
		'value' => elgg_echo('trash:restore:container:owner'),
	]);
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'destination_container_guid',
		'value' => $owner->guid,
	]);
}

// form footer
$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('save'),
	'confirm' => elgg_echo('restoreandmoveconfirm'),
]);

elgg_set_form_footer($footer);
