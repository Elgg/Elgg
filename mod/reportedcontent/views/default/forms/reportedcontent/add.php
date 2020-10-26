<?php
/**
 * Elgg report content plugin form
 */

elgg_gatekeeper();

$title = get_input('title', '');
$address = get_input('address', '');
$entity_guid = (int) get_input('entity_guid');

$description = '';

$fields = [
	[
		'#type' => 'text',
		'#label' => elgg_echo('title'),
		'name' => 'title',
		'value' => $title,
		'required' => true,
	],
	[
		'#type' => 'url',
		'#label' => elgg_echo('reportedcontent:address'),
		'name' => 'address',
		'value' => $address,
		'readonly' => (bool) $address,
		'required' => true,
	],
	[
		'#type' => 'plaintext',
		'#label' => elgg_echo('reportedcontent:description'),
		'name' => 'description',
		'value' => $description,
	],
	[
		'#type' => 'hidden',
		'name' => 'entity_guid',
		'value' => $entity_guid,
	],
];

foreach ($fields as $field) {
	echo elgg_view_field($field);
}

$footer = elgg_view('input/submit', [
	'value' => elgg_echo('reportedcontent:report'),
]);
$footer .= elgg_view('input/button', [
	'class' => 'elgg-button-cancel',
	'value' => elgg_echo('cancel'),
]);

elgg_set_form_footer($footer);
