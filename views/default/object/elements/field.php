<?php
/**
 * Outputs a field with a label
 *
 * @uses $vars['label'] Label
 * @uses $vars['value'] Value
 * @uses $vars['class'] Additional classes
 * @uses $vars['icon'] Icon
 * @uses $vars['name'] Field name
 */

$value = elgg_extract('value', $vars);
if (elgg_is_empty($value)) {
	return;
}

$icon = (string) elgg_extract('icon', $vars);
if ($icon && !str_starts_with($icon, '<')) {
	$icon = elgg_view_icon($icon);
}

if ($icon) {
	$icon = elgg_format_element('span', [
		'class' => 'elgg-profile-field-icon',
	], $icon);
}

$label = (string) elgg_extract('label', $vars);
$label = elgg_format_element('span', [
	'class' => 'elgg-profile-field-label',
], $icon . $label);

echo elgg_format_element('div', [
	'class' => elgg_extract_class($vars, ['elgg-profile-field']),
	'data-name' => elgg_extract('name', $vars),
], $label . $value);
