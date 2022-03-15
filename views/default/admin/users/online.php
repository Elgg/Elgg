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

$buttons = [
	[
		'#type' => 'submit',
		'icon' => 'ban',
		'value' => elgg_echo('ban'),
		'formaction' => elgg_generate_action_url('admin/user/bulk/ban', [], false),
	],
	[
		'#type' => 'submit',
		'icon' => 'delete',
		'class' => 'elgg-button-delete',
		'value' => elgg_echo('delete'),
		'formaction' => elgg_generate_action_url('admin/user/bulk/delete', [], false),
		'confirm' => elgg_echo('deleteconfirm:plural'),
	],
];

echo elgg_view_form('admin/users/bulk_actions', [
	'prevent_double_submit' => false,
], [
	'buttons' => $buttons,
	'filter' => 'online',
	'options' => [
		'wheres' => [
			function(QueryBuilder $qb, $main_alias) {
				return $qb->compare("{$main_alias}.last_action", '>=', Values::normalizeTimestamp('-10 minutes'), ELGG_VALUE_TIMESTAMP);
			}
		],
		'order_by' => new OrderByClause('e.last_action', 'DESC'),
	],
]);
