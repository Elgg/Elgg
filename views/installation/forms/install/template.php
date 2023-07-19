<?php
/**
 * Generic form template for install forms
 *
 * @uses $vars['variables']
 * @uses $vars['type'] Type of form: admin, database, settings
 */

$variables = elgg_extract('variables', $vars, []);
$type = elgg_extract('type', $vars);

foreach ($variables as $name => $params) {
	$params['#type'] = elgg_extract('type', $params, 'text');
	$params['#label'] = elgg_echo("install:{$type}:label:{$name}");
	$params['#help'] = elgg_echo("install:{$type}:help:{$name}");
	$params['name'] = $name;

	echo elgg_view_field($params);
}

echo elgg_format_element('div', [
	'class' => 'elgg-install-nav',
], elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('install:next'),
]));
