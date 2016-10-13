<?php
/**
 * Add a new field to the set of custom profile fields
 */

echo elgg_view('output/longtext', [
	'value' => elgg_echo('profile:explainchangefields'),
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('profile:label'),
	'name' => 'label',
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('profile:type'),
	'name' => 'type',
	'options_values' => [
		'text' => elgg_echo('profile:field:text'),
		'longtext' => elgg_echo('profile:field:longtext'),
		'tags' => elgg_echo('profile:field:tags'),
		'url' => elgg_echo('profile:field:url'),
		'email' => elgg_echo('profile:field:email'),
		'location' => elgg_echo('profile:field:location'),
		'date' => elgg_echo('profile:field:date'),
	],
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'name' => elgg_echo('add'),
	'value' => elgg_echo('add'),
]);

elgg_set_form_footer($footer);
