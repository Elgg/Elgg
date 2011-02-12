<?php
/**
 * Site settings form
 *
 * @uses $vars['variables'] Array of form variables. See ElggInstaller.
 * 
 * @todo Forms 1.8: Convert to use elgg_view_form
 */

$vars['type'] = 'settings';
$form_body = elgg_view('install/forms/template', $vars);

$url = current_page_url();

$params = array(
	'body' => $form_body,
	'action' => $url,
	'disable_security' => TRUE,
	'js' => 'onsubmit="return elggCheckFormSubmission()"',
);
echo elgg_view('input/form', $params);
