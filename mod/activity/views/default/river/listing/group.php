<?php
/**
 * List group river activity
 *
 * @uses $vars['options'] Additional listing options
 * @uses $vars['entity']  The group to show activity for
 */

use Elgg\Activity\GroupRiverFilter;

$options = (array) elgg_extract('options', $vars);
$entity = elgg_extract('entity', $vars);

$group_options = [
	'wheres' => [
		new GroupRiverFilter($entity),
	]
];

$vars['options'] = array_merge($options, $group_options);

echo elgg_view('river/listing/all', $vars);
