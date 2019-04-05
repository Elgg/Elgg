<?php
return array(
	'email:validate:subject' => "%s per favore conferma il tuo indirizzo email per %s!",
	'email:validate:body' => "Hi %s,

Before you can start you using %s, you must confirm your email address.

Please confirm your email address by clicking on the link below:

%s

If you can't click on the link, copy and paste it to your browser manually.

%s
%s",
	'email:confirm:success' => "Il tuo indirizzo email è stato confermato!",
	'email:confirm:fail' => "Il tuo indirizzo email non può essere verificato...",

	'uservalidationbyemail:emailsent' => "Email inviata a <em>%s</em>",
	'uservalidationbyemail:registerok' => "Per attivare il tuo account, per favore conferma il tuo indirizzo email facendo click sul collegamento che ti abbiamo appena inviato tramite email. (\"Dov'è l'email che mi avete mandato?\" Hai provato a guardare se è finita nello spam?)",
	'uservalidationbyemail:login:fail' => "Il tuo account non è stato convalidato quindi il tuo tentativo di entrare non ha avuto successo. Ti è stata inviata un'altra e-mail di convalida. (\"Dov'è l'email che mi avete mandato?\" Hai provato a guardare se è finita nello spam?)",

	'uservalidationbyemail:admin:resend_validation' => 'Rispedisci convalidazione',
	'uservalidationbyemail:confirm_resend_validation' => 'Rispedire l\'email di convalidazione a %s?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Reinviare la validazione agli utenti selezionati?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Utenti sconosciuti',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Impossibile rispedire la richiesta di convalidazione.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Impossibile rispedire tutte le richieste di convalidazione agli utenti selezionati.',

	'uservalidationbyemail:messages:resent_validation' => 'Richiesta di convalidazione rispedita.',
	'uservalidationbyemail:messages:resent_validations' => 'Richieste di convalidazione rispedite a tutti gli utenti selezionati.'
);
