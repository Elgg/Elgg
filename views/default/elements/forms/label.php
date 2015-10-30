<?php

/**
 * Form input label view
 *
 * @uses $vars['label'] HTML content of the label element
 * @uses $vars['id'] ID attribute of the input element
 */
$label = elgg_extract('label', $vars, '');
$id = elgg_extract('id', $vars);
$required = elgg_extract('required', $vars);

if (!$label) {
	return;
}

if ($required) {
	$label .= elgg_format_element('span', [
		'title' => elgg_echo('field:required'),
		'class' => 'elgg-required-indicator',
	], "&ast;");
}

echo elgg_format_element('label', [
	'for' => $id,
	'class' => 'elgg-field-label'
		], $label);
