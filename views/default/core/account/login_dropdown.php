<?php
/**
 * Elgg drop-down login form
 */

if (elgg_is_logged_in()) {
	return true;
}

$body = elgg_view_form('login', [
	'ajax' => true,
], [
	'returntoreferer' => true,
]);

$result = elgg_view('output/url', [
	'href' => elgg_get_login_url([], '#login-dropdown-box'),
	'rel' => 'popup',
	'text' => elgg_echo('login'),
	'data-position' => json_encode([
		'my' => 'right top',
		'at' => 'right bottom',
	]),
]);

$result .= elgg_view_module('dropdown', '', $body, ['id' => 'login-dropdown-box']);

echo elgg_format_element('div', ['id' => 'login-dropdown'], $result);
