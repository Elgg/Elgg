<?php
/**
 * Admin account form
 *
 * @uses $vars['variables'] Array of form variables. See ElggInstaller.
 */

$vars['type'] = 'admin';
$form_body = elgg_view('install/forms/template', $vars);


// @todo bug in current_page_url() with :8080 sites
//$url = current_page_url();
$url = '/install.php?step=admin';

$params = array(
	'body' => $form_body,
	'action' => $url,
	'disable_security' => TRUE,
);
echo elgg_view('input/form', $params);
