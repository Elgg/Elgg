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
$users_unverified = elgg_count_entities([
	'type' => 'user',
	'metadata_name_value_pairs' => ['validated' => false],
]);

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

$active_title = elgg_echo('active');
$total_title = elgg_echo('total');
$unverified_title = elgg_echo('unvalidated');
$banned_title = elgg_echo('banned');
$disabled_title = elgg_echo('status:disabled');

$body = <<<__HTML
<table class="elgg-table-alt">
	<tr>
		<td><b>{$active_title} :</b></td>
		<td>{$users_active}</td>
	</tr>
	<tr>
		<td><b>{$disabled_title} :</b></td>
		<td>{$users_disabled}</td>
	</tr>
	<tr>
		<td><b>{$unverified_title} :</b></td>
		<td>{$users_unverified}</td>
	</tr>
	<tr>
		<td><b>{$banned_title} :</b></td>
		<td>{$users_banned}</td>
	</tr>
	<tr>
		<td><b>{$total_title} :</b></td>
		<td>{$total_users}</td>
	</tr>
</table>
__HTML;

echo elgg_view_module('info', elgg_echo('admin:statistics:label:user'), $body);
