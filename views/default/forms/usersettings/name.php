<?php
/**
 * Provide a way of setting your full name.
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
	'#type' => 'text',
	'#label' => elgg_echo('name'),
	'name' => 'name',
	'required' => true,
	'value' => $user->name,
    'autofocus' =>true,
]);
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);
elgg_set_form_footer($footer);