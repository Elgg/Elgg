<?php

use Elgg\SystemLog\SystemLog;

$user = elgg_extract('entity', $vars, elgg_get_page_owner_entity()); // page owner for BC reasons
if (!$user instanceof \ElggUser) {
	return;
}

$log = SystemLog::instance()->getAll([
	'object_id' => $user->guid,
	'event' => 'login:user',
	'object_type' => 'user',
	'limit' => 20,
]);

if (empty($log)) {
	return;
}

$show_ip = (bool) elgg_get_plugin_setting('enable_ip_logging', 'system_log');

$header = [];
$header[] = elgg_format_element('th', [], elgg_echo('usersettings:statistics:login_history:date'));
if ($show_ip) {
	$header[] = elgg_format_element('th', [], elgg_echo('usersettings:statistics:login_history:ip'));
}

$header = elgg_format_element('tr', [], implode(PHP_EOL, $header));
$header = elgg_format_element('thead', [], $header);

$rows = [];
foreach ($log as $entry) {
	$row = [];
	
	$row[] = elgg_format_element('td', [], date(elgg_echo('friendlytime:date_format'), $entry->time_created));
	
	if ($show_ip) {
		$row[] = elgg_format_element('td', [], $entry->ip_address ?: elgg_echo('unknown'));
	}
	
	$rows[] = elgg_format_element('tr', [], implode(PHP_EOL, $row));
}

$body = elgg_format_element('tbody', [], implode(PHP_EOL, $rows));

$table = elgg_format_element('table', ['class' => 'elgg-table'], $header . $body);

echo elgg_view_module('info', elgg_echo('usersettings:statistics:login_history'), $table);
