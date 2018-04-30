<?php
// Work out number of users
$users_stats = get_number_users();
$total_users = get_number_users(true);

$active_title = elgg_echo('active');
$total_title = elgg_echo('total');

$table = new \Elgg\Markup\Table();
$table->addClass('elgg-table-alt')
	->addRow([
		new \Elgg\Markup\Bold($active_title),
		$users_stats
	])
	->addRow([
		new \Elgg\Markup\Bold($total_title),
		$total_users
	]);

echo elgg_view_module('info', elgg_echo('admin:statistics:label:user'), $table->render());
