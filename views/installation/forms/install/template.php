<?php
/**
 * Generic form template for install forms
 *
 * @uses $vars['variables']
 * @uses $vars['type'] Type of form: admin, database, settings
 */

$variables = elgg_extract('variables', $vars, []);
$type = elgg_extract('type', $vars);
$form_body = '';

foreach ($variables as $name => $params) {
	$params['#type'] = elgg_extract('type', $params, 'text');
	$params['#label'] = elgg_echo("install:$type:label:$name");
	$params['#help'] = elgg_echo("install:$type:help:$name");
	$params['name'] = $name;

	$form_body .= elgg_view_field($params);
}

$form_body .= elgg_format_element('div', [
	'class' => 'elgg-install-nav',
], elgg_view('input/submit', [
	'value' => elgg_echo('install:next'),
]));

echo $form_body;
