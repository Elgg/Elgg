<?php
/**
 * Elgg checkbox input
 * Displays a checkbox input field
 *
 * @note This also includes a hidden input with the same name as the checkboxes
 * to make sure something is sent to the server.  The default value is 0.
 * If using JS, be specific to avoid selecting the hidden default value:
 * 	$('input[type=checkbox][name=name]')
 * 
 * @warning Passing integers as labels does not currently work due to a
 * deprecated hack that will be removed in Elgg 1.9. To use integer labels,
 * the labels must be character codes: 1 would be &#0049;
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses string $vars['name'] The name of the input fields
 *                                    (Forced to an array by appending [])
 * @uses array  $vars['options']      An array of strings representing the
 *                                    label => option for the each checkbox field
 * @uses string $vars['id']   The id for each input field. Optional.
 *                                    (Only use this with a single value.)
 * @uses string $vars['default']      The default value to send if nothing is checked.
 *                                    Optional, defaults to 0. Set to FALSE for no default.
 * @uses bool   $vars['disabled']     Make all input elements disabled. Optional.
 * @uses string $vars['value']        The current value. Optional.
 * @uses string $vars['class']        Additional class of the list. Optional.
 * @uses string $vars['align']       'horizontal' or 'vertical' Default: 'vertical'
 *
 */

$additional_class = elgg_extract('class', $vars);
$align = elgg_extract('align', $vars, 'vertical');
$value = (isset($vars['value'])) ? $vars['value'] : NULL;
$value_array = (is_array($value)) ? array_map('elgg_strtolower', $value) : array(elgg_strtolower($value));
$name = (isset($vars['name'])) ? $vars['name'] : '';
$options = (isset($vars['options']) && is_array($vars['options'])) ? $vars['options'] : array();
$default = (isset($vars['default'])) ? $vars['default'] : 0;

$id = (isset($vars['id'])) ? $vars['id'] : '';
$disabled = (isset($vars['disabled'])) ? $vars['disabled'] : FALSE;
$js = (isset($vars['js'])) ? $vars['js'] : '';

$class = "elgg-input-checkboxes elgg-$align";
if ($additional_class) {
	$class = " $additional_class";
}

if ($options && count($options) > 0) {
	// include a default value so if nothing is checked 0 will be passed.
	if ($name && $default !== FALSE) {
		echo "<input type=\"hidden\" name=\"$name\" value=\"$default\" />";
	}

	echo "<ul class=\"$class\">";
	foreach ($options as $label => $option) {
		// @deprecated 1.8 Remove in 1.9
		if (is_integer($label)) {
			elgg_deprecated_notice('$vars[\'options\'] must be an associative array in input/checkboxes', 1.8);
			$label = $option;
		}

		$input_vars = array(
			'checked' => in_array(elgg_strtolower($option), $value_array),
			'value' => $option,
			'disabled' => $disabled,
			'id' => $id,
			'js' => $js,
			'default' => false,
		);

		if ($name) {
			$input_vars['name'] = "{$name}[]";
		}
		
		$input = elgg_view('input/checkbox', $input_vars);

		echo "<li><label>{$input}{$label}</label></li>";
	}
	echo '</ul>';
}