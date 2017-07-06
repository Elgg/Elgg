<?php
/**
 * Install create admin account page
 */

echo elgg_autop(elgg_echo('install:admin:instructions'));

$vars['type'] = 'admin';

$url = current_page_url();

$form_vars = [
	'action' => $url,
	'disable_security' => true,
];

echo elgg_view_form('install/template', $form_vars, $vars);
