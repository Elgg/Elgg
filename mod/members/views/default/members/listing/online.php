<?php

/**
 * Renders a list of online users
 * 
 * @uses $vars['options'] Options
 */

$defaults = [
	'types' => 'user',
	'full_view' => false,
	'seconds' => 600,
];

$options = (array) elgg_extract('options', $vars, []);

$options = array_merge($defaults, $options);

echo elgg_list_entities($options, 'find_active_users');