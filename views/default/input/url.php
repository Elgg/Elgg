<?php
/**
 * Elgg URL input
 * Displays a URL input field
 *
 * @uses $vars['class'] Additional CSS class
 */

elgg_import_esm('input/url');

$vars['class'] = elgg_extract_class($vars, 'elgg-input-url');

$defaults = [
	'autocapitalize' => 'off',
	'type' => 'url',
];

$vars = array_merge($defaults, $vars);

echo elgg_format_element('input', $vars);
