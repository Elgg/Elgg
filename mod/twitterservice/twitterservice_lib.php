<?php
/**
 * Common library of functions used by Twitter Services.
 *
 * @package TwitterService
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright Curverider Ltd 2008-2010
 */

/**
 * Tests if the system admin has enabled Sign-On-With-Twitter
 *
 * @param void
 * @return bool
 */
function twitterservice_allow_sign_on_with_twitter() {
	if (!$consumer_key = elgg_get_plugin_setting('consumer_key', 'twitterservice')) {
		return FALSE;
	}

	if (!$consumer_secret = elgg_get_plugin_setting('consumer_secret', 'twitterservice')) {
		return FALSE;
	}

	return elgg_get_plugin_setting('sign_on', 'twitterservice') == 'yes';
}

/**
 * Forwards
 *
 * @todo what is this?
 */
function twitterservice_forward() {
	// sanity check
	if (!twitterservice_allow_sign_on_with_twitter()) {
		forward();
	}

	$callback = elgg_normalize_url("pg/twitterservice/login");
	$request_link = twitterservice_get_authorize_url($callback);

	forward($request_link, 'twitterservice');
}

/**
 * Log in a user with twitter.
 */
function twitterservice_login() {

	// sanity check
	if (!twitterservice_allow_sign_on_with_twitter()) {
		forward();
	}

	$token = twitterservice_get_access_token(get_input('oauth_verifier'));
	if (!isset($token['oauth_token']) or !isset($token['oauth_token_secret'])) {
		register_error(elgg_echo('twitterservice:login:error'));
		forward();
	}

	// attempt to find user and log them in.
	// else, create a new user.
	$options = array(
		'type' => 'user',
		'plugin_user_setting_name_value_pairs' => array(
			'access_key' => $token['oauth_token'],
			'access_secret' => $token['oauth_token_secret'],
		),
		'limit' => 0
	);

	$users = elgg_get_entities_from_plugin_user_settings($options);

	if ($users) {
		if (count($users) == 1 && login($users[0])) {
			system_message(elgg_echo('twitterservice:login:success'));
		} else {
			system_message(elgg_echo('twitterservice:login:error'));
		}

		forward();
	} else {
		// need Twitter account credentials
		$consumer_key = elgg_get_plugin_setting('consumer_key', 'twitterservice');
		$consumer_secret = elgg_get_plugin_setting('consumer_secret', 'twitterservice');
		$api = new TwitterOAuth($consumer_key, $consumer_secret, $token['oauth_token'], $token['oauth_token_secret']);
		$twitter = $api->get('account/verify_credentials');

		// backward compatibility for stalled-development Twitter Login plugin
		$user = FALSE;
		if ($twitter_user = get_user_by_username($token['screen_name'])) {
			if (($screen_name = $twitter_user->twitter_screen_name) && ($screen_name == $token['screen_name'])) {
				// convert existing account
				$user = $twitter_user;
				$forward = '';
			}
		}

		// create new user
		if (!$user) {
			// check new registration allowed
			if (!twitterservice_allow_new_users_with_twitter()) {
				register_error(elgg_echo('registerdisabled'));
				forward();
			}

			// trigger a hook for plugin authors to intercept
			if (!elgg_trigger_plugin_hook('new_twitter_user', 'twitter_service', array('account' => $twitter), TRUE)) {
				// halt execution
				register_error(elgg_echo('twitterservice:login:error'));
				forward();
			}

			// Elgg-ify Twitter credentials
			$username = $twitter->screen_name;
			while (get_user_by_username($username)) {
				$username = $twitter->screen_name . '_' . rand(1000, 9999);
			}

			$password = generate_random_cleartext_password();
			$name = $twitter->name;

			$user = new ElggUser();
			$user->username = $username;
			$user->name = $name;
			$user->access_id = ACCESS_PUBLIC;
			$user->salt = generate_random_cleartext_password();
			$user->password = generate_user_password($user, $password);
			$user->owner_guid = 0;
			$user->container_guid = 0;

			if (!$user->save()) {
				register_error(elgg_echo('registerbad'));
				forward();
			}

			// @todo require email address?

			$site_name = elgg_get_site_entity()->name;
			system_message(elgg_echo('twitterservice:login:email', array($site_name)));

			$forward = "pg/settings/user/{$user->username}";
		}

		// set twitter services tokens
		elgg_set_plugin_user_setting('twitter_name', $token['screen_name'], $user->guid);
		elgg_set_plugin_user_setting('access_key', $token['oauth_token'], $user->guid);
		elgg_set_plugin_user_setting('access_secret', $token['oauth_token_secret'], $user->guid);

		// pull in Twitter icon
		twitterservice_update_user_avatar($user, $twitter->profile_image_url);

		// login new user
		if (login($user)) {
			system_message(elgg_echo('twitterservice:login:success'));
		} else {
			system_message(elgg_echo('twitterservice:login:error'));
		}

		forward($forward, 'twitterservice');
	}

	// register login error
	register_error(elgg_echo('twitterservice:login:error'));
	forward();
}

