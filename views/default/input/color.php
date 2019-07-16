<?php
/**
 * Elgg color input
 * Displays a color input field
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-input-color');

$defaults = [
	'type' => 'color'
];

$vars = array_merge($defaults, $vars);

echo elgg_view('input/text', $vars);
