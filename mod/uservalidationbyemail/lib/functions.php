<?php
/**
 * Helper functions
 *
 * @package Elgg.Core.Plugin
 * @subpackage UserValidationByEmail
 */

/**
 * Generate an email activation code.
 *
 * @param int $user_guid The guid of the user
 * @param string $email_address Email address
 * @return string
 */
function uservalidationbyemail_generate_code($user_guid, $email_address) {
	global $CONFIG;

	// Note I bind to site URL, this is important on multisite!
	return md5($user_guid . $email_address . $CONFIG->site->url . get_site_secret());
}

/**
 * Request user validation email.
 * Send email out to the address and request a confirmation.
 *
 * @param int $user_guid The user
 * @return mixed
 */
function uservalidationbyemail_request_validation($user_guid) {
	global $CONFIG;

	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid);

	if (($user) && ($user instanceof ElggUser)) {
		// Work out validate link
		$code = uservalidationbyemail_generate_code($user_guid, $user->email);
		$link = "{$CONFIG->site->url}pg/uservalidationbyemail/confirm?u=$user_guid&c=$code";
		$site = $CONFIG->site;

		// Send validation email
		$subject = sprintf(elgg_echo('email:validate:subject'), $user->name, $site->name);
		$body = sprintf(elgg_echo('email:validate:body'), $user->name, $site->name, $link, $site->name, $site->url);
		$result = notify_user($user->guid, $CONFIG->site->guid, $subject, $body, NULL, 'email');

		if ($result) {
			system_message(elgg_echo('uservalidationbyemail:registerok'));
		}

		return $result;
	}

	return FALSE;
}

/**
 * Validate a user
 *
 * @param unknown_type $user_guid
 * @param unknown_type $code
 * @return unknown
 */
function uservalidationbyemail_validate_email($user_guid, $code) {
	$user = get_entity($user_guid);

	if ($code == uservalidationbyemail_generate_code($user_guid, $user->email)) {
		return uservalidationbyemail_set_user_validation_status($user_guid, true, 'email');
	}

	return false;
}

/**
 * Set the validation status for a user.
 *
 * @param bool $status Validated (true) or false
 * @param string $method Optional method to say how a user was validated
 * @return bool
 */
function uservalidationbyemail_set_user_validation_status($user_guid, $status, $method = '') {
	if (!$status) {
		$method = '';
	}

	if ($status) {
		if (
			(create_metadata($user_guid, 'validated', $status,'', 0, ACCESS_PUBLIC)) &&
			(create_metadata($user_guid, 'validated_method', $method,'', 0, ACCESS_PUBLIC))
		) {
			return TRUE;
		}
	} else {
		$validated = get_metadata_byname($user_guid,  'validated');
		$validated_method = get_metadata_byname($user_guid,  'validated_method');

		if (
			($validated) &&
			($validated_method) &&
			(delete_metadata($validated->id)) &&
			(delete_metadata($validated_method->id))
		)
			return TRUE;
	}

	return FALSE;
}