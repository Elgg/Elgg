<?php
/**
 * Show a listing of all banned users
 */

echo elgg_view('admin/users/header', [
	'filter' => 'banned',
]);

echo elgg_view_form('admin/users/bulk_actions', [
	'prevent_double_submit' => false,
], [
	'filter' => 'banned',
	'options' => [
		'metadata_name_value_pairs' => [
			'banned' => 'yes',
		],
	],
	'menu_vars' => [
		'show_ban' => false,
	],
]);
