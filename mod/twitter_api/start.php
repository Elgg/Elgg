<?php
/**
 * Elgg Twitter Service
 * This service plugin allows users to authenticate their Elgg account with Twitter.
 *
 * @package TwitterAPI
 */

elgg_register_event_handler('init', 'system', 'twitter_api_init');

function twitter_api_init() {

	// require libraries
	$base = elgg_get_plugins_path() . 'twitter_api';
	elgg_register_class('TwitterOAuth', "$base/vendors/twitteroauth/twitterOAuth.php");
	elgg_register_library('twitter_api', "$base/lib/twitter_api.php");
	elgg_load_library('twitter_api');

	// extend site views
	//elgg_extend_view('metatags', 'twitter_api/metatags');
	elgg_extend_view('css/elgg', 'twitter_api/css');
	elgg_extend_view('css/admin', 'twitter_api/css');
	elgg_extend_view('js/elgg', 'twitter_api/js');

	// sign on with twitter
	if (twitter_api_allow_sign_on_with_twitter()) {
		elgg_extend_view('login/extend', 'twitter_api/login');
	}

	// register page handler
	elgg_register_page_handler('twitter_api', 'twitter_api_pagehandler');
	// backward compatibility
	elgg_register_page_handler('twitterservice', 'twitter_api_pagehandler_deprecated');

	// register Walled Garden public pages
	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'twitter_api_public_pages');

	// push wire post messages to twitter
	if (elgg_get_plugin_setting('wire_posts', 'twitter_api') == 'yes') {
		elgg_register_plugin_hook_handler('status', 'user', 'twitter_api_tweet');
	}

	$actions = dirname(__FILE__) . '/actions/twitter_api';
	elgg_register_action('twitter_api/interstitial_settings', "$actions/interstitial_settings.php", 'logged_in');
}

/**
 * Handles old pg/twitterservice/ handler
 *
 * @param array $page
 * @return bool
 */
function twitter_api_pagehandler_deprecated($page) {
	$url = elgg_get_site_url() . 'pg/twitter_api/authorize';
	$msg = elgg_echo('twitter_api:deprecated_callback_url', array($url));
	register_error($msg);

	return twitter_api_pagehandler($page);
}


/**
 * Serves pages for twitter.
 *
 * @param array $page
 * @return bool
 */
function twitter_api_pagehandler($page) {
	if (!isset($page[0])) {
		return false;
	}

	switch ($page[0]) {
		case 'authorize':
			twitter_api_authorize();
			break;
		case 'revoke':
			twitter_api_revoke();
			break;
		case 'forward':
			twitter_api_forward();
			break;
		case 'login':
			twitter_api_login();
			break;
		case 'interstitial':
			elgg_gatekeeper();
			// only let twitter users do this.
			$guid = elgg_get_logged_in_user_guid();
			$twitter_name = elgg_get_plugin_user_setting('twitter_name', $guid, 'twitter_api');
			if (!$twitter_name) {
				register_error(elgg_echo('twitter_api:invalid_page'));
				forward();
			}
			$pages = dirname(__FILE__) . '/pages/twitter_api';
			include "$pages/interstitial.php";
			break;
		default:
			return false;
	}
	return true;
}

/**
 * Push a status update to twitter.
 *
 * @param string $hook
 * @param string $type
 * @param null   $returnvalue
 * @param array  $params
 */
function twitter_api_tweet($hook, $type, $returnvalue, $params) {

	if (!$params['user'] instanceof ElggUser) {
		return;
	}

	// @todo - allow admin to select origins?

	// check user settings
	$user_guid = $params['user']->getGUID();
	$access_key = elgg_get_plugin_user_setting('access_key', $user_guid, 'twitter_api');
	$access_secret = elgg_get_plugin_user_setting('access_secret', $user_guid, 'twitter_api');
	if (!($access_key && $access_secret)) {
		return;
	}

	$api = twitter_api_get_api_object($access_key, $access_secret);
	if (!$api) {
		return;
	}

	$api->post('statuses/update', array('status' => $params['message']));
}

/**
 * Get tweets for a user.
 *
 * @param int   $user_guid The Elgg user GUID
 * @param array $options
 * @return array
 */
function twitter_api_fetch_tweets($user_guid, $options = array()) {

	// check user settings
	$access_key = elgg_get_plugin_user_setting('access_key', $user_guid, 'twitter_api');
	$access_secret = elgg_get_plugin_user_setting('access_secret', $user_guid, 'twitter_api');
	if (!($access_key && $access_secret)) {
		return FALSE;
	}

	$api = twitter_api_get_api_object($access_key, $access_secret);
	if (!$api) {
		return FALSE;
	}

	return $api->get('statuses/user_timeline', $options);
}

/**
 * Register as public pages for walled garden.
 *
 * @param string $hook
 * @param string $type
 * @param array  $return_value
 * @param array  $params
 * @return array
 */
function twitter_api_public_pages($hook, $type, $return_value, $params) {
	$return_value[] = 'twitter_api/forward';
	$return_value[] = 'twitter_api/login';

	return $return_value;
}
