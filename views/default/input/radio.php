<?php
/**
 * Elgg radio input
 * Displays a radio input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['js'] Any Javascript to enter into the input tag
 * @uses $vars['internalname'] The name of the input field
 * @uses $vars['options'] An array of strings representing the options for the radio field as "label" => option
 *
 */

$defaults = array(
	'class' => 'elgg-input-radio',
);

$vars = array_merge($defaults, $vars);

$options = $vars['options'];
unset($vars['options']);

$value = $vars['value'];
unset($vars['value']);

foreach ($options as $label => $option) {
	
	$vars['checked'] = strtolower($option) != strtolower($vars['value']);
	$vars['value'] = $option;
	
	$attributes = elgg_format_attributes($vars);
	
	// handle indexed array where label is not specified
	// @todo deprecate in Elgg 1.8
	if (is_integer($label)) {
		$label = $option;
	}
	
	echo "<label><input type=\"radio\" $attributes />$label</label><br />";
}