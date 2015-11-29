<?php
return array(
	'twitter_api' => 'Sevizi di Twitter',

	'twitter_api:requires_oauth' => 'I servizi di Twitter richiedono il plugin delle librerie OAuth per essere attivati.',

	'twitter_api:consumer_key' => 'Consumer Key',
	'twitter_api:consumer_secret' => 'Consumer Secret',

	'twitter_api:settings:instructions' => 'Bisogna ottenere una "consumer key" e un "consumer secret " da <a rel="nofollow" href="https://dev.twitter.com/apps/new" target="_blank">Twitter</a>. Completare la richiesta per la nuova app. Selezionare "Browser" come "application type" e "Read & Write" come tipo di accesso. L\'URL di callback è  %stwitter_api/authorize',

	'twitter_api:usersettings:description' => "Collega il tuo %s profilo con Twitter.",
	'twitter_api:usersettings:request' => "Innanzitutto devi  <a href=\"%s\">autorizzare</a> %s l'accesso al tuo account Twitter.",
	'twitter_api:usersettings:cannot_revoke' => "Non puoi scollegare questo tuo profilo da Twitter, perché non hai fornito un indirizzo email o una password. <a href=\"%s\">Forniscili ora</a>.",
	'twitter_api:authorize:error' => 'Impossibile autorizzare Twitter.',
	'twitter_api:authorize:success' => 'L\'accesso a Twitter è stato autorizzato.',

	'twitter_api:usersettings:authorized' => "Hai autorizzato %s ad accedere al tuo profilo Twitter: @%s",
	'twitter_api:usersettings:revoke' => 'Clicca <a href="%s">qui</a> per revocare l\'accesso.',
	'twitter_api:usersettings:site_not_configured' => 'Un amministratore deve configurare Twitter prima di poterlo usare.',

	'twitter_api:revoke:success' => 'L\'accesso a Twitter è stato revocato.',

	'twitter_api:post_to_twitter' => "Inoltrare i telegrammi dell'utente su Twitter?",

	'twitter_api:login' => 'Permettere agli utenti di registrarsi con Twitter?',
	'twitter_api:new_users' => 'Permette ai nuovi utenti di registrarsi utilizzando il loro profilo Twitter anche se la registrazione degli utenti è disabilitata?',
	'twitter_api:login:success' => 'Avete avuto accesso al sito.',
	'twitter_api:login:error' => 'Impossibile accedere con Twitter.',
	'twitter_api:login:email' => "Devi inserire un indirizzo email valido per il tuo nuovo profilo %s .",

	'twitter_api:invalid_page' => 'Pagina non valida',

	'twitter_api:deprecated_callback_url' => 'L\'URL di callback per l\'API di Twitter è cambiato in %s.  Per cortesia chiedi al tuo amministratore di cambiarlo.',

	'twitter_api:interstitial:settings' => 'Configura le tue impostazioni',
	'twitter_api:interstitial:description' => 'Sei quasi pronto ad usare %s! Abbiamo bisogno di alcuni ulteriori dettagli prima di poter continuare. Sono opzionali, ma ti permetteranno di accedere se Twitter avesse dei problemi, o se decidessi di scollegare i tuoi profili.',

	'twitter_api:interstitial:username' => 'Questo è il tuo nome utente, che non potrà essere cambiato. Se imposti una password, per accedere puoi utilizzare il tuo nome utente o il tuo indirizzo email.',

	'twitter_api:interstitial:name' => 'Questo è il nome che gli utenti vedranno quando dovranno interagire con te.',

	'twitter_api:interstitial:email' => 'Tuo indirizzo email. Di norma gli utenti non possono visualizzarlo.',

	'twitter_api:interstitial:password' => 'Una password per accedere se Twitter non funziona, o se decidi di scollegare da Twitter la tua registrazione a questo sito.',
	'twitter_api:interstitial:password2' => 'Ripeti la password.',

	'twitter_api:interstitial:no_thanks' => 'No grazie',

	'twitter_api:interstitial:no_display_name' => 'Devi avere un nome utente.',
	'twitter_api:interstitial:invalid_email' => 'Devi inserire un indirizzo email valido o niente.',
	'twitter_api:interstitial:existing_email' => 'Questo indirizzo email è già utilizzato in questo sito.',
	'twitter_api:interstitial:password_mismatch' => 'Le tue password non coincidono.',
	'twitter_api:interstitial:cannot_save' => 'Impossibile salvare i dettagli del profilo.',
	'twitter_api:interstitial:saved' => 'Dettagli profilo salvati!',
);
