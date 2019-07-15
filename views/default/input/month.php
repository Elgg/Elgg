<?php
/**
 * Elgg month input
 * Displays a month input field
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-input-month');

$defaults = [
	'type' => 'month'
];

$vars = array_merge($defaults, $vars);

echo elgg_view('input/text', $vars);
