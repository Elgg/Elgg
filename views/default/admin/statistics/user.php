<?php
// Work out number of users
$users_stats = get_number_users();
$total_users = get_number_users(true);

$active_title = elgg_echo('active');
$total_title = elgg_echo('total');

$body = <<<__HTML
<table class="elgg-table-alt">
	<tr>
		<td><b>{$active_title} :</b></td>
		<td>{$users_stats}</td>
	</tr>
	<tr>
		<td><b>{$total_title} :</b></td>
		<td>{$total_users}</td>
	</tr>
</table>
__HTML;

echo elgg_view_module('info', elgg_echo('admin:statistics:label:user'), $body);
