<?php
/**
 * Validate a user or users by guid
 */

$user_guids = get_input('user_guids');
$error = false;

if (!$user_guids) {
	return elgg_error_response(elgg_echo('uservalidationbyemail:errors:unknown_users'));
}

$access = access_show_hidden_entities(true);

foreach ($user_guids as $guid) {
	$user = get_entity($guid);
	if (!$user instanceof ElggUser) {
		$error = true;
		continue;
	}

	// only validate if not validated
	$is_validated = elgg_get_user_validation_status($guid);
	$validate_success = elgg_set_user_validation_status($guid, true, 'manual');

	if ($is_validated !== false || !($validate_success && $user->enable())) {
		$error = true;
		continue;
	}
}

access_show_hidden_entities($access);

if (count($user_guids) == 1) {
	$message_txt = elgg_echo('uservalidationbyemail:messages:validated_user');
	$error_txt = elgg_echo('uservalidationbyemail:errors:could_not_validate_user');
} else {
	$message_txt = elgg_echo('uservalidationbyemail:messages:validated_users');
	$error_txt = elgg_echo('uservalidationbyemail:errors:could_not_validate_users');
}

if ($error) {
	return elgg_error_response($error_txt);
}

return elgg_ok_response('', $message_txt);
