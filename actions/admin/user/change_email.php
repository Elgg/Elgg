<?php
/**
 * Admin action to change the email address of an user
 */

$guid = (int) get_input('user_guid');
$email = get_input('email');

if (empty($guid) || empty($email)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if (!is_email_address($email)) {
	return elgg_error_response(elgg_echo('registration:emailnotvalid'));
}

$user = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($guid) {
	return get_user($guid);
});
if (!$user instanceof ElggUser) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$users = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($email) {
	return get_user_by_email($email);
});

if (count($users) > 1) {
	return elgg_error_response(elgg_echo('registration:dupeemail'));
} elseif (count($users) === 1) {
	if ($users[0]->guid !== $user->guid) {
		// email already taken by other user
		return elgg_error_response(elgg_echo('registration:dupeemail'));
	}
}

if ($user->email === $email) {
	// no change
	return elgg_ok_response();
}

$user->email = $email;
$user->save();

return elgg_ok_response('', elgg_echo('email:save:success'));
