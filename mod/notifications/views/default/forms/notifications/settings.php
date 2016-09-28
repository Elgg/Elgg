<?php

/**
 * Saves personal notification settings
 *
 * @uses $vars['user'] Subscriber
 */
$user = elgg_extract('user', $vars);
if (!elgg_instanceof($user, 'user')) {
	return;
}

echo elgg_view_input('hidden', [
	'name' => 'guid',
	'value' => $user->guid,
]);

$records = elgg_view('notifications/settings/personal', $vars);
$records .= elgg_view('notifications/settings/collections', $vars);
$records .= elgg_view('notifications/settings/other', $vars);

echo elgg_format_element('div', [
	'class' => 'elgg-subscriptions',
], $records);

$footer = elgg_view_input('submit', [
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
