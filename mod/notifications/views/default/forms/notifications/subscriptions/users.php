<?php
/**
 * Saves subscription notification settings by user
 *
 * @uses $vars['user'] ElggUser
 */
$user = elgg_extract('user', $vars);
if (!$user instanceof ElggUser) {
	return;
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $user->guid,
]);

echo elgg_format_element('p', [
	'class' => 'elgg-text-help',
], elgg_echo('notifications:subscriptions:description'));

echo elgg_view('notifications/subscriptions/users', $vars);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
