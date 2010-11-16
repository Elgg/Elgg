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
 * @param int    $user_guid     The guid of the user
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
 * @param int $user_guid The user's GUID
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
		$subject = elgg_echo('email:validate:subject', array($user->name, $site->name));
		$body = elgg_echo('email:validate:body', array($user->name, $site->name, $link, $site->name, $site->url));
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
 * @param int    $user_guid
 * @param string $code
 * @return bool
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
 * @param int    $user_guid The user's GUID
 * @param bool   $status Validated (true) or false
 * @param string $method Optional method to say how a user was validated
 * @return bool
 */
function uservalidationbyemail_set_user_validation_status($user_guid, $status, $method = '') {

	$result1 = create_metadata($user_guid, 'validated', $status, '', 0, ACCESS_PUBLIC, false);
	$result2 = create_metadata($user_guid, 'validated_method', $method, '', 0, ACCESS_PUBLIC, false);
	if ($result1 && $result2) {
		return true;
	} else {
		return false;
	}
}

/**
 * Returns the validation status of a user.
 *
 * @param int $user_guid The user's GUID
 * @return bool
 */
function uservalidationbyemail_get_user_validation_status($user_guid) {
	$md = get_metadata_byname($user_guid, 'validated');

	if ($md && $md->value) {
		return TRUE;
	}

	return FALSE;
}
