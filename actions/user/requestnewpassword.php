<?php
/**
 * Action to request a new password.
 */

$username = get_input('username');
if (empty($username)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

// allow email addresses
if (elgg_strpos($username, '@') !== false && ($users = get_user_by_email($username))) {
	$username = $users[0]->username;
}

$user = get_user_by_username($username);
if (!$user instanceof \ElggUser) {
	return elgg_error_response(elgg_echo('user:username:notfound', [$username]));
}

elgg_request_new_password($user);

return elgg_ok_response('', elgg_echo('user:password:changereq:success'), '');
