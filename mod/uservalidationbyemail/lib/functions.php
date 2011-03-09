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

	$site_url = elgg_get_site_url();

	// Note I bind to site URL, this is important on multisite!
	return md5($user_guid . $email_address . $site_url . get_site_secret());
}

/**
 * Request user validation email.
 * Send email out to the address and request a confirmation.
 *
 * @param int  $user_guid       The user's GUID
 * @param bool $admin_requested Was it requested by admin
 * @return mixed
 */
function uservalidationbyemail_request_validation($user_guid, $admin_requested = FALSE) {

	$site = elgg_get_site_entity();

	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid);

	if (($user) && ($user instanceof ElggUser)) {
		// Work out validate link
		$code = uservalidationbyemail_generate_code($user_guid, $user->email);
		$link = "{$site->url}uservalidationbyemail/confirm?u=$user_guid&c=$code";


		// Send validation email
		$subject = elgg_echo('email:validate:subject', array($user->name, $site->name));
		$body = elgg_echo('email:validate:body', array($user->name, $site->name, $link, $site->name, $site->url));
		$result = notify_user($user->guid, $site->guid, $subject, $body, NULL, 'email');

		if ($result && !$admin_requested) {
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
		return elgg_set_user_validation_status($user_guid, true, 'email');
	}

	return false;
}

/**
 * Return a where clause to get entities
 *
 * "Unvalidated" means metadata of validated is not set or not truthy.
 * We can't use elgg_get_entities_from_metadata() because you can't say
 * "where the entity has metadata set OR it's not equal to 1".
 *
 * @return array
 */
function uservalidationbyemail_get_unvalidated_users_sql_where() {
	global $CONFIG;

	$validated_id = get_metastring_id('validated');
	$one_id = get_metastring_id(1);

	// thanks to daveb@freenode for the SQL tips!
	$wheres = array();
	$wheres[] = "e.enabled='no'";
	$wheres[] = "NOT EXISTS (
			SELECT 1 FROM {$CONFIG->dbprefix}metadata md
			WHERE md.entity_guid = e.guid
				AND md.name_id = $validated_id
				AND md.value_id = $one_id)";

	return $wheres;
}