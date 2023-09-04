<?php
/**
 * Elgg group picker
 *
 * @uses $vars['values']  Array of group guids for already selected groups or null
 * @uses $vars['limit']   Limit number of groups (default 0 = no limit)
 * @uses $vars['name']    Name of the returned data array (default "groups")
 * @uses $vars['handler'] Name of page handler used to power search (default "livesearch")
 */

if (!isset($vars['name'])) {
	$vars['name'] = 'groups';
}

$vars['match_on'] = elgg_extract('match_on', $vars, 'groups');
$vars['class'] = elgg_extract_class($vars, ['elgg-group-picker']);

echo elgg_view('input/entitypicker', $vars);
