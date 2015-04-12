<?php
/**
 * Create a form for data submission.
 * Use this view for forms as it provides protection against CSRF attacks.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['body'] The body of the form (made up of other input/xxx views and html
 * @uses $vars['action'] The action URL of the form
 * @uses $vars['action_name'] The name of the action (for targeting particular forms while extending)
 * @uses $vars['method'] The submit method: post (default) or get
 * @uses $vars['enctype'] Set to 'multipart/form-data' if uploading a file
 * @uses $vars['disable_security'] turn off CSRF security by setting to true
 * @uses $vars['class'] Additional class for the form
 */

$defaults = array(
	'method' => 'post',
	'disable_security' => FALSE,
);

$vars = array_merge($defaults, $vars);

$vars['class'] = (array) elgg_extract('class', $vars, []);
$vars['class'][] = 'elgg-form';

$vars['action'] = elgg_normalize_url($vars['action']);
$vars['method'] = strtolower($vars['method']);

$body = $vars['body'];
unset($vars['body']);

// Generate a security header
if (!$vars['disable_security']) {
	$body = elgg_view('input/securitytoken') . $body;
}
unset($vars['disable_security']);
unset($vars['action_name']);

echo elgg_format_element('form', $vars, "<fieldset>$body</fieldset>");
