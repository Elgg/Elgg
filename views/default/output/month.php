<?php
/**
 * Displays formatted month
 *
 * @uses $vars['value'] Month and Year
 * @uses $vars['format'] Display format (Default: Jul 2019)
 */
 
$vars['format'] = elgg_extract('format', $vars, "M Y");

echo elgg_view("output/date", $vars);
