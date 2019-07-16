<?php
/**
 * Displays formatted week
 *
 * @uses $vars['value'] Year and Week
 * @uses $vars['format'] Display format (Default: 2019 28)
 */
 
$vars['format'] = elgg_extract('format', $vars, "Y W");

echo elgg_view("output/date", $vars);
