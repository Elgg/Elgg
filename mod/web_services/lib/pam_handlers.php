<?php
/**
 * The PAM handlers for the webservices plugin
 *
 * @see register_pam_handler()
 */

use Elgg\Exceptions\SecurityException;

/**
 * Check the user token
 * This examines whether an authentication token is present and returns true if
 * it is present and is valid. The user gets logged in so with the current
 * session code of Elgg, that user will be logged out of all other sessions.
 *
 * @return bool
 *
 * @internal
 */
function elgg_ws_pam_auth_usertoken() {
	$token = get_input('auth_token');
	if (!$token) {
		return false;
	}
	
	$validated_userid = _elgg_services()->usersApiSessionsTable->validateToken($token);
	if (empty($validated_userid)) {
		return false;
	}
	
	$user = get_user($validated_userid);
	// Not an elgg user
	if (!$user instanceof ElggUser) {
		return false;
	}
	
	// User is banned
	if ($user->isBanned()) {
		return false;
	}
	
	// Fail if we couldn't log the user in
	if (!login($user)) {
		return false;
	}
	
	return true;
}

/**
 * PAM: Confirm that the call includes a valid API key
 *
 * @return bool true if good API key - otherwise throws exception
 *
 * @throws APIException
 * @since 1.7.0
 * @internal
 */
function elgg_ws_pam_auth_api_key() {
	// check that an API key is present
	$api_key = get_input('api_key');
	if ($api_key == "") {
		throw new APIException(elgg_echo('APIException:MissingAPIKey'));
	}
	
	// check that it is active
	$api_user = _elgg_services()->apiUsersTable->getApiUser($api_key);
	if (!$api_user) {
		// key is not active or does not exist
		throw new APIException(elgg_echo('APIException:BadAPIKey'));
	}
	
	// can be used for keeping stats
	// plugin can also return false to fail this authentication method
	return elgg_trigger_plugin_hook('api_key', 'use', $api_key, true);
}

/**
 * PAM: Confirm the HMAC signature
 *
 * @return true
 *
 * @throws SecurityException
 * @since 1.7.0
 * @internal
 */
function elgg_ws_pam_auth_api_hmac() {
	// Get api header
	$api_header = elgg_ws_get_and_validate_api_headers();
	
	// Pull API user details
	$api_user = _elgg_services()->apiUsersTable->getApiUser($api_header->api_key);
	
	if (!$api_user) {
		throw new SecurityException(elgg_echo('SecurityException:InvalidAPIKey'),
				ErrorResult::$RESULT_FAIL_APIKEY_INVALID);
	}
	
	// calculate expected HMAC
	$hmac = elgg_ws_calculate_hmac(
			$api_header->hmac_algo,
			$api_header->time,
			$api_header->nonce,
			$api_header->api_key,
			$api_user->secret,
			_elgg_services()->request->server->get('QUERY_STRING', ''),
			$api_header->method === 'POST' ? $api_header->posthash : ''
		);
	
	if ($api_header->hmac !== $hmac) {
		throw new SecurityException("HMAC is invalid.  {$api_header->hmac} != [calc]$hmac");
	}
	
	// Now make sure this is not a replay
	if (elgg_ws_cache_hmac_check_replay($hmac)) {
		throw new SecurityException(elgg_echo('SecurityException:DupePacket'));
	}
	
	// Validate post data
	if ($api_header->method === 'POST') {
		$postdata = elgg_ws_get_post_data();
		$calculated_posthash = elgg_ws_calculate_posthash($postdata, $api_header->posthash_algo);
		
		if ($api_header->posthash !== $calculated_posthash) {
			throw new SecurityException(elgg_echo('SecurityException:InvalidPostHash', [$calculated_posthash, $api_header->posthash]));
		}
	}
	
	return true;
}
