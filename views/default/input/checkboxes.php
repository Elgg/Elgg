<?php
/**
 * Elgg checkbox input
 * Displays a checkbox input field
 * NB: This also includes a hidden input with the same name as the checkboxes
 * to make sure something is sent to the server.  The default value is 0.
 * If using JS, be specific to avoid selecting the hidden default value:
 * 	$('input[type=checkbox][name=internalname]')
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses string $vars['internalname'] The name of the input fields
 *                                    (Forced to an array by appending [])
 * @uses array  $vars['options']      An array of strings representing the
 *                                    label => option for the each checkbox field
 * @uses string $vars['internalid']   The id for each input field. Optional
 *                                    (Only use this with a single value.)
 * @uses string $vars['default']      The default value to send if nothing is checked.
 *                                    Optional, defaults to 0. Set to FALSE for no default.
 * @uses bool   $vars['disabled']     Make all input elements disabled. Optional.
 * @uses string $vars['value']        The current value. Optional.
 * @uses string $vars['class']        The class of each input element. Optional.
 * @uses string $vars['js']           Any Javascript to enter into the input tag. Optional.
 *
 */

if (!isset($vars['value']) || $vars['value'] === FALSE) { 
	$vars['value'] = elgg_get_sticky_value($vars['internalname']); 
}

$class = (isset($vars['class'])) ? $vars['class'] : 'input_checkboxes';
$value = (isset($vars['value'])) ? $vars['value'] : NULL;
$value_array = (is_array($value)) ? array_map('strtolower', $value) : array(strtolower($value));
$internalname = (isset($vars['internalname'])) ? $vars['internalname'] : '';
$options = (isset($vars['options']) && is_array($vars['options'])) ? $vars['options'] : array();
$default = (isset($vars['default'])) ? $vars['default'] : 0;

$id = (isset($vars['internalid'])) ? $vars['internalid'] : '';
$disabled = (isset($vars['disabled'])) ? $vars['disabled'] : FALSE;
$js = (isset($vars['js'])) ? $vars['js'] : '';

if ($options) {
	// include a default value so if nothing is checked 0 will be passed.
	if ($internalname && $default !== FALSE) {
		echo "<input type=\"hidden\" name=\"$internalname\" value=\"$default\">";
	}

	foreach ($options as $label => $option) {
		// @hack - This sorta checks if options is not an assoc array and then
		// ignores the label (because it's just the index) and sets the value ($option)
		// as the label.
		// Wow.
		// @todo deprecate in Elgg 1.8
		if (is_integer($label)) {
			$label = $option;
		}

		$input_vars = array(
			'checked' => in_array(strtolower($option), $value_array),
			'value' => $option,
			'disabled' => $disabled,
			'id' => $id,
			'js' => $js,
			'default' => false,
		);
		
		if ($class) {
			$input_vars['class'] = $class;
		}

		if ($internalname) {
			$input_vars['name'] = "{$internalname}[]";
		}
		
		$input = elgg_view('input/checkbox', $input_vars);

		echo "<label>{$input}{$label}</label><br />";
	}
}