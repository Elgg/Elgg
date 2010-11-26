<?php
/**
 * Common library of functions used by Twitter Services.
 *
 * @package TwitterService
 */


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
	$token = twitterservice_get_access_token(get_input('oauth_verifier', NULL));
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
			set_plugin_usersetting('twitter_name', NULL, $user->getGUID());
			set_plugin_usersetting('access_key', NULL, $user->getGUID());
			set_plugin_usersetting('access_secret', NULL, $user->getGUID());
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
	set_plugin_usersetting('twitter_name', NULL);
	set_plugin_usersetting('access_key', NULL);
	set_plugin_usersetting('access_secret', NULL);

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

function twitterservice_get_access_token($oauth_verifier=NULL) {
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

/**
 * Returns a list of plugins registered to tweet.
 *
 * @param array $recache
 */
function twitterservice_get_tweeting_plugins($recache = FALSE) {
	static $plugins;

	if (!$plugins || $recache) {
		$plugins = trigger_plugin_hook('plugin_list', 'twitter_service', NULL, array());
	}

	return $plugins;
}

/**
 * Can a plugin tweet for $user_guid.
 *
 * @param $plugin
 * @param $user_guid
 * return bool
 */
function twitterservice_can_tweet($plugin, $user_guid = NULL) {
	if ($user_guid === NULL) {
		$user_guid = get_loggedin_userid();
	}

	$name = "allowed_plugin:$plugin";
	return (bool) get_plugin_usersetting($name, $user_id, 'twitterservice');
}