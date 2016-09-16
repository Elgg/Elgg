<?php

/**
 * Renders a list of users ordered by registration time
 * 
 * @uses $vars['options'] Options
 */

$defaults = [
	'types' => 'user',
	'full_view' => false,
];

$options = (array) elgg_extract('options', $vars, []);

$options = array_merge($defaults, $options);

echo elgg_list_entities($options);



