<?php

echo elgg_view('output/longtext', array('value' => elgg_echo('install:settings:instructions')));

$vars['type'] = 'settings';

$url = current_page_url();

$form_vars = array(
	'action' => $url,
	'disable_security' => TRUE,
);

echo elgg_view_form('install/template', $form_vars, $vars);
