<?php
/**
 * Install database page
 *
 * @uses $vars['failure'] Settings file exists but something went wrong
 */

if (isset($vars['failure']) && $vars['failure']) {
	echo elgg_autop(elgg_echo('install:database:error'));
	$vars['refresh'] = true;
	$vars['advance'] = false;
	echo elgg_view('install/nav', $vars);
	return;
}

echo elgg_autop(elgg_echo('install:database:instructions'));
	
$vars['type'] = 'database';

$form_vars = [
	'action' => elgg_get_current_url(),
	'disable_security' => true,
];

echo elgg_view_form('install/template', $form_vars, $vars);
