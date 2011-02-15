<?php
/**
 * Elgg Twitter Service
 * This service plugin allows users to authenticate their Elgg account with Twitter.
 *
 * @package TwitterService
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright Curverider Ltd 2008-2010
 */

elgg_register_event_handler('init', 'system', 'twitterservice_init');

function twitterservice_init() {

	$notice_id = 'twitter_services_disable';

	// @todo there's a better way to do this with requires.
	if (!elgg_is_active_plugin('oauth_lib')) {
		// disable the plugin
		disable_plugin('twitterservice');

		// alert the admin
		if (!elgg_admin_notice_exists($notice_id)) {
			elgg_add_admin_notice($notice_id, elgg_echo('twitterservice:requires_oauth'));
		}
	} else {
		// cleanup notices
		elgg_delete_admin_notice($notice_id);

		// require libraries
		$base = elgg_get_plugins_path() . 'twitterservice';
		require_once "$base/vendors/twitteroauth/twitterOAuth.php";
		require_once "$base/twitterservice_lib.php";

		// extend site views
		elgg_extend_view('metatags', 'twitterservice/metatags');
		elgg_extend_view('css', 'twitterservice/css');

		// sign on with twitter
		if (twitterservice_allow_sign_on_with_twitter()) {
			elgg_extend_view('login/extend', 'twitterservice/login');
		}

		// register page handler
		elgg_register_page_handler('twitterservice', 'twitterservice_pagehandler');

		// register Walled Garden public pages
		elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'twitterservice_public_pages');

		// allow plugin authors to hook into this service
		elgg_register_plugin_hook_handler('tweet', 'twitter_service', 'twitterservice_tweet');
	}
}

/**
 * Serves pages for twitter.
 *
 * @param array$page
 */
function twitterservice_pagehandler($page) {
	if (!isset($page[0])) {
		forward();
	}

	switch ($page[0]) {
		case 'authorize':
			twitterservice_authorize();
			break;
		case 'revoke':
			twitterservice_revoke();
			break;
		case 'forward':
			twitterservice_forward();
			break;
		case 'login':
			twitterservice_login();
			break;
		default:
			forward();
			break;
	}
}

/**
 * Push a tweet to twitter.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 */
function twitterservice_tweet($hook, $entity_type, $returnvalue, $params) {
	static $plugins;
	if (!$plugins) {
		$plugins = elgg_trigger_plugin_hook('plugin_list', 'twitter_service', NULL, array());
	}

	// ensure valid plugin
	if (!in_array($params['plugin'], $plugins)) {
		return NULL;
	}

	// check admin settings
	$consumer_key = elgg_get_plugin_setting('consumer_key', 'twitterservice');
	$consumer_secret = elgg_get_plugin_setting('consumer_secret', 'twitterservice');
	if (!($consumer_key && $consumer_secret)) {
		return NULL;
	}

	// check user settings
	$user_id = elgg_get_logged_in_user_guid();
	$access_key = elgg_get_plugin_user_setting('access_key', $user_id, 'twitterservice');
	$access_secret = elgg_get_plugin_user_setting('access_secret', $user_id, 'twitterservice');
	if (!($access_key && $access_secret)) {
		return NULL;
	}

	// send tweet
	$api = new TwitterOAuth($consumer_key, $consumer_secret, $access_key, $access_secret);
	$response = $api->post('statuses/update', array('status' => $params['message']));

	return TRUE;
}

/**
 * Return tweets for a user.
 *
 * @param int $user_id The Elgg user GUID
 * @param array $options
 */
function twitterservice_fetch_tweets($user_guid, $options=array()) {
	// check admin settings
	$consumer_key = elgg_get_plugin_setting('consumer_key', 'twitterservice');
	$consumer_secret = elgg_get_plugin_setting('consumer_secret', 'twitterservice');
	if (!($consumer_key && $consumer_secret)) {
		return FALSE;
	}

	// check user settings
	$access_key = elgg_get_plugin_user_setting('access_key', $user_guid, 'twitterservice');
	$access_secret = elgg_get_plugin_user_setting('access_secret', $user_guid, 'twitterservice');
	if (!($access_key && $access_secret)) {
		return FALSE;
	}

	// fetch tweets
	$api = new TwitterOAuth($consumer_key, $consumer_secret, $access_key, $access_secret);
	return $api->get('statuses/user_timeline', $options);
}

/**
 * Register as public pages for walled garden.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $return_value
 * @param unknown_type $params
 */
function twitterservice_public_pages($hook, $type, $return_value, $params) {
	$return_value[] = 'pg/twitterservice/forward';
	$return_value[] = 'pg/twitterservice/login';

	return $return_value;
}
