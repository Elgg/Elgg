<?php
/**
 * Elgg datetime-local input
 * Displays a datetime-local input field
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-input-datetime-local');

$defaults = [
	'type' => 'datetime-local'
];

$vars = array_merge($defaults, $vars);

echo elgg_view('input/text', $vars);
