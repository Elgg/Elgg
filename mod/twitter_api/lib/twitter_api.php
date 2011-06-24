<?php
/**
 * Common library of functions used by Twitter Services.
 *
 * @package twitter_api
 */

/**
 * Tests if the system admin has enabled Sign-On-With-Twitter
 *
 * @param void
 * @return bool
 */
function twitter_api_allow_sign_on_with_twitter() {
	if (!$consumer_key = elgg_get_plugin_setting('consumer_key', 'twitter_api')) {
		return FALSE;
	}

	if (!$consumer_secret = elgg_get_plugin_setting('consumer_secret', 'twitter_api')) {
		return FALSE;
	}

	return elgg_get_plugin_setting('sign_on', 'twitter_api') == 'yes';
}

/**
 * Forwards the user to twitter to authenticate
 *
 * This includes the login URL as the callback
 */
function twitter_api_forward() {
	// sanity check
	if (!twitter_api_allow_sign_on_with_twitter()) {
		forward();
	}

	$callback = elgg_normalize_url("twitter_api/login");
	$request_link = twitter_api_get_authorize_url($callback);

	forward($request_link, 'twitter_api');
}

/**
 * Log in a user referred from Twitter's OAuth API
 *
 * If the user has already linked their account with Twitter, it is a seamless
 * login. If this is a first time login (or a user from deprecated twitter login
 * plugin), we create a new account (update the account).
 *
 * If a plugin wants to be notified when someone logs in with twitter or a new
 * twitter user signs up, register for the standard login or create user events
 * and check for 'twitter_api' context.
 *
 * The user has to be redirected from Twitter for this to work. It depends on
 * the Twitter OAuth data.
 */
function twitter_api_login() {

	// sanity check
	if (!twitter_api_allow_sign_on_with_twitter()) {
		forward();
	}

	$token = twitter_api_get_access_token(get_input('oauth_verifier'));
	if (!isset($token['oauth_token']) or !isset($token['oauth_token_secret'])) {
		register_error(elgg_echo('twitter_api:login:error'));
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
		'limit' => 0,
	);

	$users = elgg_get_entities_from_plugin_user_settings($options);

	if ($users) {
		if (count($users) == 1 && login($users[0])) {
			system_message(elgg_echo('twitter_api:login:success'));			
		} else {
			register_error(elgg_echo('twitter_api:login:error'));
		}
		
		forward(elgg_get_site_url());
	} else {
		$consumer_key = elgg_get_plugin_setting('consumer_key', 'twitter_api');
		$consumer_secret = elgg_get_plugin_setting('consumer_secret', 'twitter_api');
		$api = new TwitterOAuth($consumer_key, $consumer_secret, $token['oauth_token'], $token['oauth_token_secret']);
		$twitter = $api->get('account/verify_credentials');

		// backward compatibility for deprecated Twitter Login plugin
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
			$user = twitter_api_create_user($twitter);
			$site_name = elgg_get_site_entity()->name;
			system_message(elgg_echo('twitter_api:login:email', array($site_name)));
			$forward = "settings/user/{$user->username}";
		}

		// set twitter services tokens
		elgg_set_plugin_user_setting('twitter_name', $token['screen_name'], $user->guid);
		elgg_set_plugin_user_setting('access_key', $token['oauth_token'], $user->guid);
		elgg_set_plugin_user_setting('access_secret', $token['oauth_token_secret'], $user->guid);

		// pull in Twitter icon
		twitter_api_update_user_avatar($user, $twitter->profile_image_url);

		// login new user
		if (login($user)) {
			system_message(elgg_echo('twitter_api:login:success'));
		} else {
			system_message(elgg_echo('twitter_api:login:error'));
		}

		forward($forward, 'twitter_api');
	}

	// register login error
	register_error(elgg_echo('twitter_api:login:error'));
	forward();
}

/**
 * Create a new user from Twitter information
 * 
 * @param object $twitter Twitter OAuth response
 * @return ElggUser
 */
