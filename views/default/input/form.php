<?php
/**
 * Create a form for data submission.
 * Use this view for forms as it provides protection against CSRF attacks.
 *
 * @uses $vars['body']                  The body of the form (made up of other input/xxx views and html
 * @uses $vars['action']                The action URL of the form
 * @uses $vars['action_name']           The name of the action (for targeting particular forms while extending)
 * @uses $vars['method']                The submit method: post (default) or get
 * @uses $vars['enctype']               Set to 'multipart/form-data' if uploading a file
 * @uses $vars['disable_security']      Turn off CSRF security by setting to true
 * @uses $vars['class']                 Additional class for the form
 * @uses $vars['ignore_empty_body']     Boolean (default: true) to determine if an empty body should still draw the form
 * @uses $vars['prevent_double_submit'] Boolean (default: true) disables submit button when form is submitted
 */

$defaults = [
	'method' => 'post',
	'disable_security' => false,
];

$vars = array_merge($defaults, $vars);

$ignore_empty_body = (bool) elgg_extract('ignore_empty_body', $vars, true);
unset($vars['ignore_empty_body']);

$body = elgg_extract('body', $vars);
unset($vars['body']);

if (!$ignore_empty_body && empty($body)) {
	return;
}

$vars['class'] = elgg_extract_class($vars, 'elgg-form');
if (elgg_extract('prevent_double_submit', $vars, true)) {
	elgg_import_esm('input/form-double-submit');
	$vars['class'][] = 'elgg-form-prevent-double-submit';
}

$vars['action'] = elgg_normalize_url((string) $vars['action']);
$vars['method'] = strtolower((string) $vars['method']);

// Generate a security header
if (!$vars['disable_security']) {
	$body = elgg_view('input/securitytoken') . $body;
}

unset($vars['disable_security']);
unset($vars['action_name']);

echo elgg_format_element('form', $vars, $body);
