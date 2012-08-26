<?php
/**
 * Elgg email input
 * Displays an email input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['class'] Additional CSS class
 */

$vars['type'] = 'email';

$class = elgg_extract('class', $vars, '');
$vars['class'] = "$class elgg-input-email";

$attrs = elgg_format_attributes($vars);
echo "<input $attrs />";