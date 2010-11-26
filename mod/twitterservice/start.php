<?php
/**
 * Elgg Twitter Service
 * This service plugin allows users to authenticate their Elgg account with Twitter.
 *
 * @package TwitterService
 */

register_elgg_event_handler('init','system','twitterservice_init');
function twitterservice_init() {
	global $CONFIG;

	// require libraries
	require_once "{$CONFIG->pluginspath}twitterservice/twitterservice_lib.php";

	if (!class_exists('twitterOAuth')) {
		require_once "{$CONFIG->pluginspath}twitterservice/vendors/twitteroauth/twitterOAuth.php";
	}

	// extend site views
	elgg_extend_view('css', 'twitterservice/css');

	// register page handler
	register_page_handler('twitterservice', 'twitterservice_pagehandler');

	// allow plugin authors to hook into this service
	register_plugin_hook('tweet', 'twitter_service', 'twitterservice_tweet');
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
		default:
			forward();
			break;
	}
}

function twitterservice_tweet($hook, $entity_type, $returnvalue, $params) {
	if (!twitterservice_can_tweet($params['plugin'])) {
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