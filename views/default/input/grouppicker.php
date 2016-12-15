<?php
/**
 * Group Picker.  Sends an array of group guids.
 *
 * @uses $vars['guids'] Array of group guids for already selected groups or null
 * @uses $vars['name']  Name of the returned data array (default "groups")
 * @uses $vars['class'] Optional extra class
 */

$vars['name'] = elgg_extract('name', $vars, 'groups');
$vars['class'] = elgg_extract_class($vars, 'elgg-input-group-picker');
$vars['match_on'] = 'groups';

if (!isset($vars['guids'])) {
	$vars['guids'] = (array) elgg_extract('values', $vars, []);
}
unset($vars['values']);

echo elgg_view('input/entitypicker', $vars);
