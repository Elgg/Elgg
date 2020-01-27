<?php

elgg_require_css('admin/users/newest');

echo elgg_view_form('admin/users/search', [
	'method' => 'GET',
	'action' => 'admin/users/newest',
]);

$query = get_input('q');
$getter = $query ? 'elgg_search' : 'elgg_get_entities';

// newest users
echo elgg_list_entities([
	'type' => 'user',
	'subtype'=> null,
	'full_view' => false,
	'list_type' => 'table',
	'columns' => [
		elgg()->table_columns->user(),
		elgg()->table_columns->username(),
		elgg()->table_columns->email(),
		elgg()->table_columns->time_created(null, [
			'format' => 'friendly',
		]),
	],
	'list_class' => 'elgg-newest-users',
	'query' => $query,
], $getter);
