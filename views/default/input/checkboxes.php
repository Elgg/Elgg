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
 * @uses string $vars['name']     The name of the input fields
 *                                (Forced to an array by appending [])
 * @uses array  $vars['options']  An array of strings representing the
 *                                label => option for the each checkbox field
 * @uses string $vars['default']  The default value to send if nothing is checked.
 *                                Optional, defaults to 0. Set to FALSE for no default.
 * @uses bool   $vars['disabled'] Make all input elements disabled. Optional.
 * @uses string $vars['value']    The current value. Single value or array. Optional.
 * @uses string $vars['class']    Additional class of the list. Optional.
 * @uses string $vars['align']    'horizontal' or 'vertical' Default: 'vertical'
 *
 */

$defaults = array(
	'align' => 'vertical',
	'value' => array(),
	'default' => 0,
	'disabled' => false,
	'options' => array(),
	'name' => '',
);

$vars = array_merge($defaults, $vars);

$class = "elgg-input-checkboxes elgg-{$vars['align']}";
if (isset($vars['class'])) {
	$class .= " {$vars['class']}";
	unset($vars['class']);
}

$id = '';
if (isset($vars['id'])) {
	$id = "id=\"{$vars['id']}\"";
	unset($vars['id']);
}

if (is_array($vars['value'])) {
	$values = array_map('elgg_strtolower', $vars['value']);
} else {
	$values = array(elgg_strtolower($vars['value']));
}

$input_vars = $vars;
$input_vars['default'] = false;
if ($vars['name']) {
	$input_vars['name'] = "{$vars['name']}[]";
}
unset($input_vars['align']);
unset($input_vars['options']);

if (count($vars['options']) > 0) {
	// include a default value so if nothing is checked 0 will be passed.
	if ($vars['name'] && $vars['default'] !== false) {
		echo "<input type=\"hidden\" name=\"{$vars['name']}\" value=\"{$vars['default']}\" />";
	}

	echo "<ul class=\"$class\" $id>";
	foreach ($vars['options'] as $label => $value) {
		// @deprecated 1.8 Remove in 1.9
		if (is_integer($label)) {
			elgg_deprecated_notice('$vars[\'options\'] must be an associative array in input/checkboxes', 1.8);
			$label = $value;
		}

		$input_vars['checked'] = in_array(elgg_strtolower($value), $values);
		$input_vars['value']   = $value;
		
		$input = elgg_view('input/checkbox', $input_vars);

		echo "<li><label>$input$label</label></li>";
	}
	echo '</ul>';
}
