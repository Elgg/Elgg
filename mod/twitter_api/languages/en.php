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
	'twitter_api:usersettings:cannot_revoke' => "You cannot unlink you account with Twitter because you haven't provided an email address or password. <a href=\"%s\">Provide them now</a>.",
	'twitter_api:authorize:error' => 'Unable to authorize Twitter.',
	'twitter_api:authorize:success' => 'Twitter access has been authorized.',

	'twitter_api:usersettings:authorized' => "You have authorized %s to access your Twitter account: @%s.",
	'twitter_api:usersettings:revoke' => 'Click <a href="%s">here</a> to revoke access.',
	'twitter_api:usersettings:site_not_configured' => 'An administrator must first configure Twitter before it can be used.',

	'twitter_api:revoke:success' => 'Twitter access has been revoked.',

	'twitter_api:post_to_twitter' => "Send users' wire posts to Twitter?",

	'twitter_api:login' => 'Allow users to sign in with Twitter?',
	'twitter_api:new_users' => 'Allow new users to sign up using their Twitter account even if user registration is disabled?',
	'twitter_api:login:success' => 'You have been logged in.',
	'twitter_api:login:error' => 'Unable to login with Twitter.',
	'twitter_api:login:email' => "You must enter a valid email address for your new %s account.",

	'twitter_api:invalid_page' => 'Invalid page',

	'twitter_api:deprecated_callback_url' => 'The callback URL has changed for Twitter API to %s.  Please ask your administrator to change it.',

	'twitter_api:interstitial:settings' => 'Configure your settings',
	'twitter_api:interstitial:description' => 'You\'re almost ready to use %s! We need a few more details before you can continue. These are optional, but will allow you login if Twitter goes down or you decide to unlink your accounts.',

	'twitter_api:interstitial:username' => 'This is your username. It cannot be changed. If you set a password, you can use the username or your email address to log in.',

	'twitter_api:interstitial:name' => 'This is the name people will see when interacting with you.',

	'twitter_api:interstitial:email' => 'Your email address. Users cannot see this by default.',

	'twitter_api:interstitial:password' => 'A password to login if Twitter is down or you decide to unlink your accounts.',
	'twitter_api:interstitial:password2' => 'The same password, again.',

	'twitter_api:interstitial:no_thanks' => 'No thanks',

	'twitter_api:interstitial:no_display_name' => 'You must have a display name.',
	'twitter_api:interstitial:invalid_email' => 'You must enter a valid email address or nothing.',
	'twitter_api:interstitial:existing_email' => 'This email address is already registered on this site.',
	'twitter_api:interstitial:password_mismatch' => 'Your passwords do not match.',
	'twitter_api:interstitial:cannot_save' => 'Cannot save account details.',
	'twitter_api:interstitial:saved' => 'Account details saved!',
);

add_translation('en', $english);
