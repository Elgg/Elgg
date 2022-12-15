<?php
/**
 * Statistics about this user.
 *
 * @uses $vars['entity'] The user entity for whom to show statistics
 */

$user = elgg_extract('entity', $vars, elgg_get_page_owner_entity()); // page owner for BC reasons
if (!$user instanceof \ElggUser) {
	return;
}

$time_created = elgg_view('output/date', [
	'value' => $user->time_created,
	'format' => DATE_RFC2822,
]);
$last_login = elgg_view('output/date', [
	'value' => $user->last_login,
	'format' => DATE_RFC2822,
]);

if ($user->guid === elgg_get_logged_in_user_guid()) {
	$title = elgg_echo('usersettings:statistics:yourdetails');
} else {
	$title = elgg_echo('usersettings:statistics:details:user', [$user->getDisplayName()]);
}

$table = elgg_format_element('tr', [], implode(PHP_EOL, [
	elgg_format_element('td', [], elgg_echo('usersettings:statistics:label:name')),
	elgg_format_element('td', [], $user->getDisplayName()),
]));
$table .= elgg_format_element('tr', [], implode(PHP_EOL, [
	elgg_format_element('td', [], elgg_echo('usersettings:statistics:label:email')),
	elgg_format_element('td', [], $user->email),
]));
$table .= elgg_format_element('tr', [], implode(PHP_EOL, [
	elgg_format_element('td', [], elgg_echo('usersettings:statistics:label:membersince')),
	elgg_format_element('td', [], $time_created),
]));
$table .= elgg_format_element('tr', [], implode(PHP_EOL, [
	elgg_format_element('td', [], elgg_echo('usersettings:statistics:label:lastlogin')),
	elgg_format_element('td', [], $last_login),
]));
$table = elgg_format_element('table', ['class' => 'elgg-table-alt'], $table);

echo elgg_view_module('info', $title, $table);
