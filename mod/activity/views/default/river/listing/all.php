<?php
/**
 * List river activity
 *
 * @uses $vars['options']     Additional listing options
 * @uses $vars['show_filter'] Add river filter selector (default: false)
 */

use Elgg\Database\QueryBuilder;

// filter
$show_filter = (bool) elgg_extract('show_filter', $vars, false);
unset($vars['show_filter']);
if ($show_filter) {
	echo elgg_view('river/filter', $vars);
}

// determines if individual comments show in the listing
$show_comments = (bool) elgg_extract('show_comments', $vars, true);
unset($vars['show_comments']);

// activity
$defaults = [
	'distinct' => false,
	'no_results' => elgg_echo('river:none'),
	'pagination_previous_text' => elgg_echo('newer'),
	'pagination_next_text' => elgg_echo('older'),
	'pagination_show_numbers' => false,
	'wheres' => [],
];

$options = (array) elgg_extract('options', $vars, []);
$options = array_merge($defaults, $options);

$entity_type = elgg_extract('entity_type', $vars, 'all');
if ($entity_type !== 'all') {
	$options['type'] = $entity_type;
	
	$entity_subtype = elgg_extract('entity_subtype', $vars);
	if (!empty($entity_subtype)) {
		$options['subtype'] = $entity_subtype;
	}
} elseif (!$show_comments) {
	$options['wheres'][] = function (QueryBuilder $qb, $main_alias) {
		$alias = $qb->joinEntitiesTable($main_alias, 'object_guid', 'inner', 'oe');
		$types = [
			$qb->compare("{$alias}.type", '=', 'object', ELGG_VALUE_STRING),
			$qb->compare("{$alias}.subtype", '=', 'comment', ELGG_VALUE_STRING),
		];
		
		return 'NOT (' . $qb->merge($types, 'AND') . ')';
	};
}

echo elgg_list_river($options);
