<?php
/**
 * Install create admin account page
 */

echo autop(elgg_echo('install:admin:instructions'));

$vars['type'] = 'admin';

$url = current_page_url();

$form_vars = array(
	'action' => $url,
	'disable_security' => TRUE,
);

echo elgg_view_form('install/template', $form_vars, $vars);
