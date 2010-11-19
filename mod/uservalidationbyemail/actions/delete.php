<?php
/**
 * Delete a user or users by guid
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

	// don't delete validated users
	$is_validated = elgg_get_user_validation_status($guid);
	if ($is_validated !== FALSE || !$user->delete()) {
		$error = TRUE;
		continue;
	}
}

access_show_hidden_entities($access);

if (count($user_guids) == 1) {
	$message_txt = elgg_echo('uservalidationbyemail:messages:deleted_user');
	$error_txt = elgg_echo('uservalidationbyemail:errors:could_not_delete_user');
} else {
	$message_txt = elgg_echo('uservalidationbyemail:messages:deleted_users');
	$error_txt = elgg_echo('uservalidationbyemail:errors:could_not_delete_users');
}

if ($error) {
	register_error($error_txt);
} else {
	system_message($message_txt);
}

forward(REFERRER);