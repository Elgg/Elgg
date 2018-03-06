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
 *                               If the array contains an array of 'options' an optgroup will be drawn with 'label' as the
 *                               optgroup label.
 * @uses $vars['multiple']       If true, multiselect of values will be allowed in the select box
 * @uses $vars['class']          Additional CSS class
 */

$vars['class'] = elgg_extract_class($vars, [
	'elgg-input-dropdown', // legacy class
	'elgg-input-select',
]);

$defaults = [
	'disabled' => false,
	'value' => '',
];

$vars = array_merge($defaults, $vars);

$options_values = [];

// turn options into options_values
$options = elgg_extract('options', $vars, []);
unset($vars['options']);

foreach ($options as $option) {
	if (is_array($option)) {
		if (!isset($option['value'])) {
			$option['value'] = elgg_extract('text', $option);
		}
	} else {
		$option = [
			'text' => $option,
			'value' => $option,
		];
	}

	$options_values[] = $option;
}

// provided options_values trump options
$options_values = elgg_extract('options_values', $vars, $options_values);
unset($vars['options_values']);

$normalize_options = function($options) use (&$normalize_options) {
	foreach ($options as $key => $option) {
		if (is_string($option)) {
			$option = [
				'text' => $option,
				'value' => $key,
			];
		}

		$optgroup = elgg_extract('options', $option);
		if (is_array($optgroup)) {
			$option['options'] = $normalize_options($optgroup);
		}

		$options[$key] = $option;
	}

	return $options;
};

$options_values = $normalize_options($options_values);

$value = is_array($vars['value']) ? $vars['value'] : [$vars['value']];
$value = array_map('strval', $value);
unset($vars['value']);

$vars['multiple'] = !empty($vars['multiple']);

// Add trailing [] to name if multiple is enabled to allow the form to send multiple values
if ($vars['multiple'] && !empty($vars['name']) && is_string($vars['name'])) {
	if (substr($vars['name'], -2) != '[]') {
		$vars['name'] = elgg_extract('name', $vars) . '[]';
	}
}

$render_option = function($option) use ($value) {
	$opt_value = elgg_extract('value', $option);

	$text = elgg_extract('text', $option);
	unset($option['text']);

	if (!is_string($text) && !is_numeric($text)) {
		elgg_log('No text defined for input/select option with value "' . $opt_value . '"', 'NOTICE');
	}

	if (!isset($option['selected'])) {
		$option['selected'] = isset($opt_value) && in_array((string) $opt_value, $value);
	}
	
	if (!isset($option['title'])) {
		$option['title'] = $text;
	}

	return elgg_format_element('option', $option, $text);
};

$options_list = '';

foreach ($options_values as $option) {
	$options = elgg_extract('options', $option);

	if (is_array($options)) {
		$optgroup_attrs = $option;
		unset($optgroup_attrs['options']);
		
		$optgroup = '';
		foreach ($options as $group_option) {
			$optgroup .= $render_option($group_option);
		}

		$options_list .= elgg_format_element('optgroup', $optgroup_attrs, $optgroup);
	} else {
		$options_list .= $render_option($option);
	}
}

echo elgg_format_element('select', $vars, $options_list);
