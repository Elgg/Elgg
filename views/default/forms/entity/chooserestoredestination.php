<?php

/**
 * Body of the form for choosing restore destination.
 */

elgg_gatekeeper();

$title = get_input('title', '');
$address = get_input('address', '');
$entity_guid = (int) get_input('entity_guid');
$deleter_guid = (int) get_input('deleter_guid');
$entity_owner_guid = (int) get_input('entity_owner_guid');

// If an admin is currently logged in, he will have the rights to all active groups.
// If a mere user is currently logged in, he will only have the rights to groups he has joined.
if (elgg_is_admin_logged_in()) {
	$soft_deleted_groups = elgg_get_entities([
		'type' => 'group',
		'inverse_relationship' => false,
		'sort_by' => [
			'property' => 'name',
			'direction' => 'ASC',
		],
		'no_results' => elgg_echo('groups:none'),
	]);
} else {
	$soft_deleted_groups = elgg_get_entities([
		'type' => 'group',
		'relationship' => 'member',
		'relationship_guid' => elgg_get_logged_in_user_guid(),
		'inverse_relationship' => false,
		'sort_by' => [
			'property' => 'name',
			'direction' => 'ASC',
		],
		'no_results' => elgg_echo('groups:none'),
	]);
}

$destination_container_names = [$entity_owner_guid => 'assign back to creator'];
foreach ($soft_deleted_groups as $group) {
	$destination_container_names += [$group->guid => $group->getDisplayName()];
}


$fields = [
	[
		'#type' => 'select',
		'#label' => elgg_echo('Destination group'),
		'required' => true,
		'name' => 'destination_container_guid',
		'options_values' => $destination_container_names,
	],
	[
		'#type' => 'hidden',
		'name' => 'entity_guid',
		'value' => $entity_guid,
	],
	[
		'#type' => 'hidden',
		'name' => 'deleter_guid',
		'value' => $deleter_guid,
	],
];

foreach ($fields as $field) {
	echo elgg_view_field($field);
}


// TODO: elgg_echo is currently hardcoded and not translated
$footer = elgg_view('input/submit', [
	'value' => elgg_echo('Confirm'),
]);
$footer .= elgg_view('input/button', [
	'class' => 'elgg-button-cancel',
	'value' => elgg_echo('Cancel'),
]);

elgg_set_form_footer($footer);

