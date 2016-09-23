<?php

$view = "admin/users/newest.css";
elgg_register_css($view, elgg_get_simplecache_url($view));
elgg_load_css($view);

// newest users
$users = elgg_list_entities([
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
]);

echo elgg_view_module('inline', elgg_echo('admin:users:newest'), $users);
