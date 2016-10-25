<?php
/**
 * Provide a way of setting your language prefs
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
	'#type' => 'select',
	'#label' => elgg_echo('user:language:label'),
	'name' => 'language',
	'value' => $user->language,
	'options_values' => get_installed_translations(),
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);
elgg_set_form_footer($footer);
