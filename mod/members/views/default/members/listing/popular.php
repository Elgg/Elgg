<?php

/**
 * Renders a list of users ordered by number of friends
 * 
 * @uses $vars['options'] Options
 */

$defaults = [
	'types' => 'user',
	'full_view' => false,
];

$options = (array) elgg_extract('options', $vars, []);

$options = array_merge($defaults, $options);

$options['relationship'] = 'friend';
$options['inverse_relationship'] = false;

echo elgg_list_entities_from_relationship_count($options);



