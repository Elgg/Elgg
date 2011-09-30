<?php
/**
 * Elgg dropdown input
 * Displays a dropdown (select) input field
 *
 * @warning Default values of FALSE or NULL will match '' (empty string) but not 0.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value']          The current value, if any
 * @uses $vars['options']        An array of strings representing the options for the dropdown field
 * @uses $vars['options_values'] An associative array of "value" => "option"
 *                               where "value" is the name and "option" is
 * 								 the value displayed on the button. Replaces
 *                               $vars['options'] when defined.
 * @uses $vars['class']          Additional CSS class
 */

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-dropdown {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-dropdown";
}

$defaults = array(
	'disabled' => false,
	'value' => '',
	'options_values' => array(),
	'options' => array(),
);

$vars = array_merge($defaults, $vars);

$options_values = $vars['options_values'];
unset($vars['options_values']);

$options = $vars['options'];
unset($vars['options']);

$value = $vars['value'];
unset($vars['value']);

?>
<select <?php echo elgg_format_attributes($vars); ?>>
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
