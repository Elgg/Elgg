<?php

/**
 * A library for managing users of the web services API
 */

/**
 * Generate a new API user for a site, returning a new keypair on success.
 *
 * @param int $site_guid The GUID of the site. (default is current site)
 * @return \Elgg\WebServices\ApiUser|false
 */
function create_api_user($site_guid) {
	return _elgg_ws_registry()->createApiUser($site_guid);
}

/**
 * Find an API User's details based on the provided public api key.
 * These users are not users in the traditional sense.
 *
 * @param int    $site_guid The GUID of the site.
 * @param string $api_key   The API Key
 *
 * @return \Elgg\WebServices\ApiUser|false
 */
function get_api_user($site_guid, $api_key) {
	return _elgg_ws_registry()->getApiUser($site_guid, $api_key);
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
	return _elgg_ws_registry()->removeApiUser($site_guid, $api_key);
}
