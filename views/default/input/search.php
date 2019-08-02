<?php
/**
 * Elgg search input
 * Displays a search input field
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-input-search');

$defaults = [
	'type' => 'search'
];

$vars = array_merge($defaults, $vars);

echo elgg_view('input/text', $vars);
