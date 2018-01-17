<?php
/**
 * Displays a formatted date
 *
 * @uses $vars['value'] Date as DateTime, text or a Unix timestamp
 * @uses $vars['format'] Date format
 */

$format = elgg_extract('format', $vars, elgg_echo('input:date_format'), false);

$value = elgg_extract('value', $vars);
if (!$value) {
	return;
}

try {
	$dt = \Elgg\Values::normalizeTime($value);
	
	$attributes = [
		'datetime' => $dt->format('c'),
	];
	
	echo elgg_format_element('time', $attributes, $dt->format($format));
} catch (DataFormatException $ex) {
}
