<?php
/**
 * 
 */

$user_id = get_loggedin_userid();
$twitter_name = get_plugin_usersetting('twitter_name', $user_id, 'twitterservice');
$access_key = get_plugin_usersetting('access_key', $user_id, 'twitterservice');
$access_secret = get_plugin_usersetting('access_secret', $user_id, 'twitterservice');

echo '<p>' . elgg_echo('twitterservice:usersettings:description') . '</p>';

if (!$access_key || !$access_secret) {
	// send user off to validate account
	$request_link = twitterservice_get_authorize_url();
	echo '<p>' . sprintf(elgg_echo('twitterservice:usersettings:request'), $request_link) . '</p>';
} else {
	$url = elgg_get_site_url() . "pg/twitterservice/revoke";
	echo '<p class="twitter_anywhere">' . sprintf(elgg_echo('twitterservice:usersettings:authorized'), $twitter_name) . '</p>';
	echo '<p>' . sprintf(elgg_echo('twitterservice:usersettings:revoke'), $url) . '</p>';
}
