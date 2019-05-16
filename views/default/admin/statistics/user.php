<?php
// Work out number of users
$users_active = get_number_users();
$total_users = get_number_users('total');
$users_unverified = get_number_users('unverified');
$users_banned = get_number_users('banned');

$active_title = elgg_echo('active');
$total_title = elgg_echo('total');
$unverified_title = elgg_echo('unvalidated');
$banned_title = elgg_echo('banned');

$body = <<<__HTML
<table class="elgg-table-alt">
	<tr>
		<td><b>{$active_title} :</b></td>
		<td>{$users_active}</td>
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
