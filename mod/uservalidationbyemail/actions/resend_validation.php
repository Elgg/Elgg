<?php
/**
 * Resends validation emails to a user or users by guid
 *
 * @package Elgg.Core.Plugin
 * @subpackage UserValidationByEmail
 */

$user_guids = get_input('user_guids');
$error = FALSE;

if (!$user_guids) {
	register_error(elgg_echo('uservalidationbyemail:errors:unknown_users'));
	forward(REFERRER);
}

$access = access_get_show_hidden_status();
access_show_hidden_entities(TRUE);

foreach ($user_guids as $guid) {
	$user = get_entity($guid);
	if (!$user instanceof ElggUser) {
		$error = TRUE;
		continue;
	}

	// don't resend emails to validated users
	$is_validated = elgg_get_user_validation_status($guid);
	if ($is_validated !== FALSE || !uservalidationbyemail_request_validation($guid)) {
		$error = TRUE;
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
	register_error($error_txt);
} else {
	system_message($message_txt);
}

forward(REFERRER);