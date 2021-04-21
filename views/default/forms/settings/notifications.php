<?php
/**
 * Notification settings form
 *
 * @uses $vars['entity'] the user to set the setings for
 */

$user = elgg_extract('entity', $vars);
if (!$user instanceof \ElggUser) {
	return;
}

elgg_require_js('forms/settings/notifications');
elgg_require_css('forms/settings/notifications');

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $user->guid,
]);

// Settings
echo elgg_format_element('div', [
	'class' => 'elgg-notifications-settings',
], elgg_view('notifications/settings/records', $vars));

echo elgg_view('notifications/settings/delayed_email', $vars);

echo elgg_view('notifications/settings/timed_muting', $vars);

// form footer
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
