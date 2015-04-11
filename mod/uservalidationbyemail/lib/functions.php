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
	// Note: binding to site URL for multisite.
	$site_url = elgg_get_site_url();
	return elgg_build_hmac([(int)$user_guid, $email_address, $site_url])->getToken();
}

/**
 * Request user validation email.
 * Send email out to the address and request a confirmation.
 *
 * @param int  $user_guid       The user's GUID
 * @param bool $admin_requested Was it requested by admin
 * @return mixed
 */
function uservalidationbyemail_request_validation($user_guid, $admin_requested = 'deprecated') {

	if ($admin_requested != 'deprecated') {
		elgg_deprecated_notice('Second param $admin_requested no more used in uservalidationbyemail_request_validation function', 1.9);
	}

	$site = elgg_get_site_entity();

	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid);

	if (($user) && ($user instanceof ElggUser)) {
		// Work out validate link
		$code = uservalidationbyemail_generate_code($user_guid, $user->email);
		$link = "{$site->url}uservalidationbyemail/confirm?u=$user_guid&c=$code";

		// Get email to show in the next page
		elgg_get_session()->set('emailsent', $user->email);

		$subject = elgg_echo('email:validate:subject', array(
				$user->name,
				$site->name
			), $user->language
		);

		$body = elgg_echo('email:validate:body', array(
				$user->name,
				$site->name,
				$link,
				$site->name,
				$site->url
			), $user->language
		);

		// Send validation email
		$result = notify_user($user->guid, $site->guid, $subject, $body, array(), 'email');

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
	$site_url = elgg_get_site_url();

	$matches = elgg_build_hmac([(int)$user_guid, $user->email, $site_url])->matchesToken($code);
	if (!$matches) {
		return false;
	}

	return elgg_set_user_validation_status($user_guid, true, 'email');
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
	$db_prefix = elgg_get_config('dbprefix');

	$validated_id = elgg_get_metastring_id('validated');
	$one_id = elgg_get_metastring_id('1');

	// thanks to daveb@freenode for the SQL tips!
	$wheres = array();
	$wheres[] = "e.enabled='no'";
	$wheres[] = "NOT EXISTS (
			SELECT 1 FROM {$db_prefix}metadata md
			WHERE md.entity_guid = e.guid
				AND md.name_id = $validated_id
				AND md.value_id = $one_id)";

	return $wheres;
}
