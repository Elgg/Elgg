<?php
/**
 * Elgg text input
 * Displays a text input field
 *
 * @uses $vars['class'] Additional CSS class
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-input-text');

$defaults = [
	'value' => '',
	'disabled' => false,
	'type' => 'text'
];

$vars = array_merge($defaults, $vars);

echo elgg_format_element('input', $vars);
