<?php
/**
 * Delete user confirmation form
 */

$fields = [
	[
		'#type' => 'hidden',
		'name' => 'guid',
		'value' => elgg_extract('guid', $vars),
	],
	[
		'#type' => 'checkbox',
		'#label' => elgg_echo('user:delete:confirm'),
		'required' => true,
	],
];

foreach ($fields as $field) {
	echo elgg_view_field($field);
}

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('delete'),
]);
elgg_set_form_footer($footer);