function twitter_api_create_user($twitter) {
	// check new registration allowed
	if (!twitter_api_allow_new_users_with_twitter()) {
		register_error(elgg_echo('registerdisabled'));
		forward();
	}

	// Elgg-ify Twitter credentials
	$username = $twitter->screen_name;
	while (get_user_by_username($username)) {
		// @todo I guess we just hope this is good enough
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

	return $user;
}

/**
 * Pull in the latest avatar from twitter.
 *
 * @param ElggUser $user
 * @param string   $file_location
 */
function twitter_api_update_user_avatar($user, $file_location) {
	// twitter's images have a few suffixes:
	// _normal
	// _resonably_small
	// _mini
	// the twitter app here returns _normal.  We want standard, so remove the suffix.
	// @todo Should probably check that it's an image file.
	$file_location = str_replace('_normal.jpg', '.jpg', $file_location);

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
	
	// update user's icontime
	$user->icontime = time();
}

/**
 * User-initiated Twitter authorization
 *
 * Callback action from Twitter registration. Registers a single Elgg user with
 * the authorization tokens. Will revoke access from previous users when a
 * conflict exists.
 *
 * Depends upon {@link twitter_api_get_authorize_url} being called previously
 * to establish session request tokens.
 */
function twitter_api_authorize() {
	$token = twitter_api_get_access_token();
	if (!isset($token['oauth_token']) || !isset($token['oauth_token_secret'])) {
		register_error(elgg_echo('twitter_api:authorize:error'));
		forward('settings/plugins', 'twitter_api');
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
	
	// trigger authorization hook
	elgg_trigger_plugin_hook('authorize', 'twitter_api', array('token' => $token));

	system_message(elgg_echo('twitter_api:authorize:success'));
	forward('settings/plugins', 'twitter_api');
}

/**
 * Remove twitter access for the currently logged in user.
 */
function twitter_api_revoke() {
	// unregister user's access tokens
	elgg_unset_plugin_user_setting('twitter_name');
	elgg_unset_plugin_user_setting('access_key');
	elgg_unset_plugin_user_setting('access_secret');

	system_message(elgg_echo('twitter_api:revoke:success'));
	forward('settings/plugins', 'twitter_api');
}

/**
 * Gets the url to authorize a user.
 *
 * @param string $callback The callback URL
 */
function twitter_api_get_authorize_url($callback = NULL, $login = true) {
	global $SESSION;

	$consumer_key = elgg_get_plugin_setting('consumer_key', 'twitter_api');
	$consumer_secret = elgg_get_plugin_setting('consumer_secret', 'twitter_api');

	// request tokens from Twitter
	$twitter = new TwitterOAuth($consumer_key, $consumer_secret);
	$token = $twitter->getRequestToken($callback);

	// save token in session for use after authorization
	$SESSION['twitter_api'] = array(
		'oauth_token' => $token['oauth_token'],
		'oauth_token_secret' => $token['oauth_token_secret'],
	);

	return $twitter->getAuthorizeURL($token['oauth_token'], $login);
}

/**
 * Returns the access token to use in twitter calls.
 *
 * @param unknown_type $oauth_verifier
 */
function twitter_api_get_access_token($oauth_verifier = FALSE) {
	global $SESSION;

	$consumer_key = elgg_get_plugin_setting('consumer_key', 'twitter_api');
	$consumer_secret = elgg_get_plugin_setting('consumer_secret', 'twitter_api');

	// retrieve stored tokens
	$oauth_token = $SESSION['twitter_api']['oauth_token'];
	$oauth_token_secret = $SESSION['twitter_api']['oauth_token_secret'];
	$SESSION->offsetUnset('twitter_api');

	// fetch an access token
	$api = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
	return $api->getAccessToken($oauth_verifier);
}

/**
 * Checks if this site is accepting new users.
 * Admins can disable manual registration, but some might want to allow
 * twitter-only logins.
 */
function twitter_api_allow_new_users_with_twitter() {
	$site_reg = elgg_get_config('allow_registration');
	$twitter_reg = elgg_get_plugin_setting('new_users');

	if ($site_reg || (!$site_reg && $twitter_reg == 'yes')) {
		return true;
	}

	return false;
}