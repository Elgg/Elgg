<?php
/**
 * Admin action to change the email address of an user
 */

$guid = (int) get_input('user_guid');
$email = (string) get_input('email');

if (empty($guid) || empty($email)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if (!elgg_is_valid_email($email)) {
	return elgg_error_response(elgg_echo('registration:emailnotvalid'));
}

$user = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($guid) {
	return get_user($guid);
});
if (!$user instanceof \ElggUser) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if ($user->email === $email) {
	// no change
	return elgg_ok_response();
}

$existing_user = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($email) {
	return elgg_get_user_by_email($email);
});

if ($existing_user instanceof \ElggUser && $user->guid !== $existing_user->guid) {
	return elgg_error_response(elgg_echo('registration:dupeemail'));
}

$user->email = $email;
$user->save();

return elgg_ok_response('', elgg_echo('email:save:success'));
