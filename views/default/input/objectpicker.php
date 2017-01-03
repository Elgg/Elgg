<?php
/**
 * Object Picker.  Sends an array of object guids.
 *
 * @uses $vars['guids'] Array of object guids for already selected objects or null
 * @uses $vars['name']  Name of the returned data array (default "objects")
 * @uses $vars['class'] Optional extra class
 */

$vars['name'] = elgg_extract('name', $vars, 'objects');
$vars['class'] = elgg_extract_class($vars, 'elgg-input-object-picker');
$vars['match_on'] = 'objects';

if (!isset($vars['guids'])) {
	$vars['guids'] = (array) elgg_extract('values', $vars, []);
}
unset($vars['values']);

echo elgg_view('input/entitypicker', $vars);
