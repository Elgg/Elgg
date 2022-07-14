<?php
/**
 * Returns content for the "online" page
 */

use Elgg\Database\QueryBuilder;
use Elgg\Values;

echo elgg_view_page(elgg_echo('members:title:online'), [
	'content' => elgg_list_entities([
		'type' => 'user',
		'wheres' => [
			function(QueryBuilder $qb, $main_alias) {
				return $qb->compare("{$main_alias}.last_action", '>=', Values::normalizeTimestamp('-10 minutes'), ELGG_VALUE_TIMESTAMP);
			}
		],
		'sort_by' => [
			'property' => 'last_action',
			'direction' => 'desc',
		],
	]),
	'sidebar' => elgg_view('members/sidebar'),
	'filter_id' => 'members',
	'filter_value' => 'online',
	'filter_sorting' => false,
]);
