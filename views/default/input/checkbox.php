<?php
/**
 * Elgg checkbox input
 * Displays a checkbox input tag
 *
 * Pass input tag attributes as key value pairs. For a list of allowable
 * attributes, see http://www.w3schools.com/tags/tag_input.asp
 *
 * @uses $vars['name']        Name of the checkbox
 * @uses $vars['value']       Value of the checkbox
 * @uses $vars['default']     The default value to submit if not checked.
 *                            Optional, defaults to 0. Set to false for no default.
 * @uses $vars['checked']     Whether this checkbox is checked
 * @uses $vars['switch']      Whether this checkbox is styled as a toggle switch
 * @uses $vars['label']       Optional label string
 * @uses $vars['class']       Additional CSS class
 * @uses $vars['label_class'] Optional class for the label
 * @uses $vars['label_tag']   HTML tag that wraps concatinated label and input. Defaults to 'label'.
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-input-checkbox');

$defaults = [
	'default' => 0,
	'disabled' => false,
	'type' => 'checkbox'
];

$vars = array_merge($defaults, $vars);

$default = elgg_extract('default', $vars);
unset($vars['default']);

if (isset($vars['name']) && $default !== false) {
	echo elgg_view('input/hidden', [
		'name' => elgg_extract('name', $vars),
		'value' => $default,
		'disabled' => elgg_extract('disabled', $vars),
	]);
}

$label = elgg_extract('label', $vars, false);
$label_class = (array) elgg_extract('label_class', $vars, []);
$label_class[] = 'elgg-input-single-checkbox';
unset($vars['label']);
unset($vars['label_class']);

$switch = elgg_extract('switch', $vars, false);
unset($vars['switch']);

if ($switch) {
	$vars['class'][] = 'hidden';
}

$input = elgg_format_element('input', $vars);
if ($switch) {
	$input .= elgg_format_element('span', ['class' => 'elgg-input-checkbox-switch']);
	
	if (empty($label)) {
		$label = '&nbsp;';
	}
}

if (!empty($label)) {
	$html_tag = elgg_extract('label_tag', $vars, 'label', false);
	if ($switch && ($html_tag !== 'label')) {
		$input = elgg_format_element('label', [], $input);
	}
	echo elgg_format_element($html_tag, ['class' => $label_class], "$input $label");
} else {
	echo $input;
}
