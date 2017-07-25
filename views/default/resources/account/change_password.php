<?php
/**
 * Page for resetting a forgotten password
 *
 * @package Elgg.Core
 * @subpackage Registration
 */

if (elgg_is_logged_in()) {
	forward();
}

elgg_signed_request_gatekeeper();

$user_guid = get_input('u');
$code = get_input('c');

$user = get_user($user_guid);

// don't check code here to avoid automated attacks
if (!$user instanceof ElggUser) {
	register_error(elgg_echo('user:resetpassword:unknown_user'));
	forward();
}

$title = elgg_echo('changepassword');

$params = [
	'guid' => $user_guid,
	'code' => $code,
];
$content = elgg_view_form('user/changepassword', ['class' => 'elgg-form-account'], $params);

$shell = elgg_get_config('walled_garden') ? 'walled_garden' : 'default';

$body = elgg_view_layout('default', [
	'content' => $content,
	'title' => $title,
	'sidebar' => false,
]);
echo elgg_view_page($title, $body, $shell);
