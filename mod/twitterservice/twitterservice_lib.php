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
	if (!$consumer_key = get_plugin_setting('consumer_key', 'twitterservice')) {
		return FALSE;
	}
	
	if (!$consumer_secret = get_plugin_setting('consumer_secret', 'twitterservice')) {
		return FALSE;
	}
	
	return get_plugin_setting('sign_on', 'twitterservice') == 'yes';
}

function twitterservice_forward() {
	
	// sanity check
	if (!twitterservice_allow_sign_on_with_twitter()) {
		forward();
	}
	
	$callback = elgg_normalize_url("pg/twitterservice/login");
	$request_link = twitterservice_get_authorize_url($callback);
	
	forward($request_link);
}

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
	
	// attempt to find user
	$values = array(
		'plugin:settings:twitterservice:access_key' => $token['oauth_token'],
		'plugin:settings:twitterservice:access_secret' => $token['oauth_token_secret'],
	);
	
	if (!$users = get_entities_from_private_setting_multi($values, 'user', '', 0, '', 0)) {
		// need Twitter account credentials
		$consumer_key = get_plugin_setting('consumer_key', 'twitterservice');
		$consumer_secret = get_plugin_setting('consumer_secret', 'twitterservice');
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
			if (!elgg_get_config('allow_registration')) {
				register_error(elgg_echo('registerdisabled'));
				forward();
			}
			
			// trigger a hook for plugin authors to intercept
			if (!trigger_plugin_hook('new_twitter_user', 'twitter_service', array('account' => $twitter), TRUE)) {
				// halt execution
				register_error(elgg_echo('twitterservice:login:error'));
				forward();
			}
			
			// Elgg-ify Twitter credentials
			$username = "{$twitter->screen_name}_twitter";
			$display_name = $twitter->name;
			$password = generate_random_cleartext_password();
		
			// @hack Temporary, junk email account to allow user creation
			$email = "$username@elgg.com";
		
			try {
				// create new account
				if (!$user_id = register_user($username, $password, $display_name, $email)) {
					register_error(elgg_echo('registerbad'));
					forward();
				}
			} catch (RegistrationException $r) {
				register_error($r->getMessage());
				forward();
			}
		
			$user = new ElggUser($user_id);
			
			// @hack Remove temporary email and forward to user settings page
			// @todo Consider using a view to force valid email
			system_message(elgg_echo('twitterservice:login:email'));
			$user->email = '';
			$user->save();
			
			$forward = "pg/settings/user/{$user->username}";
		}
		
		// set twitter services tokens
		set_plugin_usersetting('twitter_name', $token['screen_name'], $user->guid);
		set_plugin_usersetting('access_key', $token['oauth_token'], $user->guid);
		set_plugin_usersetting('access_secret', $token['oauth_token_secret'], $user->guid);
		
		// pull in Twitter icon
		twitterservice_update_user_avatar($user, $twitter->profile_image_url);
		
		// login new user
		if (login($user)) {
			system_message(elgg_echo('twitterservice:login:success'));
		} else {
			system_message(elgg_echo('twitterservice:login:error'));
		}
		
		forward($forward);
	} elseif (count($users) == 1) {
		if (login($users[0])) {
			system_message(elgg_echo('twitterservice:login:success'));
		} else {
			system_message(elgg_echo('twitterservice:login:error'));
		}
		
		forward();
	}
	
	// register login error
	register_error(elgg_echo('twitterservice:login:error'));
	forward();
}

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

		$filehandler->setFilename("profile/$user->username$size.jpg");
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
		forward('pg/settings/plugins');
	}
	
	// only one user per tokens
	$values = array(
		'plugin:settings:twitterservice:access_key' => $token['oauth_token'],
		'plugin:settings:twitterservice:access_secret' => $token['oauth_token_secret'],
	);
	
	if ($users = get_entities_from_private_setting_multi($values, 'user', '', 0, '', 0)) {
		foreach ($users as $user) {
			// revoke access
			clear_plugin_usersetting('twitter_name', $user->getGUID());
			clear_plugin_usersetting('access_key', $user->getGUID());
			clear_plugin_usersetting('access_secret', $user->getGUID());
		}
	}
	
	// register user's access tokens
	set_plugin_usersetting('twitter_name', $token['screen_name']);
	set_plugin_usersetting('access_key', $token['oauth_token']);
	set_plugin_usersetting('access_secret', $token['oauth_token_secret']);
	
	system_message(elgg_echo('twitterservice:authorize:success'));
	forward('pg/settings/plugins');
}

function twitterservice_revoke() {
	// unregister user's access tokens
	clear_plugin_usersetting('twitter_name');
	clear_plugin_usersetting('access_key');
	clear_plugin_usersetting('access_secret');
	
	system_message(elgg_echo('twitterservice:revoke:success'));
	forward('pg/settings/plugins');
}

function twitterservice_get_authorize_url($callback=NULL) {
	global $SESSION;
	
	$consumer_key = get_plugin_setting('consumer_key', 'twitterservice');
	$consumer_secret = get_plugin_setting('consumer_secret', 'twitterservice');
	
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

function twitterservice_get_access_token($oauth_verifier=FALSE) {
	global $SESSION;
	
	$consumer_key = get_plugin_setting('consumer_key', 'twitterservice');
	$consumer_secret = get_plugin_setting('consumer_secret', 'twitterservice');
	
	// retrieve stored tokens
	$oauth_token = $SESSION['twitterservice']['oauth_token'];
	$oauth_token_secret = $SESSION['twitterservice']['oauth_token_secret'];
	$SESSION->offsetUnset('twitterservice');
	
	// fetch an access token
	$api = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
	return $api->getAccessToken($oauth_verifier);
}
