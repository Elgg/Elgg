<?php
/**
 * User settings for notifications.
 *
 * @package Elgg
 * @subpackage Core
 */

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner instanceof ElggUser) {
	return;
}

$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();
$notification_settings = $page_owner->getNotificationSettings();

$title = elgg_echo('notifications:usersettings');

$rows = '';

// Loop through options
foreach ($NOTIFICATION_HANDLERS as $k => $v) {
	if ($notification_settings[$k]) {
		$val = "yes";
	} else {
		$val = "no";
	}

	$radio = elgg_view('input/radio', [
		'name' => "method[$k]",
		'value' => $val,
		'options' => [
			elgg_echo('option:yes') => 'yes',
			elgg_echo('option:no') => 'no'
		],
	]);

	$cells = '<td class="prm pbl">' . elgg_echo("notification:method:$k") . ': </td>';
	$cells .= "<td>$radio</td>";

	$rows .= "<tr>$cells</tr>";
}

$content = "<table>$rows</table>";

echo elgg_view_module('info', $title, $content);
