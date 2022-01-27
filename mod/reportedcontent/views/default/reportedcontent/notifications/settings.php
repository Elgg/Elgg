<?php
/**
 * Add a notification setting for admins on the notification settings page
 *
 * @uses $vars['entity'] the user for which to set the settings
 *
 * @since 4.2
 */

$user = elgg_extract('entity', $vars);
if (!$user instanceof \ElggUser || !$user->isAdmin()) {
	return;
}

$params = $vars;
$params['description'] = elgg_echo('reportedcontent:usersettings:notifications:reportedcontent:description');
$params['purpose'] = 'reportedcontent';

echo elgg_view('notifications/settings/record', $params);