/**
 * Pull in the latest avatar from twitter.
 *
 * @param unknown_type $user
 * @param unknown_type $file_location
 */
function twitterservice_update_user_avatar($user, $file_location) {
	$sizes = array(
		'topbar' => array(16, 16, TRUE),
		'tiny' => array(25, 25, TRUE),
		'small' => array(40, 40, TRUE),
		'medium' => array(100, 100, TRUE),
		'large' => array(200, 200, FALSE),
		'master' => array(550, 550, FALSE),
	);

	$filehandler = new ElggFile();
	$filehandler->owner_guid = $user->getGUID();
	foreach ($sizes as $size => $dimensions) {
		$image = get_resized_image_from_existing_file(
			$file_location,
			$dimensions[0],
			$dimensions[1],
			$dimensions[2]
		);

		$filehandler->setFilename("profile/$user->guid$size.jpg");
		$filehandler->open('write');
		$filehandler->write($image);
		$filehandler->close();
	}

	return TRUE;
}

/**
 * User-initiated Twitter authorization
 *
 * Callback action from Twitter registration. Registers a single Elgg user with
 * the authorization tokens. Will revoke access from previous users when a
 * conflict exists.
 *
 * Depends upon {@link twitterservice_get_authorize_url} being called previously
 * to establish session request tokens.
 */
function twitterservice_authorize() {
	$token = twitterservice_get_access_token();
	if (!isset($token['oauth_token']) || !isset($token['oauth_token_secret'])) {
		register_error(elgg_echo('twitterservice:authorize:error'));
		forward('pg/settings/plugins', 'twitterservice');
	}

	// make sure no other users are registered to this twitter account.
	$options = array(
		'type' => 'user',
		'plugin_user_setting_name_value_pairs' => array(
			'access_key' => $token['oauth_token'],
			'access_secret' => $token['oauth_token_secret'],
		),
		'limit' => 0
	);

	$users = elgg_get_entities_from_plugin_user_settings($options);

	if ($users) {
		foreach ($users as $user) {
			// revoke access
			elgg_unset_plugin_user_setting('twitter_name', $user->getGUID());
			elgg_unset_plugin_user_setting('access_key', $user->getGUID());
			elgg_unset_plugin_user_setting('access_secret', $user->getGUID());
		}
	}

	// register user's access tokens
	elgg_set_plugin_user_setting('twitter_name', $token['screen_name']);
	elgg_set_plugin_user_setting('access_key', $token['oauth_token']);
	elgg_set_plugin_user_setting('access_secret', $token['oauth_token_secret']);

	system_message(elgg_echo('twitterservice:authorize:success'));
	forward('pg/settings/plugins', 'twitterservice');
}

/**
 * Remove twitter access for the currently logged in user.
 */
function twitterservice_revoke() {
	// unregister user's access tokens
	elgg_unset_plugin_user_setting('twitter_name');
	elgg_unset_plugin_user_setting('access_key');
	elgg_unset_plugin_user_setting('access_secret');

	system_message(elgg_echo('twitterservice:revoke:success'));
	forward('pg/settings/plugins', 'twitterservice');
}

/**
 * Returns the url to authorize a user.
 *
 * @param string $callback The callback URL?
 */
function twitterservice_get_authorize_url($callback = NULL) {
	global $SESSION;

	$consumer_key = elgg_get_plugin_setting('consumer_key', 'twitterservice');
	$consumer_secret = elgg_get_plugin_setting('consumer_secret', 'twitterservice');

	// request tokens from Twitter
	$twitter = new TwitterOAuth($consumer_key, $consumer_secret);
	$token = $twitter->getRequestToken($callback);

	// save token in session for use after authorization
	$SESSION['twitterservice'] = array(
		'oauth_token' => $token['oauth_token'],
		'oauth_token_secret' => $token['oauth_token_secret'],
	);

	return $twitter->getAuthorizeURL($token['oauth_token']);
}

/**
 * Returns the access token to use in twitter calls.
 *
 * @param unknown_type $oauth_verifier
 */
function twitterservice_get_access_token($oauth_verifier = FALSE) {
	global $SESSION;

	$consumer_key = elgg_get_plugin_setting('consumer_key', 'twitterservice');
	$consumer_secret = elgg_get_plugin_setting('consumer_secret', 'twitterservice');

	// retrieve stored tokens
	$oauth_token = $SESSION['twitterservice']['oauth_token'];
	$oauth_token_secret = $SESSION['twitterservice']['oauth_token_secret'];
	$SESSION->offsetUnset('twitterservice');

	// fetch an access token
	$api = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
	return $api->getAccessToken($oauth_verifier);
}

/**
 * Checks if this site is accepting new users.
 * Admins can disable manual registration, but some might want to allow
 * twitter-only logins.
 */
function twitterservice_allow_new_users_with_twitter() {
	$site_reg = elgg_get_config('allow_registration');
	$twitter_reg = elgg_get_plugin_setting('new_users');

	if ($site_reg || (!$site_reg && $twitter_reg == 'yes')) {
		return true;
	}

	return false;
}
