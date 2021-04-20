<?php
/**
 * User settings for notifications.
 *
 * @uses $vars['entity'] the user to set settings for
 */

$user = elgg_extract('entity', $vars, elgg_get_page_owner_entity());
if (!$user instanceof ElggUser) {
	return;
}

$methods = elgg_get_notification_methods();
if (empty($methods)) {
	return;
}

$notification_settings = $user->getNotificationSettings();

$content = '';

// Loop through options
foreach ($methods as $method) {
	$content .= elgg_view_field([
		'#type' => 'radio',
		'#label' => elgg_echo("notification:method:{$method}"),
		'name' => "method[{$method}]",
		'value' => (bool) elgg_extract($method, $notification_settings, false) ? 'yes' : 'no',
		'options' => [
			elgg_echo('option:yes') => 'yes',
			elgg_echo('option:no') => 'no',
		],
		'align' => 'horizontal',
	]);
}

echo elgg_view_module('info', elgg_echo('notifications:usersettings'), $content);
