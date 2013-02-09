<?php
/**
 * A library for managing users of the web services API
 */

// API key functions /////////////////////////////////////////////////////////////////////

/**
 * Generate a new API user for a site, returning a new keypair on success.
 *
 * @param int $site_guid The GUID of the site. (default is current site)
 *
 * @return stdClass object or false
 */
function create_api_user($site_guid) {
	global $CONFIG;

	if (!isset($site_guid)) {
		$site_guid = $CONFIG->site_id;
	}

	$site_guid = (int)$site_guid;

	$public = sha1(rand() . $site_guid . microtime());
	$secret = sha1(rand() . $site_guid . microtime() . $public);

	$insert = insert_data("INSERT into {$CONFIG->dbprefix}api_users
		(site_guid, api_key, secret) values
		($site_guid, '$public', '$secret')");

	if ($insert) {
		return get_api_user($site_guid, $public);
	}

	return false;
}

/**
 * Find an API User's details based on the provided public api key.
 * These users are not users in the traditional sense.
 *
 * @param int    $site_guid The GUID of the site.
 * @param string $api_key   The API Key
 *
 * @return mixed stdClass representing the database row or false.
 */
function get_api_user($site_guid, $api_key) {
	global $CONFIG;

	$api_key = sanitise_string($api_key);
	$site_guid = (int)$site_guid;

	$query = "SELECT * from {$CONFIG->dbprefix}api_users"
	. " where api_key='$api_key' and site_guid=$site_guid and active=1";

	return get_data_row($query);
}

/**
 * Revoke an api user key.
 *
 * @param int    $site_guid The GUID of the site.
 * @param string $api_key   The API Key (public).
 *
 * @return bool
 */
function remove_api_user($site_guid, $api_key) {
	global $CONFIG;

	$keypair = get_api_user($site_guid, $api_key);
	if ($keypair) {
		return delete_data("DELETE from {$CONFIG->dbprefix}api_users where id={$keypair->id}");
	}

	return false;
}
