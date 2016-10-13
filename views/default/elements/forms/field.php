<?php

/**
 * Form field view
 *
 * @uses $vars['input'] Form input element
 * @uses $vars['id'] ID attribute of the input element
 * @uses $vars['required'] Required or optional input
 * @uses $vars['label'] HTML content of the label element
 * @uses $vars['help'] HTML content of the help element
 */
$input = elgg_extract('input', $vars);
if (!$input) {
	return;
}

$label = elgg_extract('label', $vars, '');
$help = elgg_extract('help', $vars, '');

$class = elgg_extract_class($vars, 'elgg-field');
if (elgg_extract('required', $vars)) {
	$class[] = "elgg-field-required";
}

$field = $label . $input . $help;

echo elgg_format_element('div', [
	'class' => $class,
		], $field);

