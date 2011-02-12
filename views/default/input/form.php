<?php
/**
 * Create a form for data submission.
 * Use this view for forms rather than creating a form tag in the wild as it provides
 * extra security which help prevent CSRF attacks.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['body'] The body of the form (made up of other input/xxx views and html
 * @uses $vars['disable_security'] turn off CSRF security by setting to true
 */

$defaults = array(
	'method' => "post",
	'disable_security' => FALSE,
);

$vars = array_merge($defaults, $vars);

$vars['action'] = elgg_normalize_url($vars['action']);
$vars['method'] = strtolower($vars['method']);

$body = $vars['body'];
unset($vars['body']);

// Generate a security header
if (!$vars['disable_security']) {
	$body = elgg_view('input/securitytoken') . $body;
}
unset($vars['disable_security']);

$attributes = elgg_format_attributes($vars);

echo "<form $attributes><fieldset>$body</fieldset></form>";