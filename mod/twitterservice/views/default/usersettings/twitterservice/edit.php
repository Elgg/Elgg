<?php
/**
 *
 */

$user_id = get_loggedin_userid();
$twitter_name = get_plugin_usersetting('twitter_name', $user_id, 'twitterservice');
$access_key = get_plugin_usersetting('access_key', $user_id, 'twitterservice');
$access_secret = get_plugin_usersetting('access_secret', $user_id, 'twitterservice');
$plugins  = twitterservice_get_tweeting_plugins();

echo '<p>' . elgg_echo('twitterservice:usersettings:description') . '</p>';

if (!$access_key || !$access_secret) {
	// send user off to validate account
	$request_link = twitterservice_get_authorize_url($vars['url'] . 'pg/twitterservice/authorize');
	echo '<p>' . sprintf(elgg_echo('twitterservice:usersettings:request'), $request_link) . '</p>';
} else {
	$url = "{$CONFIG->site->url}pg/twitterservice/revoke";
	echo '<p class="twitter_anywhere">' . sprintf(elgg_echo('twitterservice:usersettings:authorized'), $twitter_name, $vars['config']->site->name) . '</p>';
	echo '<p>' . sprintf(elgg_echo('twitterservice:usersettings:revoke'), $url) . '</p>';

	// allow granular plugin access to twitter
	echo '<h3>' . elgg_echo('twitterservice:usersettings:allowed_plugins') . '</h3><br />';

	foreach ($plugins as $plugin => $info) {
		$name = "allowed_plugin:$plugin";
		$checked = (twitterservice_can_tweet($plugin, $user_guid)) ? 'checked = checked' : '';

		// can't use input because it doesn't work correctly for sending a single checkbox.
		echo "<input type=\"hidden\" name=\"params[$name]\" value=\"0\" />
			<label><input type=\"checkbox\" name=\"params[$name]\" value=\"1\" $checked />"
			. elgg_echo($info['name']) . '</label>
			<p class="twitterservice_usersettings_desc">' . $info['description'] . '</p>
			';
	}
}