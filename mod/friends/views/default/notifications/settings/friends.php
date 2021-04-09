<?php
/**
 * Extension on the personal notification settings to manage default friends notification settings
 */

$params = $vars;
$params['description'] = elgg_echo('friends:notification:settings:description');
$params['purpose'] = 'friends';

echo elgg_view('notifications/settings/record', $params);
