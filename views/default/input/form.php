<?php
/**
 * Create a form for data submission.
 * Use this view for forms as it provides protection against CSRF attacks.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['header'] The header rendered before the form body
 * @uses $vars['body'] The body of the form (made up of other input/xxx views and html
 * @uses $vars['footer'] The footer rendered after the form body
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

$vars['class'] = elgg_extract_class($vars, 'elgg-form');

$vars['action'] = elgg_normalize_url(elgg_extract('action', $vars, ''));
unset($vars['action_name']);

$vars['method'] = strtolower(elgg_extract('method', $vars, 'POST'));

$body = elgg_extract('body', $vars, '');
unset($vars['body']);

// Add CSRF tokens
$tokens = '';
if (!elgg_extract('disable_security', $vars, false)) {
	$tokens = elgg_view_input('securitytoken');
}
unset($vars['disable_security']);

$header = '';
if (isset($vars['header'])) {
	$header = elgg_format_element('header', [
		'class' => 'elgg-form-header',
	], $vars['header']);
	unset($vars['header']);
}

$body = elgg_format_element('fieldset', [
	'class' => 'elgg-form-body',
], $tokens . $body);

$footer = '';
if (isset($vars['footer'])) {
	$footer = elgg_format_element('footer', [
		'class' => 'elgg-form-footer',
	], $vars['footer']);
	unset($vars['footer']);
}

echo elgg_format_element('form', $vars, $header . $body . $footer);
