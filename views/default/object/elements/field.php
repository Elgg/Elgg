<?php
/**
 * Outputs a field with a label
 *
 * @uses $vars['label'] Label
 * @uses $vars['value'] Value
 * @uses $vars['class'] Additional classes
 * @uses $vars['icon'] Icon
 * @uses $vars['align'] 'horizontal'|'vertical'
 * @uses $vars['name'] Field name
 */

$value = elgg_extract('value', $vars);
if (!$value) {
	return;
}

$icon = elgg_extract('icon', $vars, '');
if ($icon && !preg_match('/^</', $icon)) {
	$icon = elgg_view_icon($icon);
}

if ($icon) {
	$icon = elgg_format_element('span', [
		'class' => 'elgg-profile-field-icon',
	], $icon);
}

$label = elgg_extract('label', $vars, '');
$label = elgg_format_element('span', [
	'class' => 'elgg-profile-field-label',
], $label);

$class = ['elgg-profile-field'];

$align = elgg_extract('align', $vars, 'vertical');
$class[] = "elgg-profile-field-$align";

$class = elgg_extract_class($vars, $class);

echo elgg_format_element('div', [
	'class' => $class,
	'data-name' => elgg_extract('name', $vars),
], $icon . $label . $value);
