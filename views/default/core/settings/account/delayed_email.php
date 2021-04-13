<?php
/**
 * Set the desired interval for delayed email notifications
 *
 * @uses $vars['entity']      the user to configure for
 * @uses $vars['show_module'] show the settings in an module (default: true)
 */

$user = elgg_extract('entity', $vars);
if (!$user instanceof ElggUser || !(bool) elgg_get_config('enable_delayed_email')) {
	return;
}

$content = elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('usersettings:delayed_email:interval'),
	'#help' => elgg_echo('usersettings:delayed_email:interval:help'),
	'name' => 'delayed_email_interval',
	'options_values' => [
		'daily' => elgg_echo('interval:daily'),
		'weekly' => elgg_echo('interval:weekly'),
	],
	'value' => $user->getPrivateSetting('delayed_email_interval'),
]);

if (!(bool) elgg_extract('show_module', $vars, true)) {
	echo $content;
	return;
}

echo elgg_view_module('info', elgg_echo('usersettings:delayed_email'), $content);
