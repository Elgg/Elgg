<?php
/**
 * User settings for Twitter API
 */

$user_id = elgg_get_logged_in_user_guid();
$twitter_name = get_plugin_usersetting('twitter_name', $user_id, 'twitter_api');
$access_key = get_plugin_usersetting('access_key', $user_id, 'twitter_api');
$access_secret = get_plugin_usersetting('access_secret', $user_id, 'twitter_api');

$site_name = elgg_get_site_entity()->name;
echo '<div>' . elgg_echo('twitter_api:usersettings:description', array($site_name)) . '</div>';

if (!$access_key || !$access_secret) {
	// send user off to validate account
	$request_link = twitter_api_get_authorize_url(null, false);
	echo '<div>' . elgg_echo('twitter_api:usersettings:request', array($request_link, $site_name)) . '</div>';
} else {
	$url = elgg_get_site_url() . "twitter_api/revoke";
	echo '<div class="twitter_anywhere">' . elgg_echo('twitter_api:usersettings:authorized', array($site_name, $twitter_name)) . '</div>';
	echo '<div>' . sprintf(elgg_echo('twitter_api:usersettings:revoke'), $url) . '</div>';
}
