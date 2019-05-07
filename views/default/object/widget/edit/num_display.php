<?php
/**
 * Widget edit num_display
 *
 * @uses $vars['entity']  ElggWidget
 * @uses $vars['name']    (optional) The name of the attribute, defaults to 'num_display'
 * @uses $vars['default'] (optional) The default value if no value is set, defaults to 4
 * @uses $vars['step']    (optional) The stepsize used in the number input, defaults to 1
 * @uses $vars['min']     (optional) The smallest value allowed, defaults to 1
 * @uses $vars['max']     (optional) The largest value allowed, defaults first to 'default_limit'
 *                        and as last straw to max(min, 20)
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
$vars['#label'] = elgg_extract('label', $vars);
unset($vars['label']);

$value = (int) $widget->$name;
if ($value < 1) {
	$value = (int) elgg_extract('default', $vars, 4);
}
$vars['value'] = $value;

$vars['step'] = (int) elgg_extract('step', $vars, 1);

$min = (int) elgg_extract('min', $vars, 1);
$vars['min'] = max($min, 1);

$max = (int) elgg_extract('max', $vars, false);
if (!$max) {
	$max = (int) elgg_get_config('default_limit');
}
if (!$max) {
	$max = max($min, 20);
}
$vars['max'] = max($max, $vars['min']);

$vars['#type'] = 'number';

echo elgg_view_field($vars);
