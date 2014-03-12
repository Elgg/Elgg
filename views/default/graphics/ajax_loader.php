<?php
/**
 * Elgg AJAX loader
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['id']     CSS id
 * @uses $vars['class']  Optional additional CSS class
 * @uses $vars['hidden'] Begin hidden? (true)
 */

$attributes = array();

if (isset($vars['id'])) {
	$attributes['id'] = $vars['id'];
}

$class = 'elgg-ajax-loader';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

if (elgg_extract('hidden', $vars, true)) {
	$class = "$class hidden";
}

$attributes['class'] = $class;

$attrs = elgg_format_attributes($attributes);

$loader = <<< END

<div $attrs></div>

END;

echo $loader;
