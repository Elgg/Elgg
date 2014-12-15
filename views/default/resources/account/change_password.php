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

$user_guid = get_input('u');
$code = get_input('c');

$user = get_entity($user_guid);

// don't check code here to avoid automated attacks
if (!$user instanceof ElggUser) {
	register_error(elgg_echo('user:resetpassword:unknown_user'));
	forward();
}

$title = elgg_echo('changepassword');

$params = array(
	'guid' => $user_guid,
	'code' => $code,
);
$content = elgg_view_form('user/changepassword', array('class' => 'elgg-form-account'), $params);

if (elgg_get_config('walled_garden')) {
	elgg_load_css('elgg.walled_garden');
	$body = elgg_view_layout('walled_garden', array('content' => $content));
	echo elgg_view_page($title, $body, 'walled_garden');
} else {
	$body = elgg_view_layout('one_column', array(
		'title' => $title,
		'content' => $content,
	));
	echo elgg_view_page($title, $body);
}

