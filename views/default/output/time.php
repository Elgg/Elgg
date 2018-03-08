<?php
/**
 * Displays a formatted time
 *
 * @uses $vars['value'] Date as DateTime, text or a Unix timestamp
 * @uses $vars['format'] Date format
 */

$format = elgg_extract('format', $vars, elgg_get_config('time_format', elgg_echo('input:time_format')), false);
$vars['format'] = $format;

echo elgg_view('output/date', $vars);