<?php
/**
 * Extension on the personal notification settings to manage default group notification settings
 */

$params = $vars;
$params['description'] = elgg_echo('groups:usersettings:notification:group_join:description');
$params['purpose'] = 'group_join';

echo elgg_view('notifications/settings/record', $params);
