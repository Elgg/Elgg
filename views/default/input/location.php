<?php
/**
 * Location input field
 *
 * @uses $vars['entity'] The ElggEntity that has a location
 * @uses $vars['value']  The default value for the location
 */

$defaults = array(
	'class' => 'elgg-input-location',
	'disabled' => FALSE,
);

if (isset($vars['entity'])) {
	$defaults['value'] = $vars['entity']->location;
	unset($vars['entity']);
}

$vars = array_merge($defaults, $vars);

echo elgg_view('input/tag', $vars);
