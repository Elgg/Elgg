<?php
/**
 * Elgg file input
 * Displays a file input field
 *
 * @uses $vars['value'] The current value if any
 * @uses $vars['class'] Additional CSS class
 */

if (!empty($vars['value'])) {
	echo elgg_format_element('div', [
		'class' => 'elgg-state elgg-state-warning',
	], elgg_echo('fileexists'));
}

$vars['class'] = elgg_extract_class($vars, 'elgg-input-file');

$defaults = [
	'disabled' => false,
	'type' => 'file'
];

$vars = array_merge($defaults, $vars);

echo elgg_format_element('input', $vars);
