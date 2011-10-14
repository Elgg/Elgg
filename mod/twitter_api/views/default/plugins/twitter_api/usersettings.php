<?php
/**
 * User settings for Twitter API
 */

$user = elgg_get_logged_in_user_entity();
$user_guid = $user->getGUID();
$twitter_name = elgg_get_plugin_user_setting('twitter_name', $user_guid, 'twitter_api');
$access_key = elgg_get_plugin_user_setting('access_key', $user_guid, 'twitter_api');
$access_secret = elgg_get_plugin_user_setting('access_secret', $user_guid, 'twitter_api');

$site_key = elgg_get_plugin_setting('consumer_key', 'twitter_api');
$site_secret = elgg_get_plugin_setting('consumer_secret', 'twitter_api');

if (!($site_key && $site_secret)) {
	echo '<div>' . elgg_echo('twitter_api:usersettings:site_not_configured') . '</div>';
	return true;
}

$site_name = elgg_get_site_entity()->name;
echo '<div>' . elgg_echo('twitter_api:usersettings:description', array($site_name)) . '</div>';

if (!$access_key || !$access_secret) {
	// send user off to validate account
	$request_link = twitter_api_get_authorize_url(null, false);
	echo '<div>' . elgg_echo('twitter_api:usersettings:request', array($request_link, $site_name)) . '</div>';
} else {
	// if this user logged in through twitter and never set up an email address, don't
	// let them disassociate their account.
	if ($user->email) {
		$url = elgg_get_site_url() . "twitter_api/revoke";
		echo '<div>' . elgg_echo('twitter_api:usersettings:authorized', array($site_name, $twitter_name)) . '</div>';
		echo '<div>' . sprintf(elgg_echo('twitter_api:usersettings:revoke'), $url) . '</div>';
	} else {
		echo elgg_echo('twitter_api:usersettings:cannot_revoke', array(elgg_normalize_url('twitter_api/interstitial')));
	}
}
