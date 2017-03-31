<?php

$label = elgg_extract('label', $vars);
unset($vars['label']);

$value = elgg_extract('value', $vars);
unset($vars['value']);

if (!$value) {
	return;
}

if ($label) {
	$label = elgg_format_element('div', [
		'class' => 'elgg-output-field-label',
	], $label);
}

$value = elgg_format_element('div', [
	'class' => 'elgg-output-field-value',
], $value);

$vars['class'] = elgg_extract_class($vars, ['elgg-output-field']);

echo elgg_format_element('div', $vars, $label . $value);