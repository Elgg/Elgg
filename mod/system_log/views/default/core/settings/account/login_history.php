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

$body = '<table class="elgg-table">';
$body .= '<thead><tr>';
$body .= elgg_format_element('th', [], elgg_echo('usersettings:statistics:login_history:date'));
$body .= elgg_format_element('th', [], elgg_echo('usersettings:statistics:login_history:ip'));
$body .= '</tr></thead>';
$body .= '<tbody>';

foreach ($log as $entry) {
	$ip_address = $entry->ip_address ?: elgg_echo('unknown');
		
	$time = date(elgg_echo('friendlytime:date_format'), $entry->time_created);
	
	$body .= "<tr><td>{$time}</td><td>{$ip_address}</td></tr>";
}

$body .= '</tbody></table>';

echo elgg_view_module('info', elgg_echo('usersettings:statistics:login_history'), $body);
