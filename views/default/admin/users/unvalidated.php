<?php
/**
 * List all unvalidated users in the admin area
 *
 * Allows for manual actions like validate/delete
 */

echo elgg_view('admin/users/header', [
	'filter' => 'unvalidated',
]);

echo elgg_view_form('admin/users/bulk_actions', [
	'prevent_double_submit' => false,
], [
	'filter' => 'unvalidated',
	'options' => [
		'columns' => [
			elgg()->table_columns->checkbox(elgg_view('input/checkbox', [
				'name' => 'user_guids',
				'title' => elgg_echo('table_columns:fromView:select'),
			]), [
				'name' => 'user_guids[]',
			]),
			elgg()->table_columns->icon(null, [
				'use_hover' => false,
			]),
			elgg()->table_columns->user(null, [
				'item_view' => 'user/default/admin_column',
			]),
			elgg()->table_columns->email(),
			elgg()->table_columns->time_created(null, [
				'format' => 'friendly',
			]),
			elgg()->table_columns->unvalidated_menu(),
		],
		'metadata_name_value_pairs' => [
			'validated' => false,
		],
	],
	'menu_vars' => [
		'show_ban' => false,
		'show_unban' => false,
		'show_validate' => true,
	],
	'no_results' => elgg_echo('admin:users:unvalidated:no_results'),
]);
