<?php
/**
 * Elgg radio input
 * Displays a radio input field
 *
 * @warning Passing integers as labels does not currently work due to a
 * deprecated hack that will be removed in Elgg 1.9. To use integer labels,
 * the labels must be character codes: 1 would be &#0049;
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value']        The current value, if any
 * @uses $vars['name'] The name of the input field
 * @uses $vars['options']      An array of strings representing the options for the
 *                             radio field as "label" => option
 * @uses $vars['class']        Additional class of the list. Optional.
 * @uses $vars['align']       'horizontal' or 'vertical' Default: 'vertical'
 */

$additional_class = elgg_extract('class', $vars);
$align = elgg_extract('align', $vars, 'vertical');
$class = "elgg-input-radio elgg-$align";
if ($additional_class) {
	$class = " $additional_class";
	unset($vars['class']);
}

if (isset($vars['align'])) {
	unset($vars['align']);
}

$options = $vars['options'];
unset($vars['options']);

$value = $vars['value'];
unset($vars['value']);

if ($options && count($options) > 0) {
	echo "<ul class=\"$class\">";
	foreach ($options as $label => $option) {

		$vars['checked'] = elgg_strtolower($option) == elgg_strtolower($value);
		$vars['value'] = $option;

		$attributes = elgg_format_attributes($vars);

		// handle indexed array where label is not specified
		// @deprecated 1.8 Remove in 1.9
		if (is_integer($label)) {
			elgg_deprecated_notice('$vars[\'options\'] must be an associative array in input/radio', 1.8);
			$label = $option;
		}

		echo "<li><label><input type=\"radio\" $attributes />$label</label></li>";
	}
	echo '</ul>';
}
