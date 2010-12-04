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

$body = $vars['body'];
unset($vars['body']);

$vars['action'] = elgg_normalize_url($vars['action']);

// @todo why?
$vars['method'] = strtolower($vars['method']);

// Generate a security header
if (!$vars['disable_security']) {
	$body .= elgg_view('input/securitytoken');
}
unset($vars['disable_security']);


$attributes = elgg_format_attributes($vars);

echo "<form $attributes>$body</form>";