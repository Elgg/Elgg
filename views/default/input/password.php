<?php
/**
 * Elgg password input
 * Displays a password input field
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['name']  The name of the input field
 * @uses $vars['class'] Additional CSS class
 */

$vars['type'] = 'password';

$class = elgg_extract('class', $vars, '');
$vars['class'] = "$class elgg-input-password";

$attrs = elgg_format_attributes($vars);
echo "<input $attrs />";