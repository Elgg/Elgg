<?php
/**
 * Elgg URL input
 * Displays a URL input field
 *
 * @uses $vars['class'] Additional CSS class
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-input-url');

$defaults = [
	'value' => '',
	'disabled' => false,
	'autocapitalize' => 'off',
	'autocorrect' => 'off',
	'type' => 'url'
];

$vars = array_merge($defaults, $vars);

echo elgg_format_element('input', $vars);
