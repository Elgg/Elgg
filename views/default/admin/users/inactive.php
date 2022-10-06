<?php
/**
 * Show a listing of all inactive users (last login before a certain date)
 */

use Elgg\Values;

elgg_unregister_event_handler('register', 'menu:filter:admin/users', 'Elgg\Menus\FilterSortItems::registerTimeCreatedSorting');
elgg_register_event_handler('register', 'menu:filter:admin/users', 'Elgg\Menus\FilterSortItems::registerLastLoginSorting', 499);

$last_login_before = (int) get_input('last_login_before', Values::normalizeTimestamp('-120 days'));

echo elgg_view('admin/users/header', [
	'filter' => 'inactive',
	'show_search_form' => true,
	'additional_search_fields' => [[
		'#type' => 'date',
		'#label' => elgg_echo('admin:users:inactive:last_login_before'),
		'#help' => elgg_echo('admin:users:inactive:last_login_before:help'),
		'name' => 'last_login_before',
		'value' => $last_login_before,
		'timestamp' => true,
		'required' => true,
		'datepicker_options' => [
			'maxDate' => -1,
		],
	]],
]);

echo elgg_view_form('admin/users/bulk_actions', [
	'prevent_double_submit' => false,
], [
	'filter' => 'inactive',
	'options' => [
		'metadata_name_value_pairs' => [
			'name' => 'last_login',
			'value' => $last_login_before,
			'operand' => '<',
			'type' => ELGG_VALUE_INTEGER,
		],
		'created_before' => $last_login_before,
		'sort_by' => get_input('sort_by', [
			'property' => 'last_login',
			'direction' => 'asc',
		]),
		'columns' => [
			elgg()->table_columns->checkbox(elgg_view('input/checkbox', [
				'name' => 'user_guids',
				'title' => elgg_echo('table_columns:fromView:checkbox'),
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
			elgg()->table_columns->last_login(null, [
				'format' => 'friendly',
			]),
			elgg()->table_columns->entity_menu(null, [
				'add_user_hover_admin_section' => true,
				'admin_listing' => elgg_extract('filter', $vars, 'all'),
			]),
		],
	],
]);
