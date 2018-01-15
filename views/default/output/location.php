<?php
/**
 * Display a location
 *
 * @uses $vars['entity'] The ElggEntity that has a location
 * @uses $vars['value']  The location string if the entity is not passed
 */

if (isset($vars['entity'])) {
	$vars['value'] = elgg_extract('entity', $vars)->location;
	unset($vars['entity']);
}

// Fixes #4566 we used to allow arrays of strings for location
if (is_array($vars['value'])) {
	$vars['value'] = implode(', ', $vars['value']);
}

echo elgg_view('output/tag', $vars);
