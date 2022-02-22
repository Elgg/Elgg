<?php
/**
 * Elgg URL input
 * Displays a URL input field
 *
 * @uses $vars['class'] Additional CSS class
 */

elgg_require_js('input/url');

$vars['class'] = elgg_extract_class($vars, 'elgg-input-url');

$defaults = [
	'value' => '',
	'disabled' => false,
	'autocapitalize' => 'off',
	'type' => 'url'
];

$vars = array_merge($defaults, $vars);

echo elgg_format_element('input', $vars);
