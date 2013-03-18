<?php
/**
 * Groups plugin settings
 */

// set default value
if (!isset($vars['entity']->hidden_projects)) {
	$vars['entity']->hidden_projects = 'no';
}

// set default value
if (!isset($vars['entity']->limited_projects)) {
	$vars['entity']->limited_projects = 'no';
}

echo '<div>';
echo elgg_echo('projects:allowhiddenprojects');
echo ' ';
echo elgg_view('input/dropdown', array(
	'name' => 'params[hidden_projects]',
	'options_values' => array(
		'no' => elgg_echo('option:no'),
		'yes' => elgg_echo('option:yes')
	),
	'value' => $vars['entity']->hidden_projects,
));
echo '</div>';

echo '<div>';
echo elgg_echo('projects:whocancreate');
echo ' ';
echo elgg_view('input/dropdown', array(
	'name' => 'params[limited_projects]',
	'options_values' => array(
		'no' => elgg_echo('LOGGED_IN'),
		'yes' => elgg_echo('admin')
	),
	'value' => $vars['entity']->limited_projects,
));
echo '</div>';
