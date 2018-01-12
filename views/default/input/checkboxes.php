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
 * @uses string $vars['name']           The name of the input fields
 *                                      (Forced to an array by appending [])
 * @uses array  $vars['options']        An array of strings representing the
 *                                      label => option for the each checkbox field
 * @uses array  $vars['options_values'] An associative array of 'value' => ['text' => 'option']
 * @uses string $vars['default']        The default value to send if nothing is checked.
 *                                      Optional, defaults to 0. Set to FALSE for no default.
 * @uses bool   $vars['disabled']       Make all input elements disabled. Optional.
 * @uses string $vars['value']          The current value. Single value or array. Optional.
 * @uses string $vars['class']          Additional class of the list. Optional.
 * @uses string $vars['align']          'horizontal' or 'vertical' Default: 'vertical'
 *
 */

$defaults = [
	'align' => 'vertical',
	'value' => [],
	'default' => 0,
	'disabled' => false,
	'options' => [],
	'name' => '',
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

$list_class = elgg_extract_class($vars, [
	'elgg-input-checkboxes',
	"elgg-{$vars['align']}",
]);
unset($vars['class']);

$id = elgg_extract('id', $vars, '');
unset($vars['id']);

if (is_array(elgg_extract('value', $vars))) {
	$values = array_map('elgg_strtolower', elgg_extract('value', $vars));
} else {
	$values = [elgg_strtolower(elgg_extract('value', $vars))];
}

// include a default value so if nothing is checked 0 will be passed.
if ($vars['name'] && $vars['default'] !== false) {
	echo elgg_view('input/hidden', [
		'name' => elgg_extract('name', $vars),
		'value' => elgg_extract('default', $vars),
		'disabled' => elgg_extract('disabled', $vars),
	]);
}

// prepare checkbox vars
$input_vars = $vars;
$input_vars['default'] = false;
if (elgg_extract('name', $vars)) {
	$input_vars['name'] = "{$vars['name']}[]";
}
unset($input_vars['align']);

$checkboxes = '';
foreach ($input_options as $label => $option) {
	$checkbox_input_options = array_merge($input_vars, $option);
	$checkbox_input_options['checked'] = in_array(elgg_strtolower(elgg_extract('value', $option)), $values);
	$checkbox_input_options['label'] = elgg_extract('text', $checkbox_input_options, $label);
	unset($checkbox_input_options['text']);

	$input = elgg_view('input/checkbox', $checkbox_input_options);

	$checkboxes .= "<li>$input</li>";
}

echo elgg_format_element('ul', ['class' => $list_class, 'id' => $id], $checkboxes);
