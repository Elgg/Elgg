<?php
/**
 * Groups plugin settings
 */

// set default value
if (!isset($vars['entity']->hidden_groups)) {
	$vars['entity']->hidden_groups = 'no';
}

echo '<div>';
echo elgg_echo('groups:allowhiddengroups');
echo ' ';
echo elgg_view('input/dropdown', array(
	'name' => 'params[hidden_groups]',
	'options_values' => array(
		'no' => elgg_echo('option:no'),
		'yes' => elgg_echo('option:yes')
	),
	'value' => $vars['entity']->hidden_groups,
));
echo '</div>';
