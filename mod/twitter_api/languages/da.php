<?php
return array(
	'twitter_api' => 'Twitter Services',

	'twitter_api:requires_oauth' => 'Twitter Services kræver at OAuth Libraries plugin er aktiveret.',

	'twitter_api:consumer_key' => 'Consumer Key',
	'twitter_api:consumer_secret' => 'Consumer Secret',

	'twitter_api:settings:instructions' => 'Du skal indhente en Consumer Key og Consumer Secret fra <a href="https://dev.twitter.com/apps/new" target="_blank">Twitter</a>. Udfyld den nye app ansøgning. Vælg "Browser" som ansøgningstype og "Read & Write" som adgangstype. Callback url er %stwitter_api/authorize',

	'twitter_api:usersettings:description' => "Link din %s konto med Twitter.",
	'twitter_api:usersettings:request' => "Du skal først <a href=\"%s\">autorisere</a> %s for at få adgang til din Twitter konto.",
	'twitter_api:usersettings:cannot_revoke' => "You cannot unlink you account with Twitter because you haven't provided an email address or password. <a href=\"%s\">Provide them now</a>.",
	'twitter_api:authorize:error' => 'Kunne ikke autorisere Twitter.',
	'twitter_api:authorize:success' => 'Twitter adgang er blevet autoriseret.',

	'twitter_api:usersettings:authorized' => "Du har autoriseret %s til at tilgå din Twitter konto: @%s.",
	'twitter_api:usersettings:revoke' => 'Klik <a href="%s">her</a> for at tilbagekalde adgangstilladelse.',
	'twitter_api:usersettings:site_not_configured' => 'An administrator must first configure Twitter before it can be used.',

	'twitter_api:revoke:success' => 'Twitter adgang er blevet tilbagekaldt.',

	'twitter_api:post_to_twitter' => "Send brugernes wire indlæg til Twitter?",

	'twitter_api:login' => 'Tillad nye brugere at registrere sig med Twitter?',
	'twitter_api:new_users' => 'Tillad nye brugere at tilmelde sig ved hjælp af deres Twitter konto, selvom brugerregistrering er deaktiveret?',
	'twitter_api:login:success' => 'Du er blevet logget ind.',
	'twitter_api:login:error' => 'Kunne ikke logge ind med Twitter.',
	'twitter_api:login:email' => "Du skal indtaste en gyldig e-mail adresse til din nye %s konto.",

	'twitter_api:invalid_page' => 'Invalid page',

	'twitter_api:deprecated_callback_url' => 'Callback URL\'en er ændret for Twitter API til %s.  Bed din administrator om at ændre det.',

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
