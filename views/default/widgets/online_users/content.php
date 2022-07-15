<?php
/**
 * Online users widget
 */

use Elgg\Database\QueryBuilder;
use Elgg\Values;

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof ElggWidget) {
	return;
}

$num_display = (int) $widget->num_display ?: 8;

echo elgg_list_entities([
	'type' => 'user',
	'pagination' => false,
	'limit' => $num_display,
	'no_results' => true,
	'widget_more' => elgg_view_url('admin/users/online', elgg_echo('more')),
	'wheres' => [
		function(QueryBuilder $qb, $main_alias) {
			return $qb->compare("{$main_alias}.last_action", '>=', Values::normalizeTimestamp('-10 minutes'), ELGG_VALUE_TIMESTAMP);
		}
	],
	'sort_by' => [
		'property' => 'last_action',
		'direction' => 'desc',
	],
]);
