<?php
/**
 * Database form
 *
 * @uses $vars['variables'] Array of form variables. See ElggInstaller.
 */

$vars['type'] = 'database';
$form_body = elgg_view('install/forms/template', $vars);

// @todo bug in current_page_url() with :8080 sites
//$url = current_page_url();
$url = '/install.php?step=database';

$params = array(
	'body' => $form_body,
	'action' => $url,
	'disable_security' => TRUE,
	'js' => 'onsubmit="return elggCheckFormSubmission()"',
);
echo elgg_view('input/form', $params);
