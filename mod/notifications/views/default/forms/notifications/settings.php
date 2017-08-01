<?php
/**
 * Saves personal notification settings
 *
 * @uses $vars['user'] Subscriber
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

$records = elgg_view('notifications/settings/personal', $vars);
if (elgg_is_active_plugin('friends')) {
	$records .= elgg_view('notifications/settings/collections', $vars);
}
if (elgg_view_exists('notifications/settings/other')) {
	$records .= elgg_view('notifications/settings/other', $vars);
}

echo elgg_format_element('div', [
	'class' => 'elgg-subscriptions',
], $records);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
