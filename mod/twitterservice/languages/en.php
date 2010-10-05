<?php
/**
 * An english language definition file
 */

$english = array(
	'twitterservice' => 'Twitter Services',

	'twitterservice:consumer_key' => 'Consumer Key',
	'twitterservice:consumer_secret' => 'Consumer Secret',

	'twitterservice:usersettings:description' => "Link your {$CONFIG->site->name} account with Twitter.",
	'twitterservice:usersettings:request' => "You must first <a href=\"%s\">authorize</a> {$CONFIG->site->name} to access your Twitter account.",
	'twitterservice:authorize:error' => 'Unable to authorize Twitter.',
	'twitterservice:authorize:success' => 'Twitter access has been authorized.',

	'twitterservice:usersettings:authorized' => "You have authorized {$CONFIG->site->name} to access your Twitter account: @%s.  If Tweets aren't showing up, you might need to reauthorize.  Click revoke below, then go to <a href=\"http://twitter.com/settings/connections\">Twitter Connection Settings</a> and revoke access for %s.  Then come back to this page and authorize again.",
	'twitterservice:usersettings:revoke' => 'Click <a href="%s">here</a> to revoke access.',
	'twitterservice:revoke:success' => 'Twitter access has been revoked.',

	'twitterservice:usersettings:allowed_plugins' => 'Allowed Plugins',
);

add_translation('en', $english);
