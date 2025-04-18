<?php
/**
 * Form input label view
 *
 * @uses $vars['label'] HTML content of the label element
 * @uses $vars['required_indicator'] Override required indicator with a custom view, or set to a false value to not render it
 * @uses $vars['id'] ID attribute of the input element
 */

$label = elgg_extract('label', $vars, '');
$id = elgg_extract('id', $vars);
$required = elgg_extract('required', $vars);

if (elgg_is_empty($label)) {
	return;
}

if ($required) {
	$indicator = elgg_extract('required_indicator', $vars);
	if (!isset($indicator)) {
		$indicator = elgg_format_element('span', [
			'title' => elgg_echo('field:required'),
			'class' => 'elgg-required-indicator',
		], '&ast;');
	}
	
	if ($indicator) {
		$label .= $indicator;
	}
}

echo elgg_format_element('label', [
	'id' => "{$id}-field-label",
	'for' => $id,
	'class' => 'elgg-field-label',
], $label);
