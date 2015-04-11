<?php
/**
 * Location input field
 *
 * @uses $vars['entity'] The ElggEntity that has a location
 * @uses $vars['value']  The default value for the location
 * @uses $vars['class']  Additional CSS class
 */

$vars['class'] = (array) elgg_extract('class', $vars, []);
$vars['class'][] = 'elgg-input-location';

$defaults = array(
	'disabled' => false,
);

if (isset($vars['entity'])) {
	$defaults['value'] = $vars['entity']->location;
	unset($vars['entity']);
}

$vars = array_merge($defaults, $vars);

echo elgg_view('input/tag', $vars);
