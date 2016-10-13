<?php
/**
 * Provide a way of setting your email
 */

$user = elgg_extract('entity', $vars);
if (!($user instanceof ElggUser)) {
	return;
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $user->guid,
]);

echo elgg_view_field([
	'#type' => 'email',
	'#label' => elgg_echo('email:address:label'),
	'name' => 'email',
	'required' => true,
	'value' => $user->email,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);
elgg_set_form_footer($footer);
