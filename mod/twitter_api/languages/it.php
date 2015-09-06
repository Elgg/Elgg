<?php
return array(
	'twitter_api' => 'Sevizi Twitter',

	'twitter_api:requires_oauth' => 'Per essere attivati i servizi Twitter richiedono la libreria per il plugin OAuth.',

	'twitter_api:consumer_key' => 'Consumer Key',
	'twitter_api:consumer_secret' => 'Consumer Secret',

	'twitter_api:settings:instructions' => 'You must obtain a consumer key and secret from <a rel="nofollow" href="https://dev.twitter.com/apps/new" target="_blank">Twitter</a>. Fill out the new app application. Select "Browser" as the application type and "Read & Write" for the access type. The callback url is %stwitter_api/authorize',

	'twitter_api:usersettings:description' => "Colelgamento al tuo %s account su Twitter.",
	'twitter_api:usersettings:request' => "Dovresti <a href=\"%s\">autorizzare</a> %s l'accesso al tuo account Twitter.",
	'twitter_api:usersettings:cannot_revoke' => "Non puoi disconnetter il tuo account da Twitter perché non hai fornito un indirizzo email o password. <a href=\"%s\">Forniscili ora</a>.",
	'twitter_api:authorize:error' => 'Impossibile autorizzare Twitter.',
	'twitter_api:authorize:success' => 'L\'accesso a Twitter è stato autorizzato.',

	'twitter_api:usersettings:authorized' => "hai autorizzato %s ad accedere al tuo account Twitter: @%s",
	'twitter_api:usersettings:revoke' => 'clicca <a href="%s">qui</a> per revocare l\'accesso.',
	'twitter_api:usersettings:site_not_configured' => 'Un amministratore deve configurare Twitter prima dell\'uso.',

	'twitter_api:revoke:success' => 'L\'accesso a Twitter è stato revocato.',

	'twitter_api:post_to_twitter' => "Send users' wire posts to Twitter?",

	'twitter_api:login' => 'Vuoi permettere agli utenti esistenti che hanno connesso il loro account Twitter ad entrare tramite Twitter',
	'twitter_api:new_users' => 'Allow new users to sign up using their Twitter account even if user registration is disabled?',
	'twitter_api:login:success' => 'Benvenuto/a su  .',
	'twitter_api:login:error' => 'Impossibile fare login con Twitter.',
	'twitter_api:login:email' => "Devi inserire un indirizzo e-mail valido per il tuo nuovo account %s .",

	'twitter_api:invalid_page' => 'pagina non valida',

	'twitter_api:deprecated_callback_url' => 'The callback URL has changed for Twitter API to %s.  Please ask your administrator to change it.',

	'twitter_api:interstitial:settings' => 'Configura i tuoi settings',
	'twitter_api:interstitial:description' => 'Sei quasi pronto ad usare %s! abbiamo bisogno di alcuni ulteriori dettagli prima di continuare. Sono opzionali, ma ti permetteranno di fare login se Twitter avesse problemi o se decidi di scollegare i tuoi account.',

	'twitter_api:interstitial:username' => 'Questo è il tuo username, che non potrà essere cambiato. Se imposti una password, per fare login puoi utilizzare gli username o indirizzo e-mail.',

	'twitter_api:interstitial:name' => 'questo è il nome che la gente vedrà quando dovrà interagire con te.',

	'twitter_api:interstitial:email' => 'Tuo indirizzo e-mail. Gli utenti non potranno vederlo di default.',

	'twitter_api:interstitial:password' => 'Una password per entrare se Twitter non funziona o decidi di collegare i tuoi account.',
	'twitter_api:interstitial:password2' => 'Ripeti la password.',

	'twitter_api:interstitial:no_thanks' => 'No grazie',

	'twitter_api:interstitial:no_display_name' => 'Devi avere un nome utente.',
	'twitter_api:interstitial:invalid_email' => 'Devi inserire un indirizzo e-mail valido o niente.',
	'twitter_api:interstitial:existing_email' => 'Questo indirizzo e-mail è già registrato in questo sito.',
	'twitter_api:interstitial:password_mismatch' => 'Le tue password non coincidono.',
	'twitter_api:interstitial:cannot_save' => 'Impossibile salvare i dettagli dell\'account.',
	'twitter_api:interstitial:saved' => 'Dettagli account salvati!',
);
