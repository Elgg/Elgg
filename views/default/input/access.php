<?php
/**
 * Elgg access level input
 * Displays a dropdown input field
 *
 * @package Elgg
 * @subpackage Core


 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['js'] Any Javascript to enter into the input tag
 * @uses $vars['internalname'] The name of the input field
 *
 */

$defaults = array(
	'class' => 'elgg-input-access',
	'disabled' => FALSE,
	'value' => get_default_access(),
	'options' => get_write_access_array(),
);

$vars = array_merge($defaults, $vars);

if (is_array($vars['options']) && sizeof($vars['options']) > 0) {
	echo elgg_view('input/dropdown', $vars);
}