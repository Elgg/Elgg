<?php

echo elgg_autop(elgg_echo('install:settings:instructions'));

$vars['type'] = 'settings';

$form_vars = [
	'action' => elgg_get_current_url(),
	'disable_security' => true,
];

echo elgg_view_form('install/template', $form_vars, $vars);
