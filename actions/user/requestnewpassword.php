<?php
/**
 * Action to request a new password.
 */

$username = get_input('username');
if (empty($username)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

// also allow by email addresses
$user = elgg_get_user_by_username($username, true);
if (!$user instanceof \ElggUser) {
	return elgg_error_response(elgg_echo('user:username:notfound', [$username]));
}

elgg_request_new_password($user);

return elgg_ok_response('', elgg_echo('user:password:changereq:success'), '');
