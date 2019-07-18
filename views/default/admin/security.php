<?php

$params = $vars;
$params['selected'] = 'settings';
echo elgg_view('admin/security/tabs', $params);

echo elgg_view('output/longtext', [
	'value' => elgg_echo('admin:security:settings:description'),
]);

echo elgg_view_form('admin/security/settings');
