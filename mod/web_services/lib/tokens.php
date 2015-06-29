<?php
/**
 * Library for managing user tokens
 */

/**
 * Obtain a token for a user
 *
 * @param string $username The username
 * @param int    $expire   Minutes until token expires (default is 60 minutes)
 * @return string|bool
 */
function create_user_token($username, $expire = 60) {
	$user = get_user_by_username($username);
	$site = elgg_get_site_entity();
	if (!$user || !$site) {
		return false;
	}
	$token = (new \Elgg\WebServices\TokenService())->create($user, $site, $expire);
	return ($token) ? $token->token : false;
}

/**
 * Get all tokens attached to a user
 *
 * @param int $user_guid The user GUID
 * @param int $site_guid The ID of the site (default is current site)
 *
 * @return false if none available or array of stdClass objects
 * 		(see users_apisessions schema for available variables in objects)
 * @since 1.7.0
 */
function get_user_tokens($user_guid, $site_guid) {
	$user = get_entity($user_guid);
	$site = get_entity($site_guid);
	if (!$site) {
		$site = elgg_get_site_entity();
	}
	return (new \Elgg\WebServices\TokenService())->all($user, $site);
}

/**
 * Validate a token against a given site.
 *
 * A token registered with one site can not be used from a
 * different apikey(site), so be aware of this during development.
 *
 * @param string $token     The Token.
 * @param int    $site_guid The ID of the site (default is current site)
 *
 * @return mixed The user id attached to the token if not expired or false.
 */
function validate_user_token($token, $site_guid) {
	$token = Elgg\WebServices\UserToken::load($token);
	if (!$token) {
		return false;
	}
	$site = get_entity($site_guid);
	$result = $token->validate($site);
	if ($result instanceof \ElggEntity) {
		return $result->guid;
	}
	return false;
}

/**
 * Remove user token
 *
 * @param string $token     The toekn
 * @param int    $site_guid The ID of the site (default is current site)
 *
 * @return bool
 * @since 1.7.0
 */
function remove_user_token($token, $site_guid) {
	$token = Elgg\WebServices\UserToken::load($token);
	if (!$token) {
		return false;
	}
	return $token->delete();
}

/**
 * Remove expired tokens
 *
 * @return bool
 * @since 1.7.0
 */
function remove_expired_user_tokens() {
	return (new \Elgg\WebServices\TokenService())->removeExpiredTokens();
}

/**
 * The auth.gettoken API.
 * This API call lets a user log in, returning an authentication token which can be used
 * to authenticate a user for a period of time. It is passed in future calls as the parameter
 * auth_token.
 *
 * @param array $values Values received with the API request
 * @return string Token string or exception
 * @throws SecurityException
 * @access private
 */
function auth_gettoken($values) {
	return (new \Elgg\WebServices\TokenService())->exchange($values);
}
