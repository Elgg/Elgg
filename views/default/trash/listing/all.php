<?php
/**
 * List entities that have been deleted
 *
 * @uses $vars['options'] additional options
 */

use Elgg\Database\QueryBuilder;

$defaults = [
	'type_subtype_pairs' => elgg_entity_types_with_capability('restorable'),
	'no_results' => elgg_echo('trash:no_results'),
	'list_class' => 'elgg-listing-trash',
	'item_view' => 'trash/entity',
	'pagination_behaviour' => 'ajax-replace',
	'limit' => max(20, (int) elgg_get_config('default_limit'), (int) get_input('limit')),
	'sort_by' => [
		'property' => 'time_deleted',
		'direction' => 'desc',
	],
	'wheres' => [],
];

$options = (array) elgg_extract('options', $vars, []);
$options = array_merge($defaults, $options);

// ensure only deleted items are shown
$options['wheres'][] = function(QueryBuilder $qb, $main_alias) {
	return $qb->compare("{$main_alias}.deleted", '=', 'yes', ELGG_VALUE_STRING);
};

$vars['options'] = $options;
echo elgg_view('trash/elements/notice', $vars);

echo elgg_call(ELGG_SHOW_DELETED_ENTITIES, function() use ($options) {
	return elgg_list_entities($options);
});
