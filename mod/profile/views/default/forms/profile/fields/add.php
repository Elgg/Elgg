<?php
/**
 * Add a new field to the set of custom profile fields
 */

// need to have a gatekeeper as this form is used via ajax
elgg_admin_gatekeeper();

$id = elgg_extract('id', $vars);
$label = $id ? elgg_get_config("admin_defined_profile_$id") : null;
$type = $id ? elgg_get_config("admin_defined_profile_type_$id") : null;

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'id',
	'value' => $id,
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('profile:label'),
	'name' => 'label',
	'value' => $label,
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
	'value' => $type,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
