<?php
/**
 * Elgg tel input
 * Displays a tel input field
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-input-tel');

$defaults = [
	'type' => 'tel',
];

$vars = array_merge($defaults, $vars);

echo elgg_view('input/text', $vars);
