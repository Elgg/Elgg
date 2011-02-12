<?php
/**
 * 
 */

$user_id = elgg_get_logged_in_user_guid();
$twitter_name = get_plugin_usersetting('twitter_name', $user_id, 'twitterservice');
$access_key = get_plugin_usersetting('access_key', $user_id, 'twitterservice');
$access_secret = get_plugin_usersetting('access_secret', $user_id, 'twitterservice');

$site_name = elgg_get_site_entity()->name;
echo '<div>' . elgg_echo('twitterservice:usersettings:description', array($site_name)) . '</div>';

if (!$access_key || !$access_secret) {
	// send user off to validate account
	$request_link = twitterservice_get_authorize_url();
	echo '<div>' . elgg_echo('twitterservice:usersettings:request', array($request_link, $site_name)) . '</div>';
} else {
	$url = elgg_get_site_url() . "pg/twitterservice/revoke";
	echo '<div class="twitter_anywhere">' . elgg_echo('twitterservice:usersettings:authorized', array($site_name, $twitter_name)) . '</div>';
	echo '<div>' . sprintf(elgg_echo('twitterservice:usersettings:revoke'), $url) . '</div>';
}
