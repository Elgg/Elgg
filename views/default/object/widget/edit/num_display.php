<?php
/**
 * Widget edit num_display
 *
 * @uses $vars['entity']  ElggWidget
 * @uses $vars['name']    (optional) The name of the attribute, defaults to 'num_display'
 * @uses $vars['default'] (optional) The default value if no value is set, defaults to first option
 */

$widget = elgg_extract('entity', $vars);
if (!($widget instanceof \ElggWidget)) {
	return;
}
unset($vars['widget']);

$name = elgg_extract('name', $vars, 'num_display');
$vars['name'] = "params[{$name}]";

if (!isset($vars['label'])) {
	$vars['label'] = elgg_echo('widget:numbertodisplay');
}

if (!isset($vars['options'])) {
	$vars['options'] = [5, 8, 10, 12, 15, 20];
}

$value = sanitize_int($widget->$name, false);
if (!$value) {
	$value = elgg_extract('default', $vars, $vars['options'][0]);
}
$vars['value'] = $value;

echo elgg_view_input('select', $vars);
