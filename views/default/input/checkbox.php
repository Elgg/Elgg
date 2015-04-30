<?php
/**
 * Elgg checkbox input
 * Displays a checkbox input tag
 * 
 * @package Elgg
 * @subpackage Core
 *
 *
 * Pass input tag attributes as key value pairs. For a list of allowable
 * attributes, see http://www.w3schools.com/tags/tag_input.asp
 * 
 * @uses $vars['name']        Name of the checkbox
 * @uses $vars['value']       Value of the checkbox
 * @uses $vars['default']     The default value to submit if not checked.
 *                            Optional, defaults to 0. Set to false for no default.
 * @uses $vars['checked']     Whether this checkbox is checked
 * @uses $vars['label']       Optional label string
 * @uses $vars['class']       Additional CSS class
 * @uses $vars['label_class'] Optional class for the label
 */

$vars['class'] = (array) elgg_extract('class', $vars, []);
$vars['class'][] = 'elgg-input-checkbox';

$defaults = array(
	'default' => 0,
	'disabled' => false,
	'type' => 'checkbox'
);

$vars = array_merge($defaults, $vars);

$default = $vars['default'];
unset($vars['default']);

if (isset($vars['name']) && $default !== false) {
	echo elgg_view('input/hidden', ['name' => $vars['name'], 'value' => $default]);
}

$label = elgg_extract('label', $vars, false);
$label_class = elgg_extract('label_class', $vars);
unset($vars['label']);
unset($vars['label_class']);

$input = elgg_format_element('input', $vars);

if (!empty($label)) {
	echo elgg_format_element('label', ['class' => $label_class], "$input $label");
} else {
	echo $input;
}
