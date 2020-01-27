<?php
/**
 * Elgg AJAX loader
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

echo elgg_format_element('div', $attributes);
