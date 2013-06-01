<?php
/**
 * Configuration inspection form
 */

echo '<div>';
echo '<p>' . elgg_echo('developers:inspect:help') . '</p>';

$options = array(
	'Actions' => 'Actions',
	'Events' => 'Events',
	'Menus' => 'Menus',
	'Plugin Hooks' => 'Plugin Hooks',
	'Simple Cache' => 'Simple Cache',
	'Views' => 'Views',
	'Widgets' => 'Widgets',
);

if (elgg_is_active_plugin('web_services')) {
	$options['Web Services'] = 'Web Services';
}

ksort($options);

echo elgg_view('input/select', array(
	'name' => 'inspect_type',
	'options_values' => $options,
));

echo elgg_view('input/submit', array('value' => elgg_echo('submit')));
echo '</div>';
