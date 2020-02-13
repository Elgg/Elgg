<?php

use Elgg\SystemLog\SystemLog;

$user = elgg_get_page_owner_entity();
if (!$user instanceof ElggUser) {
	return;
}

$log = SystemLog::instance()->getAll([
	'performed_by_guid' => $user->guid,
	'event' => 'login',
	'object_type' => 'user',
	'limit' => 20,
]);

if (empty($log)) {
	return;
}
$body = '<table class="elgg-table">';
$body .= '<thead><tr>';
$body .= '<th>' . elgg_echo('usersettings:statistics:login_history:date') . '</th><th>' . elgg_echo('usersettings:statistics:login_history:ip') . '</th>';
$body .= '</tr></thead>';
$body .= '<tbody>';
				
foreach ($log as $entry) {
	if ($entry->ip_address) {
		$ip_address = $entry->ip_address;
	} else {
		$ip_address = elgg_echo('unknown');
	}
	
	$time = date(elgg_echo('friendlytime:date_format'), $entry->time_created);
	
	$body .= "<tr><td>{$time}</td><td>{$ip_address}</td></tr>";
}

$body .= '</tbody></table>';

echo elgg_view_module('info', elgg_echo('usersettings:statistics:login_history'), $body);
