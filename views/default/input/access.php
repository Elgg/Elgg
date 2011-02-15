<?php
/**
 * Elgg access level input
 * Displays a dropdown input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['options_values']
 * @uses $vars['name'] The name of the input field
 */

$defaults = array(
	'class' => 'elgg-input-access',
	'disabled' => FALSE,
	'value' => get_default_access(),
	'options_values' => get_write_access_array(),
);

$vars = array_merge($defaults, $vars);

if ($vars['value'] == ACCESS_DEFAULT) {
	$vars['value'] = get_default_access();
}

if (is_array($vars['options_values']) && sizeof($vars['options_values']) > 0) {
	echo elgg_view('input/dropdown', $vars);
}