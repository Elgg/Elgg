<?php

/**
 * Helper view that can be used to filter vars for all input views
 */
$input_type = elgg_extract('input_type', $vars);
unset($vars['input_type']);

$input = elgg_view("input/$input_type", $vars);

echo elgg_format_element('div', [
	'class' => 'elgg-field-input',
], $input);
