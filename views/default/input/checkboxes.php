<?php
/**
 * Elgg checkbox input
 * Displays a checkbox input field
 * NB: This also includes a hidden input with the same name as the checkboxes
 * to make sure something is sent to the server.  The default value is 0.
 * If using JS, be specific to avoid selecting the hidden default value:
 * 	$('input[type=checkbox][name=internalname])
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses string $vars['internalname'] The name of the input fields (Forced to an array by appending [])
 * @uses array $vars['options'] An array of strings representing the label => option for the each checkbox field
 * @uses string $vars['internalid'] The id for each input field. Optional (Only use this with a single value.)
 * @uses string $vars['default'] The default value to send if nothing is checked. Optional, defaults to 0.
 * @uses bool $vars['disabled'] Make all input elements disabled. Optional.
 * @uses string $vars['value'] The current value. Optional.
 * @uses string $vars['class'] The class of each input element. Optional.
 * @uses string $vars['js'] Any Javascript to enter into the input tag. Optional.
 *
 */

$class = (isset($vars['class'])) ? $vars['class'] : 'input-checkboxes';
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
	if ($internalname) {
		echo "<input type=\"hidden\" name=\"$internalname\" value=\"$default\">";
	}
	
	foreach($options as $label => $option) {
		// @hack - This sorta checks if options is not an assoc array and then
		// ignores the label (because it's just the index) and sets the value ($option)
		// as the label.
		// Wow.
		// @todo deprecate in Elgg 1.8
		if (is_integer($label)) {
			$label = $option;
		}

		if (!in_array(strtolower($option), $value_array)) {
			$selected = FALSE;
		} else {
			$selected = TRUE;
		}
		
		$attr = array(
			'type="checkbox"',
			'value="' . htmlentities($option, ENT_QUOTES, 'UTF-8') . '"'
		);
		
		if ($id) {
			$attr[] = "id=\"$id\"";
		}
		
		if ($class) {
			$attr[] = "class=\"$class\"";
		}
		
		if ($disabled) {
			$attr[] = 'disabled="yes"';
		}
		
		if ($selected) {
			$attr[] = 'checked = "checked"';
		}
		
		if ($js) {
			$attr[] = $js;
		}
		
		if ($internalname) {
			// @todo this really, really should only add the []s if there are > 1 element in options.
			$attr[] = "name=\"{$internalname}[]\"";
		}
		
		$attr_str = implode(' ', $attr);
		
		echo "<label><input $attr_str />$label</label><br />";
	}
}