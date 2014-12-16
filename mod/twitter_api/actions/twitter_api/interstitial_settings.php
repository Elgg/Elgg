<?php
/**
 * Save settings for first time logins with twitter
 */
elgg_make_sticky_form('twitter_api_interstitial');

$display_name = get_input('display_name');
$email = get_input('email');
$password_1 = get_input('password_1', null, false);
$password_2 = get_input('password_2', null, false);

if (!$display_name) {
	register_error(elgg_echo('twitter_api:interstitial:no_display_name'));
	forward(REFERER);
}

if ($email && !is_email_address($email)) {
	register_error(elgg_echo('twitter_api:interstitial:invalid_email'));
	forward(REFERER);
}

$existing_user = get_user_by_email($email);
if ($email && $existing_user) {
	register_error(elgg_echo('twitter_api:interstitial:existing_email'));
	forward(REFERER);
}

if ($password_1 && !($password_1 == $password_2)) {
	register_error(elgg_echo('twitter_api:interstitial:password_mismatch'));
	forward(REFERER);
}

$user = elgg_get_logged_in_user_entity();
$user->name = $display_name;

if ($email) {
	$user->email = $email;
}

if ($password_1) {
	$user->setPassword($password_1);
}

if (!$user->save()) {
	register_error(elgg_echo('twitter_api:interstitial:cannot_save'));
	forward(REFERER);
}

elgg_clear_sticky_form('twitter_api_interstitial');

system_message(elgg_echo('twitter_api:interstitial:saved'));
forward('/');