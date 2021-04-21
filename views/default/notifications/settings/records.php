<?php
/**
 * Show the different notification settings / preferences for the given user
 *
 * For correct rendering and generic handling of the saving of the settings use the 'notifications/settings/record' view
 *
 * @uses $vars['entity'] the user to set the setings for
 */

$user = elgg_extract('entity', $vars);
if (!$user instanceof \ElggUser) {
	return;
}

$methods = elgg_get_notification_methods();
if (empty($methods)) {
	return;
}

// generic personal preferences
$params = $vars;
$params['description'] = elgg_echo('usersettings:notifications:default:description');
$params['purpose'] = 'default';

echo elgg_view('notifications/settings/record', $params);

// content creation preferences
$params = $vars;
$params['description'] = elgg_echo('usersettings:notifications:content_create:description');
$params['purpose'] = 'content_create';

echo elgg_view('notifications/settings/record', $params);

// comment creation preferences
$params = $vars;
$params['description'] = elgg_echo('usersettings:notifications:create_comment:description');
$params['purpose'] = 'create_comment';

echo elgg_view('notifications/settings/record', $params);
