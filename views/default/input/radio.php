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
 * @uses $vars['value']          The current value, if any
 * @uses $vars['name']           The name of the input field
 * @uses $vars['options']        An array of strings representing the options for the
 * @uses $vars['options_values'] An associative array of 'value' => ['text' => 'option']
 * @uses $vars['class']          Additional class of the list. Optional.
 * @uses $vars['align']          'horizontal' or 'vertical' Default: 'vertical'
 */

$defaults = [
	'align' => 'vertical',
	'value' => [],
	'disabled' => false,
	'options' => [],
	'name' => '',
	'type' => 'radio',
];

$vars = array_merge($defaults, $vars);

$options_values = elgg_extract('options_values', $vars, []);
unset($vars['options_values']);

$input_options = [];

foreach ($options_values as $key => $value) {
	if (is_array($value)) {
		$key = elgg_extract('text', $value, $key);
	} else {
		$value = [
			'text' => $value,
			'value' => $key,
		];
	}
	$input_options[$key] = $value;
}

// turn options into options_values
$options = elgg_extract('options', $vars);
unset($vars['options']);
if (!empty($options) && empty($input_options)) {
	foreach ($options as $label => $value) {
		$input_options[$label] = ['value' => $value];
	}
}

if (empty($input_options)) {
	return;
}

$id = elgg_extract('id', $vars, '');
unset($vars['id']);

$list_class = elgg_extract_class($vars, ['elgg-input-radios', "elgg-{$vars['align']}"]);

unset($vars['class']);
unset($vars['align']);

$vars['class'] = 'elgg-input-radio';

if (is_array($vars['value'])) {
	$selected_value = array_map('elgg_strtolower', $vars['value']);
} else {
	$selected_value = [elgg_strtolower($vars['value'])];
}
unset($vars['value']);

$radios = '';
foreach ($input_options as $label => $option) {
	$radio_input_options = array_merge($vars, $option);
	$radio_input_options['checked'] = in_array(elgg_strtolower(elgg_extract('value', $option)), $selected_value);

	$label = elgg_extract('text', $radio_input_options, $label);
	unset($radio_input_options['text']);
	
	$radio = elgg_format_element('input', $radio_input_options);
	$radios .= "<li><label>{$radio}{$label}</label></li>";
}

echo elgg_format_element('ul', [
	'class' => $list_class,
	'id' => $id,
], $radios);
