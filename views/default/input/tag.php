<?php
/**
 * Elgg tag input
 *
 * Accepts a single tag value
 *
 * @uses $vars['value'] The default value for the tag
 * @uses $vars['class'] Additional CSS class
 */

$vars['type'] = 'text';

$class = elgg_extract('class', $vars, '');
$vars['class'] = "$class elgg-input-tag";

$attrs = elgg_format_attributes($vars);
echo "<input $attrs />";