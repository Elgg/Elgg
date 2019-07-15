<?php
/**
 * Displays formatted date-time
 *
 * @uses $vars['value'] Date and Time
 * @uses $vars['format'] Date format (Default: 2019-07-13 01:59)
 */
 
$vars['format'] = elgg_extract('format', $vars, "Y-m-d H:i");

echo elgg_view("output/date", $vars);
