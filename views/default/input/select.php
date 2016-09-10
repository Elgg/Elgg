<?php
/**
 * Elgg select input
 * Displays a select input field
 *
 * @warning Values of FALSE or NULL will match '' (empty string) but not 0.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value']          The current value or an array of current values if multiple is true
 * @uses $vars['options']        An array of strings or arrays representing the options
 *                               for the dropdown field. If an array is passed,
 *                               the "text" key is used as its text, all other
 *                               elements in the array are used as attributes.
 * @uses $vars['options_values'] An associative array of "value" => "option"
 *                               where "value" is the name and "option" is
 *                               the value displayed on the button. Replaces
 *                               $vars['options'] when defined. When "option"
 *                               is passed as an array, the same behaviour is used
 *                               as when the $vars['options'] is passed an array to.
 * @uses $vars['multiple']       If true, multiselect of values will be allowed in the select box
 * @uses $vars['class']          Additional CSS class
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-input-dropdown');

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

$value = is_array($vars['value']) ? $vars['value'] : array($vars['value']);
$value = array_map('strval', $value);
unset($vars['value']);

$vars['multiple'] = !empty($vars['multiple']);

// Add trailing [] to name if multiple is enabled to allow the form to send multiple values
if ($vars['multiple'] && !empty($vars['name']) && is_string($vars['name'])) {
    if (substr($vars['name'], -2) != '[]') {
        $vars['name'] = $vars['name'] . '[]';
    }
}

$options_list = '';

if ($options_values) {
	foreach ($options_values as $opt_value => $option) {

		$option_attrs = array(
			'value' => $opt_value,
			'selected' => in_array((string)$opt_value, $value),
		);

		if (is_array($option)) {
			$text = elgg_extract('text', $option, '');
			unset($option['text']);
			if (!$text) {
				elgg_log('No text defined for input/select option with value "' . $opt_value . '"', 'ERROR');
			}

			$option_attrs = array_merge($option_attrs, $option);
		} else {
			$text = $option;
		}

		$options_list .= elgg_format_element('option', $option_attrs, $text);
	}
} else {
	if (is_array($options)) {
		foreach ($options as $option) {

			if (is_array($option)) {
				$text = elgg_extract('text', $option, '');
				unset($option['text']);

				if (!$text) {
					elgg_log('No text defined for input/select option', 'ERROR');
				}

				$option_attrs = [
					'selected' => in_array((string)$text, $value),
				];
				$option_attrs = array_merge($option_attrs, $option);
			} else {
				$option_attrs = [
					'selected' => in_array((string)$option, $value),
				];

				$text = $option;
			}

			$options_list .= elgg_format_element('option', $option_attrs, $text);
		}
	}
}

echo elgg_format_element('select', $vars, $options_list);
