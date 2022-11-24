<?php
/**
 * Resends validation emails to a user and/or changes a newly registered users email address
 */

$user = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() {
	return get_user((int) get_input('guid'));
});

if (!$user instanceof \ElggUser) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$hmac = elgg_build_hmac([
	$user->guid,
	$user->time_created,
]);

if (!$hmac->matchesToken(get_input('change_secret'))) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

$email = (string) get_input('email');
if (!elgg_is_valid_email($email)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if ($user->email !== $email) {
	$existing_user = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function () use ($email) {
		return elgg_get_user_by_email($email);
	});

	if (!empty($existing_user)) {
		return elgg_error_response(elgg_echo('registration:dupeemail'));
	}
	
	$user->email = $email;
}

// Get email to show in the next page
elgg_get_session()->set('emailsent', $user->email);

// send new validation email
elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($user) {
	uservalidationbyemail_request_validation($user->guid);
});

return elgg_ok_response('', '', elgg_generate_url('account:validation:email:sent'));
