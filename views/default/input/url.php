<?php
/**
 * Elgg URL input
 * Displays a URL input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['class'] Additional CSS class
 */

$vars['class'] = (array) elgg_extract('class', $vars, []);
$vars['class'][] = 'elgg-input-url';

$defaults = array(
	'value' => '',
	'disabled' => false,
	'autocapitalize' => 'off',
	'autocorrect' => 'off',
	'type' => 'url'
);

$vars = array_merge($defaults, $vars);

echo elgg_format_element('input', $vars);
