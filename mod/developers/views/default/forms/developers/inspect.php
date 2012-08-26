<?php
/**
 * Configuration inspection form
 */

echo '<div>';
echo '<p>' . elgg_echo('developers:inspect:help') . '</p>';

echo elgg_view('input/select', array(
	'name' => 'inspect_type',
	'options_values' => array(
		'Actions' => 'Actions',
		'Events' => 'Events',
		'Menus' => 'Menus',
		'Plugin Hooks' => 'Plugin Hooks',
		'Simple Cache' => 'Simple Cache',
		'Views' => 'Views',
		'Web Services' => 'Web Services',
		'Widgets' => 'Widgets',
	),
));

echo elgg_view('input/submit', array('value' => elgg_echo('submit')));
echo '</div>';
