<?php
/**
 * Elgg file input
 * Displays a file input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The current value if any
 * @uses $vars['class'] Additional CSS class
 */

if (!empty($vars['value'])) {
	echo elgg_echo('fileexists') . "<br />";
}

$vars['class'] = elgg_extract_class($vars, 'elgg-input-file');

$defaults = [
	'disabled' => false,
	'type' => 'file'
];

$vars = array_merge($defaults, $vars);

echo elgg_format_element('input', $vars);
