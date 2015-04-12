<?php
/**
 * Elgg password input
 * Displays a password input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['name']  The name of the input field
 * @uses $vars['class'] Additional CSS class
 */

$vars['class'] = (array) elgg_extract('class', $vars, []);
$vars['class'][] = 'elgg-input-password';

$defaults = array(
	'disabled' => false,
	'value' => '',
	'autocapitalize' => 'off',
	'autocorrect' => 'off',
	'type' => 'password'
);

$vars = array_merge($defaults, $vars);

echo elgg_format_element('input', $vars);
