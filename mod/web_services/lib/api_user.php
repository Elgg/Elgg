<?php
/**
 * A library for managing users of the web services API
 */

// API key functions /////////////////////////////////////////////////////////////////////

/**
 * Generate a new API user for a site, returning a new keypair on success.
 *
 * @return stdClass object or false
 */
function create_api_user() {
	$dbprefix = elgg_get_config('dbprefix');
	$public = _elgg_services()->crypto->getRandomString(40, ElggCrypto::CHARS_HEX);
	$secret = _elgg_services()->crypto->getRandomString(40, ElggCrypto::CHARS_HEX);

	$insert = insert_data("INSERT into {$dbprefix}api_users
		(api_key, secret) values
		('$public', '$secret')");

	if ($insert) {
		return get_api_user($public);
	}

	return false;
}

/**
 * Find an API User's details based on the provided public api key.
 * These users are not users in the traditional sense.
 *
 * @param string $api_key The API Key
 *
 * @return mixed stdClass representing the database row or false.
 */
function get_api_user($api_key) {
	$dbprefix = elgg_get_config('dbprefix');
	$api_key = sanitise_string($api_key);

	$query = "SELECT * from {$dbprefix}api_users"
	. " where api_key='$api_key' and active=1";

	return get_data_row($query);
}

/**
 * Revoke an api user key.
 *
 * @param string $api_key The API Key (public).
 *
 * @return bool
 */
function remove_api_user($api_key) {
	$dbprefix = elgg_get_config('dbprefix');
	$keypair = get_api_user($api_key);
	if ($keypair) {
		return delete_data("DELETE from {$dbprefix}api_users where id={$keypair->id}");
	}

	return false;
}
