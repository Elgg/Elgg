<?php
/**
 * Location input field
 *
 * @uses $vars['entity'] The ElggEntity that has a location
 * @uses $vars['value']  The default value for the location
 * @uses $vars['class']  Additional CSS class
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-input-location');

$defaults = [
	'disabled' => false,
];

if (isset($vars['entity'])) {
	$defaults['value'] = elgg_extract('entity', $vars)->location;
	unset($vars['entity']);
}

$vars = array_merge($defaults, $vars);

echo elgg_view('input/tag', $vars);
