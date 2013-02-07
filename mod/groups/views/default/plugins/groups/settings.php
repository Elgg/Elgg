<?php
/**
 * Groups plugin settings
 */

// set default value
if (!isset($vars['entity']->hidden_groups)) {
	$vars['entity']->hidden_groups = 'no';
}

// set default value
if (!isset($vars['entity']->limited_groups)) {
	$vars['entity']->limited_groups = 'no';
}

echo '<div>';
echo elgg_echo('groups:allowhiddengroups');
echo ' ';
echo elgg_view('input/select', array(
	'name' => 'params[hidden_groups]',
	'options_values' => array(
		'no' => elgg_echo('option:no'),
		'yes' => elgg_echo('option:yes')
	),
	'value' => $vars['entity']->hidden_groups,
));
echo '</div>';

echo '<div>';
echo elgg_echo('groups:whocancreate');
echo ' ';
echo elgg_view('input/dropdown', array(
	'name' => 'params[limited_groups]',
	'options_values' => array(
		'no' => elgg_echo('LOGGED_IN'),
		'yes' => elgg_echo('admin')
	),
	'value' => $vars['entity']->limited_groups,
));
echo '</div>';
