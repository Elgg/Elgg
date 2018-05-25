<?php
/**
 * Elgg object picker
 *
 * @uses $vars['values']  Array of object guids for already selected objects or null
 * @uses $vars['limit']   Limit number of object (default 0 = no limit)
 * @uses $vars['name']    Name of the returned data array (default "guids")
 * @uses $vars['handler'] Name of page handler used to power search (default "livesearch")
 * @uses $vars['subtype'] which subtype of objects to search for
 */

if (!isset($vars['name'])) {
	$vars['name'] = 'guids';
}

$vars['show_friends'] = false;
$vars['match_on'] = elgg_extract('match_on', $vars, 'objects');
$vars['class'] = elgg_extract_class($vars, ['elgg-object-picker']);

$options = (array) elgg_extract('options', $vars, []);
$options['subtype'] = elgg_extract('subtype', $vars);

$vars['options'] = $options;

// @todo don't abuse userpicker, make it into generic entity picker
echo elgg_view('input/userpicker', $vars);
