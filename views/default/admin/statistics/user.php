<?php
// Banned user count
$users_banned = elgg_count_entities([
	'type' => 'user',
	'metadata_name_value_pairs' => ['banned' => 'yes'],
]);

// Active user count
$users_active = elgg_count_entities([
	'type' => 'user',
	'metadata_name_value_pairs' => ['banned' => 'no'],
]);

// Unverified user count
$users_unverified = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function () {
	return elgg_count_entities([
		'type' => 'user',
		'metadata_name_value_pairs' => ['validated' => false],
	]);
});

// Total user count (Enable & Disabled)
$total_users = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function () {
	return elgg_count_entities([
		'type' => 'user',
	]);
});

// Enabled user count
$users_enabled = elgg_count_entities([
	'type' => 'user',
]);

// Disabled user count
$users_disabled = $total_users - $users_enabled;

$table = elgg_format_element('tr', [], implode(PHP_EOL, [
	elgg_format_element('td', [], elgg_format_element('b', [], elgg_echo('active'))),
	elgg_format_element('td', [], $users_active),
]));
$table .= elgg_format_element('tr', [], implode(PHP_EOL, [
	elgg_format_element('td', [], elgg_format_element('b', [], elgg_echo('status:disabled'))),
	elgg_format_element('td', [], $users_disabled),
]));
$table .= elgg_format_element('tr', [], implode(PHP_EOL, [
	elgg_format_element('td', [], elgg_format_element('b', [], elgg_echo('unvalidated'))),
	elgg_format_element('td', [], $users_unverified),
]));
$table .= elgg_format_element('tr', [], implode(PHP_EOL, [
	elgg_format_element('td', [], elgg_format_element('b', [], elgg_echo('banned'))),
	elgg_format_element('td', [], $users_banned),
]));
$table .= elgg_format_element('tr', [], implode(PHP_EOL, [
	elgg_format_element('td', [], elgg_format_element('b', [], elgg_echo('total'))),
	elgg_format_element('td', [], $total_users),
]));
$table = elgg_format_element('table', ['class' => 'elgg-table-alt'], $table);

echo elgg_view_module('info', elgg_echo('admin:statistics:label:user'), $table);
