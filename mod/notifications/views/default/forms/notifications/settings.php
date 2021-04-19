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

echo elgg_format_element('div', [
	'class' => 'elgg-notifications-settings',
], elgg_view('notifications/settings/records', $vars));

$params = $vars;
$params['show_module'] = false;
$params['entity'] = $user;
echo elgg_view('core/settings/account/delayed_email', $params);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
