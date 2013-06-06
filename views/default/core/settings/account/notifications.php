<?php
/**
 * User settings for notifications.
 *
 * @package Elgg
 * @subpackage Core
 */

$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();
$notification_settings = get_user_notification_settings(elgg_get_page_owner_guid());

$title = elgg_echo('notifications:usersettings');

$rows = '';

// Loop through options
foreach ($NOTIFICATION_HANDLERS as $k => $v) {

	if ($notification_settings->$k) {
		$val = "yes";
	} else {
		$val = "no";
	}

	$radio = elgg_view('input/radio', array(
		'name' => "method[$k]",
		'value' => $val,
		'options' => array(
			elgg_echo('option:yes') => 'yes',
			elgg_echo('option:no') => 'no'
		),
	));

	$cells = '<td class="prm pbl">' . elgg_echo("notification:method:$k") . ': </td>';
	$cells .= "<td>$radio</td>";

	$rows .= "<tr>$cells</tr>";
}

$content = '';
$content .= "<table>$rows</table>";

echo elgg_view_module('info', $title, $content);
