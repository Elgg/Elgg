<?php
/**
 * Form body for refining the log browser search.
 * Look for a particular person or in a time window.
 *
 * @uses $vars['username']
 * @uses $vars['ip_address']
 * @uses $vars['timelower']
 * @uses $vars['timeupper']
 */

$lowerval = '';
if (isset($vars['timelower']) && $vars['timelower']) {
	$lowerval = date('r', $vars['timelower']);
}

$upperval = '';
if (isset($vars['timeupper']) && $vars['timeupper']) {
	$upperval = date('r', $vars['timeupper']);
}

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('logbrowser:user'),
	'name' => 'search_username',
	'value' => elgg_extract('username', $vars),
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('logbrowser:ip_address'),
	'name' => 'ip_address',
	'value' => elgg_extract('ip_address', $vars),
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('logbrowser:starttime'),
	'name' => 'timelower',
	'value' => $lowerval,
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('logbrowser:endtime'),
	'name' => 'timeupper',
	'value' => $upperval,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('search'),
]);

elgg_set_form_footer($footer);
