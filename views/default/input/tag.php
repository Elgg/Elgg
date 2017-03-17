<?php
/**
 * Elgg tag input
 *
 * Accepts a single tag value
 *
 * @uses $vars['value'] The default value for the tag
 * @uses $vars['class'] Additional CSS class
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-input-tag');

$defaults = [
	'value' => '',
	'disabled' => false,
	'autocapitalize' => 'off',
	'type' => 'text'
];

$vars = array_merge($defaults, $vars);

echo elgg_format_element('input', $vars);
