<?php
/**
 * Elgg URL input
 * Displays a URL input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['class'] Additional CSS class
 */

$vars['type'] = 'url';

$class = elgg_extract('class', $vars, '');
$vars['class'] = "$class elgg-input-url";

$attrs = elgg_format_attributes($vars);
echo "<input $attrs />";