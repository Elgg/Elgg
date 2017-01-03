<?php
/**
 * User Picker.  Sends an array of user guids.
 *
 * @uses $vars['guids'] Array of user guids for already selected users or null
 * @uses $vars['values'] Alias for 'guids'
 * @uses $vars['name']   Name of the returned data array (default "members")
 * @uses $vars['filter'] (optional) override userpickers default filter that allows you to limit to friends
 */

$vars['name'] = elgg_extract('name', $vars, 'members');
$vars['class'] = elgg_extract_class($vars, 'elgg-input-user-picker');
$vars['match_on'] = 'users';

if (!isset($vars['filter'])) {
	$vars['filter'] = elgg_view_field([
		'#type' => 'checkbox',
		'#label' => elgg_echo('userpicker:only_friends'),
		'name' => 'match_on',
		'value' => 'true',
	]);
}

if (!isset($vars['guids'])) {
	$vars['guids'] = (array) elgg_extract('values', $vars, []);
}
unset($vars['values']);

echo elgg_view('input/entitypicker', $vars);
