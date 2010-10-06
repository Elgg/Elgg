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
	$params['internalname'] = $field;

	$form_body .= '<p>';
	$form_body .= "<label>$label</label>";
	$form_body .= elgg_view("input/{$params['type']}", $params);
	$form_body .= "<span class=\"install_help\">$help</span>";
	$form_body .= '</p>';
}

$form_body .= elgg_view('input/submit', array('value' => elgg_echo('next')));

echo $form_body;
