<?php
/**
 * Provide a way of setting your default access
 */

$user = elgg_extract('entity', $vars);
if (!($user instanceof ElggUser)) {
	return;
}

$default_access = $user->getPrivateSetting('elgg_default_access');
if ($default_access === null) {
	$default_access = elgg_get_config('default_access');
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $user->guid,
]);

echo elgg_view_field([
	'#type' => 'access',
	'#label' => elgg_echo('default_access:label'),
	'name' => 'default_access',
	'value' => $default_access,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);
elgg_set_form_footer($footer);
