<?php
/**
 * Display a location
 *
 * @uses $vars['entity'] The ElggEntity that has a location
 * @uses $vars['value']  The location string if the entity is not passed
 */

if (isset($vars['entity'])) {
	$vars['value'] = $vars['entity']->location;
	unset($vars['entity']);
}

echo elgg_view('output/tag', $vars);
