<?php
return array(
	'twitter_api' => 'Twitter-Service',

	'twitter_api:requires_oauth' => 'Für die Twitter-Services muss das OAuth-Libraries-Plugin aktiviert sein.',

	'twitter_api:consumer_key' => 'Consumer Key',
	'twitter_api:consumer_secret' => 'Consumer Secret',

	'twitter_api:settings:instructions' => 'Es ist ein Consumer Key und und ein Consumer Secret von <a href="https://dev.twitter.com/apps/new" target="_blank">Twitter</a> notwendig.  Fülle auf Twitter die Angaben für eine neue Applikation aus. Wähle "Browser" als Applikationstyp und "Read & Write" für die Zugangsberechtigung. Die Callback-URL, die Du angeben mußt, ist %stwitter_api/authorize.',

	'twitter_api:usersettings:description' => "Verbinde Deinen %s-Account mit Twitter.",
	'twitter_api:usersettings:request' => "Du mußt zuerst eine <a href=\"%s\">Authorisierung</a> für %s einrichten, damit der Zugriff auf Deinen Twitter-Account möglich ist.",
	'twitter_api:usersettings:cannot_revoke' => "Du kannst die Verbindung Deines Accounts mit Twitter nicht aufheben, da Du keine Email-Addresse oder kein Passwort für Deinen Account angegeben hast. <a href=\"%s\">Gebe diese Angaben jetzt ein</a>.",
	'twitter_api:authorize:error' => 'Twitter-Authorisierung fehlgeschlagen.',
	'twitter_api:authorize:success' => 'Twitter-Zugriff wurde authorisiert.',

	'twitter_api:usersettings:authorized' => "Du hast %s authorisiert, auf Deinen Twitter-Account zuzugreifen: @%s.",
	'twitter_api:usersettings:revoke' => '<a href="%s">HIER</a> klicken, um den Zugriff auf Deinen Twitter-Account zu widerrufen..',
	'twitter_api:usersettings:site_not_configured' => 'Bevor Twitter verwendet werden kann, muss erst ein Administrator die Konfiguration vornehmen.',

	'twitter_api:revoke:success' => 'Twitter-Zugriff wurde widerrufen.',

	'twitter_api:post_to_twitter' => "Nachrichten von Benutzern im Heißen Draht an Twitter senden?",

	'twitter_api:login' => 'Anmeldung mit Twitter-Accountdaten auf Deiner Elgg-Community-Seite erlauben?',
	'twitter_api:new_users' => 'Erlaube neuen Benutzern, sich mit ihren Twitter-Accountdaten anzumelden, selbst wenn die Account-Registrierung auf Deiner Elgg-Community-Seite deaktiviert ist?',
	'twitter_api:login:success' => 'Du bist nun angemeldet.',
	'twitter_api:login:error' => 'Die Anmeldung mit Deinen Twitter-Accountdaten ist fehlgeschlagen.',
	'twitter_api:login:email' => "Du mußt als erstes eine gültige Email-Adresse für Deinen neuen %s-Account eingeben.",

	'twitter_api:invalid_page' => 'Ungültige Seite.',

	'twitter_api:deprecated_callback_url' => 'Die Callback-URL für die Twitter-API hat sich zu %s geändert. Bitte den Administrator dieser Community-Seite, sie zu ändern.',

	'twitter_api:interstitial:settings' => 'Account konfigurieren',
	'twitter_api:interstitial:description' => 'Du kannst Dich in wenigen Augenblicken auf %s anmelden! Wir benötigen nur noch einige wenige Angaben. Diese Angaben sind optional, aber sie ermöglichen es Dir, Dich auf dieser Community-Seite anzumelden, falls Twitter einmal nicht erreichbar ist oder Du Dich entschließen solltest, die Verbindung zu Deinem Twitter-Account zu trennen.',

	'twitter_api:interstitial:username' => 'Dies ist Dein Benutzername. Er kann nicht geändert werden. Wenn Du ein Passwort angibst, kannst Du Deinen Benutzernamen oder Deine Email-Adresse für die Anmeldung auf dieser Community-Seite verwenden.',

	'twitter_api:interstitial:name' => 'Dies ist der Name, den die anderen Mitglieder sehen werden, wenn sie mit Dir interagieren.',

	'twitter_api:interstitial:email' => 'Deine Email-Adresse. Standardmäßig können die anderen Mitglieder der Community-Seite diese nicht sehen.',

	'twitter_api:interstitial:password' => 'Ein Passwort, damit Du Dich auf dieser Community-Seite anmelden kannst, falls Twitter nicht erreichbar ist oder Du Dich entschließen solltest, die Verbindung zu Deinem Twitter-Account zu trennen.',
	'twitter_api:interstitial:password2' => 'Das gleiche Passwort noch einmal.',

	'twitter_api:interstitial:no_thanks' => 'Nein danke.',

	'twitter_api:interstitial:no_display_name' => 'Du mußt einen Namen eingeben.',
	'twitter_api:interstitial:invalid_email' => 'Du mußt entweder eine gültige Email-Adresse oder gar keine eingeben.',
	'twitter_api:interstitial:existing_email' => 'Diese Email-Adresse ist bereits auf dieser Community-Seite registriert.',
	'twitter_api:interstitial:password_mismatch' => 'Die beiden Passwort-Eingaben stimmen nicht überein.',
	'twitter_api:interstitial:cannot_save' => 'Das Speichern der Eingaben ist fehlgeschlagen!',
	'twitter_api:interstitial:saved' => 'Die Eingaben wurden gespeichert.',
);
