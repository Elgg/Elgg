<?php
/**
 * Set a user's email address
 */

$email = get_input('email');
$user_guid = (int) get_input('guid');

if ($user_guid) {
	$user = get_user($user_guid);
} else {
	$user = elgg_get_logged_in_user_entity();
}

if (!is_email_address($email)) {
	return elgg_error_response(elgg_echo('email:save:fail'));
}

if (!$user) {
	return elgg_error_response(elgg_echo('email:save:fail'));
}

if (get_user_by_email($email)) {
	return elgg_error_response(elgg_echo('registration:dupeemail'));
}

$user->email = $email;
if (!$user->save()) {
	return elgg_error_response(elgg_echo('email:save:fail'));
}

return elgg_ok_response('', elgg_echo('email:save:success'));
