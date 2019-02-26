<?php
/**
 * Page for resetting a forgotten password
 *
 * @package Elgg.Core
 * @subpackage Registration
 */

elgg_signed_request_gatekeeper();

$user_guid = get_input('u');
$code = get_input('c');

$user = get_user($user_guid);

// don't check code here to avoid automated attacks
if (!$user instanceof ElggUser) {
	throw new \Elgg\EntityNotFoundException(elgg_echo('user:changepassword:unknown_user'));
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
