<?php

echo elgg_autop(elgg_echo('install:settings:instructions'));

$vars['type'] = 'settings';

$url = current_page_url();

$form_vars = [
	'action' => $url,
	'disable_security' => true,
];

echo elgg_view_form('install/template', $form_vars, $vars);
