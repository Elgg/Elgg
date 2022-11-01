<?php
/**
 * Show a list of online users (active within the past 10 minutes)
 */

use Elgg\Database\QueryBuilder;
use Elgg\Values;

elgg_unregister_event_handler('register', 'menu:filter:admin/users', 'Elgg\Menus\FilterSortItems::registerTimeCreatedSorting');
elgg_register_event_handler('register', 'menu:filter:admin/users', 'Elgg\Menus\FilterSortItems::registerLastActionSorting', 499);

echo elgg_view('admin/users/header', [
	'filter' => 'online',
]);

echo elgg_view_form('admin/users/bulk_actions', [
	'prevent_double_submit' => false,
], [
	'filter' => 'online',
	'options' => [
		'wheres' => [
			function(QueryBuilder $qb, $main_alias) {
				return $qb->compare("{$main_alias}.last_action", '>=', Values::normalizeTimestamp('-10 minutes'), ELGG_VALUE_TIMESTAMP);
			}
		],
		'sort_by' => get_input('sort_by', [
			'property' => 'last_action',
			'property_type' => 'attribute',
			'direction' => 'desc',
		]),
	],
	'menu_vars' => [
		'show_unban' => false,
	],
]);
