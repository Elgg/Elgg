<?php
/**
 * Displays the input color
 *
 * @uses $vars['value'] Color code in HexCode format
 */

$value = elgg_extract('value', $vars);

if (!preg_match('/^#[a-f0-9]{6}$/i', $value)) {
	return;
}

$vars['style'] = "background-color: $value";
$vars['value'] = strtoupper($vars['value']);
$vars['class'] = 'elgg-color-box';

echo elgg_format_element('span', $vars) . " " . elgg_view("output/text", $vars);
