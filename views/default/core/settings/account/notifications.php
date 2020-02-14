<?php
/**
 * User settings for notifications.
 */

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner instanceof ElggUser) {
	return;
}

$methods = elgg_get_notification_methods();
if (empty($methods)) {
	return;
}

$notification_settings = $page_owner->getNotificationSettings();

$title = elgg_echo('notifications:usersettings');

$content = elgg_view('output/longtext', [
	'value' => elgg_echo('notifications:usersettings:description'),
]);

// Loop through options
$fields = [];
foreach ($methods as $method) {
	$fields[] = [
		'#type' => 'checkbox',
		'#label' => elgg_echo("notification:method:$method"),
		'name' => "method[$method]",
		'default' => 'no',
		'value' => 'yes',
		'checked' => (bool) elgg_extract($method, $notification_settings, false),
		'switch' => true,
	];
}

$content .= elgg_view_field([
	'#type' => 'fieldset',
	'fields' => $fields,
	'align' => 'horizontal',
]);

echo elgg_view_module('info', $title, $content);
