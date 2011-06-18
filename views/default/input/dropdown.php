<?php
/**
 * Elgg dropdown input
 * Displays a dropdown (select) input field
 *
 * @warning Default values of FALSE or NULL will match '' (empty string) and not 0.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value']          The current value, if any
 * @uses $vars['options']        An array of strings representing the options for the dropdown field
 * @uses $vars['options_values'] An associative array of "value" => "option"
 *                               where "value" is an internal name and "option" is
 * 								 the value displayed on the button. Replaces
 *                               $vars['options'] when defined.
 */

$defaults = array(
	'class' => 'elgg-input-dropdown', 
	'disabled' => FALSE,
);

$options_values = $vars['options_values'];
unset($vars['options_values']);

$options = $vars['options'];
unset($vars['options']);

$value = $vars['value'];
unset($vars['value']);

$attrs = array_merge($defaults, $vars);

?>
<select <?php echo elgg_format_attributes($attrs); ?>>
<?php

if ($options_values) {
	foreach ($options_values as $opt_value => $option) {

		$option_attrs = elgg_format_attributes(array(
			'value' => $opt_value,
			'selected' => (string)$opt_value == (string)$value,
		));

		echo "<option $option_attrs>$option</option>";
	}
} else {
	if (is_array($options)) {
		foreach ($options as $option) {

			$option_attrs = elgg_format_attributes(array(
				'selected' => (string)$option == (string)$value
			));

			echo "<option $option_attrs>$option</option>";
		}
	}
}
?>
</select>
