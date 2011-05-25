<?php
/**
 * Generic form template for install forms
 *
 * @uses $vars['variables']
 * @uses $vars['type'] Type of form: admin, database, settings
 */

$variables = $vars['variables'];
$type = $vars['type'];

$form_body = '';
foreach ($variables as $field => $params) {
	$label = elgg_echo("install:$type:label:$field");
	$help = elgg_echo("install:$type:help:$field");
	$params['name'] = $field;

	$form_body .= '<div>';
	$form_body .= "<label>$label</label>";
	$form_body .= elgg_view("input/{$params['type']}", $params);
	$form_body .= "<span class=\"install-help\">$help</span>";
	$form_body .= '</div>';
}

$submit_params = array(
	'value' => elgg_echo('next'),
);
$form_body .= elgg_view('input/submit', $submit_params);

echo $form_body;
