<?php
/**
 * Action to request a new password.
 */

$username = get_input('username');

// allow email addresses
if (elgg_strpos($username, '@') !== false && ($users = get_user_by_email($username))) {
	$username = $users[0]->username;
}

$user = get_user_by_username($username);
if (!$user) {
	return elgg_error_response(elgg_echo('user:username:notfound', [$username]));
}

if (!send_new_password_request($user->guid)) {
	return elgg_error_response(elgg_echo('user:password:changereq:fail'));
}

return elgg_ok_response('', elgg_echo('user:password:changereq:success'), '');
