<?php
/**
 * Set the desired interval for delayed email notifications
 *
 * @uses $vars['entity'] the user to configure for
 */

$user = elgg_extract('entity', $vars);
if (!$user instanceof ElggUser || !(bool) elgg_get_config('enable_delayed_email')) {
	return;
}

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('usersettings:delayed_email:interval'),
	'#help' => elgg_echo('usersettings:delayed_email:interval:help'),
	'#class' => 'ptl',
	'name' => 'delayed_email_interval',
	'options_values' => [
		'daily' => elgg_echo('interval:daily'),
		'weekly' => elgg_echo('interval:weekly'),
	],
	'value' => $user->getPrivateSetting('delayed_email_interval'),
]);
