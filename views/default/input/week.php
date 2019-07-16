<?php
/**
 * Elgg week input
 * Displays a week input field
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-input-week');

$defaults = [
	'type' => 'week'
];

$vars = array_merge($defaults, $vars);

echo elgg_view('input/text', $vars);
