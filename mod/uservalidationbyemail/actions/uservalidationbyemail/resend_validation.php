<?php
/**
 * Resends validation emails to a user or users by guid
 */

$user_guids = (array) get_input('user_guids');
if (!$user_guids) {
	return elgg_error_response(elgg_echo('uservalidationbyemail:errors:unknown_users'));
}

$error = false;
$access = access_show_hidden_entities(true);

foreach ($user_guids as $guid) {
	$user = get_user($guid);
	if (empty($user)) {
		continue;
	}

	// don't resend emails to validated users
	if ($user->isValidated() !== false || !uservalidationbyemail_request_validation($guid)) {
		$error = true;
		continue;
	}
}

access_show_hidden_entities($access);

if (count($user_guids) == 1) {
	$message_txt = elgg_echo('uservalidationbyemail:messages:resent_validation');
	$error_txt = elgg_echo('uservalidationbyemail:errors:could_not_resend_validation');
} else {
	$message_txt = elgg_echo('uservalidationbyemail:messages:resent_validations');
	$error_txt = elgg_echo('uservalidationbyemail:errors:could_not_resend_validations');
}

if ($error) {
	return elgg_error_response($error_txt);
}

return elgg_ok_response('', $message_txt);
