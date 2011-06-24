<?php
/**
 * An english language definition file
 */

$english = array(
	'twitter_api' => 'Twitter Services',

	'twitter_api:requires_oauth' => 'Twitter Services requires the OAuth Libraries plugin to be enabled.',

	'twitter_api:consumer_key' => 'Consumer Key',
	'twitter_api:consumer_secret' => 'Consumer Secret',

	'twitter_api:settings:instructions' => 'You must obtain a consumer key and secret from <a href="https://dev.twitter.com/apps/new" target="_blank">Twitter</a>. Fill out the new app application. Select "Browser" as the application type and "Read & Write" for the access type. The callback url is %stwitter_api/authorize',

	'twitter_api:usersettings:description' => "Link your %s account with Twitter.",
	'twitter_api:usersettings:request' => "You must first <a href=\"%s\">authorize</a> %s to access your Twitter account.",
	'twitter_api:authorize:error' => 'Unable to authorize Twitter.',
	'twitter_api:authorize:success' => 'Twitter access has been authorized.',

	'twitter_api:usersettings:authorized' => "You have authorized %s to access your Twitter account: @%s.",
	'twitter_api:usersettings:revoke' => 'Click <a href="%s">here</a> to revoke access.',
	'twitter_api:revoke:success' => 'Twitter access has been revoked.',

	'twitter_api:login' => 'Allow existing users who have connected their Twitter account to sign in with Twitter?',
	'twitter_api:new_users' => 'Allow new users to sign up using their Twitter account even if user registration is disabled?',
	'twitter_api:login:success' => 'You have been logged in.',
	'twitter_api:login:error' => 'Unable to login with Twitter.',
	'twitter_api:login:email' => "You must enter a valid email address for your new %s account.",

	'twitter_api:deprecated_callback_url' => 'The callback URL has changed for Twitter API to %s.  Please ask your administrator to change it.',
);

add_translation('en', $english);
