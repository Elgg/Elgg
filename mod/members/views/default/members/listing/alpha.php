<?php

/**
 * Renders a list of users ordered alphabetically
 * 
 * @uses $vars['options'] Options
 */
$defaults = [
	'types' => 'user',
	'full_view' => false,
];

$options = (array) elgg_extract('options', $vars, []);

$options = array_merge($defaults, $options);

$dbprefix = elgg_get_config('dbprefix');

$options['joins'][] = "JOIN {$dbprefix}users_entity ue ON e.guid = ue.guid";
$options['order_by'] = 'ue.name ASC';

echo elgg_list_entities($options);


