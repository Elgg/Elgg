<?php
/**
 * Show a list of online users (active within the past 10 minutes)
 */

use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\QueryBuilder;
use Elgg\Values;

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
		'order_by' => new OrderByClause('e.last_action', 'DESC'),
	],
	'menu_vars' => [
		'show_unban' => false,
	],
]);
