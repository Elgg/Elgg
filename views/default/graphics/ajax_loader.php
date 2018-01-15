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

$attributes = [];

if (isset($vars['id'])) {
	$attributes['id'] = elgg_extract('id', $vars);
}

$attributes['class'] = elgg_extract_class($vars, 'elgg-ajax-loader');

if (elgg_extract('hidden', $vars, true)) {
	$attributes['class'][] = "hidden";
}

$attrs = elgg_format_attributes($attributes);

$loader = <<< END

<div $attrs></div>

END;

echo $loader;
