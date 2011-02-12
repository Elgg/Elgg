<?php
/**
 * An english language definition file
 */

$english = array(
	'twitterservice' => 'Twitter Services',

	'twitterservice:requires_oauth' => 'Twitter Services requires the OAuth Libraries plugin to be enabled.',

	'twitterservice:consumer_key' => 'Consumer Key',
	'twitterservice:consumer_secret' => 'Consumer Secret',

	'twitterservice:settings:instructions' => 'You must obtain a consumer key and secret from <a href="https://twitter.com/oauth_clients" target="_blank">Twitter</a>. Most of the fields are self explanatory, the one piece of data you will need is the callback url which takes the form http://[yoursite]/action/twitterlogin/return - [yoursite] is the url of your Elgg network.',

	'twitterservice:usersettings:description' => "Link your %s account with Twitter.",
	'twitterservice:usersettings:request' => "You must first <a href=\"%s\">authorize</a> %s to access your Twitter account.",
	'twitterservice:authorize:error' => 'Unable to authorize Twitter.',
	'twitterservice:authorize:success' => 'Twitter access has been authorized.',

	'twitterservice:usersettings:authorized' => "You have authorized %s to access your Twitter account: @%s.",
	'twitterservice:usersettings:revoke' => 'Click <a href="%s">here</a> to revoke access.',
	'twitterservice:revoke:success' => 'Twitter access has been revoked.',

	'twitterservice:login' => 'Allow existing users who have connected their Twitter account to sign in with Twitter?',
	'twitterservice:new_users' => 'Allow new users to sign up using their Twitter account even if manual registration is disabled?',
	'twitterservice:login:success' => 'You have been logged in.',
	'twitterservice:login:error' => 'Unable to login with Twitter.',
	'twitterservice:login:email' => "You must enter a valid email address for your new %s account.",
);

add_translation('en', $english);