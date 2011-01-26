<?php
/**
 * Elgg Twitter Service
 * This service plugin allows users to authenticate their Elgg account with Twitter.
 * 
 * @package TwitterService
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright Curverider Ltd 2008-2010
 */

register_elgg_event_handler('init','system','twitterservice_init');
function twitterservice_init() {
	global $CONFIG;
	$notice_id = 'twitter_services_disable';
	
	if (!is_plugin_enabled('oauth_lib')) {
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
		require_once "{$CONFIG->pluginspath}twitterservice/vendors/twitteroauth/twitterOAuth.php";
		require_once "{$CONFIG->pluginspath}twitterservice/twitterservice_lib.php";
		
		// extend site views
		elgg_extend_view('metatags', 'twitterservice/metatags');
		elgg_extend_view('css', 'twitterservice/css');
	
		// sign on with twitter
		if (twitterservice_allow_sign_on_with_twitter()) {
			elgg_extend_view('login/extend', 'twitterservice/login');
		}
	
		// register page handler
		register_page_handler('twitterservice', 'twitterservice_pagehandler');
	
		// register Walled Garden public pages
		register_plugin_hook('public_pages', 'walled_garden', 'twitterservice_public_pages');
	
		// allow plugin authors to hook into this service
		register_plugin_hook('tweet', 'twitter_service', 'twitterservice_tweet');
	}
}

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

function twitterservice_tweet($hook, $entity_type, $returnvalue, $params) {
	static $plugins;
	if (!$plugins) {
		$plugins = trigger_plugin_hook('plugin_list', 'twitter_service', NULL, array());
	}
	
	// ensure valid plugin
	if (!in_array($params['plugin'], $plugins)) {
		return NULL;
	}
	
	// check admin settings
	$consumer_key = get_plugin_setting('consumer_key', 'twitterservice');
	$consumer_secret = get_plugin_setting('consumer_secret', 'twitterservice');
	if (!($consumer_key && $consumer_secret)) {
		return NULL;
	}
	
	// check user settings
	$user_id = get_loggedin_userid();
	$access_key = get_plugin_usersetting('access_key', $user_id, 'twitterservice');
	$access_secret = get_plugin_usersetting('access_secret', $user_id, 'twitterservice');
	if (!($access_key && $access_secret)) {
		return NULL;
	}
	
	// send tweet
	$api = new TwitterOAuth($consumer_key, $consumer_secret, $access_key, $access_secret);
	$response = $api->post('statuses/update', array('status' => $params['message']));
	
	return TRUE;
}

function twitterservice_fetch_tweets($user_id, $options=array()) {
	// check admin settings
	$consumer_key = get_plugin_setting('consumer_key', 'twitterservice');
	$consumer_secret = get_plugin_setting('consumer_secret', 'twitterservice');
	if (!($consumer_key && $consumer_secret)) {
		return FALSE;
	}
	
	// check user settings
	$access_key = get_plugin_usersetting('access_key', $user_id, 'twitterservice');
	$access_secret = get_plugin_usersetting('access_secret', $user_id, 'twitterservice');
	if (!($access_key && $access_secret)) {
		return FALSE;
	}
	
	// fetch tweets
	$api = new TwitterOAuth($consumer_key, $consumer_secret, $access_key, $access_secret);
	return $api->get('statuses/user_timeline', $options);
}

function twitterservice_public_pages($hook, $type, $return_value, $params) {
	$return_value[] = 'pg/twitterservice/forward';
	$return_value[] = 'pg/twitterservice/login';
	
	return $return_value;
}
