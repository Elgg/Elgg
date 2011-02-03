<?php
/**
 * 
 */

$user_id = get_loggedin_userid();
$twitter_name = get_plugin_usersetting('twitter_name', $user_id, 'twitterservice');
$access_key = get_plugin_usersetting('access_key', $user_id, 'twitterservice');
$access_secret = get_plugin_usersetting('access_secret', $user_id, 'twitterservice');

$site_name = elgg_get_site_entity()->name;
echo '<p>' . elgg_echo('twitterservice:usersettings:description', array($site_name)) . '</p>';

if (!$access_key || !$access_secret) {
	// send user off to validate account
	$request_link = twitterservice_get_authorize_url();
	echo '<p>' . elgg_echo('twitterservice:usersettings:request', array($request_link, $site_name)) . '</p>';
} else {
	$url = elgg_get_site_url() . "pg/twitterservice/revoke";
	echo '<p class="twitter_anywhere">' . elgg_echo('twitterservice:usersettings:authorized', array($site_name, $twitter_name)) . '</p>';
	echo '<p>' . sprintf(elgg_echo('twitterservice:usersettings:revoke'), $url) . '</p>';
}
