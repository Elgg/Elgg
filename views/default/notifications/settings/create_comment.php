<?php
/**
 * Extension on the personal notification settings to manage default comment create notification settings
 */

$params = $vars;
$params['description'] = elgg_echo('notification:settings:create_comment:description');
$params['purpose'] = 'create_comment';

echo elgg_view('notifications/settings/record', $params);
