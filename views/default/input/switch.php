<?php
/**
 * Elgg switch input
 *
 * Displays a checkbox input tag used as a switch.
 * Pass a truthy 'value' to have the switch checked/on
 *
 * @since 6.1
 */

$current_value = (bool) elgg_extract('value', $vars, false);

$vars['class'] = elgg_extract_class($vars, 'elgg-input-switch');
$vars['switch'] = true;
$vars['default'] = 0;
$vars['value'] = 1;
$vars['checked'] = $current_value;

echo elgg_view('input/checkbox', $vars);
