<?php
return array(
	'twitter_api' => 'Twitter Services',

	'twitter_api:requires_oauth' => 'Twitter Services vereist dat de OAuth libraries-plugin is ingeschakeld.',

	'twitter_api:consumer_key' => 'Consumer Key',
	'twitter_api:consumer_secret' => 'Consumer Secret',

	'twitter_api:settings:instructions' => 'Je moet een Consumer Key en Consumer Secret aanvragen bij <a href="https://dev.twitter.com/apps/new" target="_blank">Twitter</a>. Vul het formulier in voor een nieuwe applicatie. Selecteer "Browser" voor het applicatietype en "Read & Write" voor de rechten. De callback-URL is %stwitter_api/authorize',

	'twitter_api:usersettings:description' => "Koppel je %s account met Twitter.",
	'twitter_api:usersettings:request' => "Je moet eerst %2$s <a href=\"%1$s\">autoriseren</a> om toegang te krijgen tot je Twitter account.",
	'twitter_api:usersettings:cannot_revoke' => "Je kunt je account niet ontkoppelen van Twitter omdat je geen e-mailadres of wachtwoord hebt opgegeven. <a href=\"%s\">Vul dit nu in!</a>",
	'twitter_api:authorize:error' => 'Fout tijdens het autoriseren van Twitter.',
	'twitter_api:authorize:success' => 'Twittertoegang is geautoriseerd.',

	'twitter_api:usersettings:authorized' => "Je hebt %s geautoriseerd om toegang te krijgen tot je Twitteraccount: @%s.",
	'twitter_api:usersettings:revoke' => 'Klik <a href="%s">hier</a> om de toegang in te trekken.',
	'twitter_api:usersettings:site_not_configured' => 'Een beheerder moet Twitter eerst instellen voordat het gebruikt kan worden.',

	'twitter_api:revoke:success' => 'Twittertoegang is ingetrokken.',

	'twitter_api:post_to_twitter' => "Wil je dat jouw Wire-berichten ook op je Twittertijdlijn gepost worden? ",

	'twitter_api:login' => 'Mogen bestaande gebruikers die hun Twitteraccount hebben gekoppeld zich aanmelden met Twitter?',
	'twitter_api:new_users' => 'Mogen nieuwe gebruikers zich registreren met hun Twitteraccount, zelfs als registratie is uitgeschakeld?',
	'twitter_api:login:success' => 'Je bent aangemeld.',
	'twitter_api:login:error' => 'Aanmelden met Twitter mislukt.',
	'twitter_api:login:email' => "Je moet een geldig e-mailadres opgeven voor je nieuwe %s account.",

	'twitter_api:invalid_page' => 'Ongeldige pagina',

	'twitter_api:deprecated_callback_url' => 'De callback-URL voor de Twitter-API van %s is gewijzigd. Vraag de beheerder om dit aan te passen.',

	'twitter_api:interstitial:settings' => 'Configureer je instellingen',
	'twitter_api:interstitial:description' => 'Je bent bijna klaar om gebruik te maken van %s! We hebben echter nog een paar gegevens nodig voordat je verder kunt. Deze zijn optioneel, maar maken het mogelijk dat je je kunt aanmelden als Twitter niet beschikbaar is of als je de koppeling met Twitter verwijdert. ',

	'twitter_api:interstitial:username' => 'Dit is je gebruikersnaam. Deze kan niet worden aangepast. Als je een wachtwoord opgeeft, kun je je aanmelden met deze gebruikersnaam of je e-mailadres.',

	'twitter_api:interstitial:name' => 'Dit is de naam die mensen zien als ze via de site contact met jou hebben.',

	'twitter_api:interstitial:email' => 'Je e-mailadres. Gebruikers kunnen dit standaard <em>niet</em> zien.',

	'twitter_api:interstitial:password' => 'Een wachtwoord om je aan te melden als Twitter niet beschikbaar is of als je accounts niet meer gekoppeld zijn.',
	'twitter_api:interstitial:password2' => 'Hetzelfde wachtwoord, nogmaals',

	'twitter_api:interstitial:no_thanks' => 'Nee, dank je',

	'twitter_api:interstitial:no_display_name' => 'Je moet een weergavenaam hebben.',
	'twitter_api:interstitial:invalid_email' => 'Je moet óf niets, óf een geldig e-mailadres opgeven.',
	'twitter_api:interstitial:existing_email' => 'Dit e-mailadres is al geregistreerd op deze site.',
	'twitter_api:interstitial:password_mismatch' => 'Je wachtwoorden komen niet overeen.',
	'twitter_api:interstitial:cannot_save' => 'De accountdetails kunnen niet worden opgeslagen.',
	'twitter_api:interstitial:saved' => 'Accountdetails opgeslagen!',
);
