<?php
/**
 * Content statistics widget edit view
 */

$num_display = sanitize_int($vars['entity']->num_display, false);
// set default value for display number
if (!$num_display) {
	$num_display = 8;
}

$dropdown = elgg_view('input/select', [
	'name' => 'params[num_display]',
	'value' => $num_display,
	'options' => [5, 8, 10, 12, 15, 20],
]);

echo elgg_format_element('p', [], elgg_echo('widget:numbertodisplay') . ": $dropdown");
