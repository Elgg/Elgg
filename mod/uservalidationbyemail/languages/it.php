<?php
return array(
	'admin:users:unvalidated' => 'Non convalidati',
	
	'email:validate:subject' => "%s per favore conferma il tuo indirizzo email per %s!",
	'email:validate:body' => "%s,

prima di poter iniziare a utilizzare %s occorre confermare il tuo indirizzo email.

Per favore conferma il tuo indirizzo email cliccando sul seguente collegamento:

%s

Se non riesci a cliccare sul collegamento, copialo e incollalo manualmente nel tuo browser.

%s
%s
",
	'email:confirm:success' => "Il tuo indirizzo email è stato confermato!",
	'email:confirm:fail' => "Il tuo indirizzo email non può essere verificato...",

	'uservalidationbyemail:emailsent' => "Email inviata a <em>%s</em>",
	'uservalidationbyemail:registerok' => "Per attivare il tuo account, per favore conferma il tuo indirizzo email facendo click sul collegamento che ti abbiamo appena inviato tramite email. (\"Dov'è l'email che mi avete mandato?\" Hai provato a guardare se è finita nello spam?)",
	'uservalidationbyemail:login:fail' => "Il tuo account non è stato convalidato quindi il tuo tentativo di entrare non ha avuto successo. Ti è stata inviata un'altra e-mail di convalida. (\"Dov'è l'email che mi avete mandato?\" Hai provato a guardare se è finita nello spam?)",

	'uservalidationbyemail:admin:no_unvalidated_users' => 'Nessun utente non convalidato',

	'uservalidationbyemail:admin:unvalidated' => 'Non convalidato',
	'uservalidationbyemail:admin:user_created' => 'Registrati %s',
	'uservalidationbyemail:admin:resend_validation' => 'Rispedisci convalidazione',
	'uservalidationbyemail:admin:validate' => 'Convalida',
	'uservalidationbyemail:confirm_validate_user' => 'Convalidare %s?',
	'uservalidationbyemail:confirm_resend_validation' => 'Rispedire l\'email di convalidazione a %s?',
	'uservalidationbyemail:confirm_delete' => 'Eliminare %s?',
	'uservalidationbyemail:confirm_validate_checked' => 'Convalidare gli utenti selezionati?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Reinviare la validazione agli utenti selezionati?',
	'uservalidationbyemail:confirm_delete_checked' => 'Eliminare gli utenti selezionati?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Utenti sconosciuti',
	'uservalidationbyemail:errors:could_not_validate_user' => 'Impossibile convalidare l\'utente.',
	'uservalidationbyemail:errors:could_not_validate_users' => 'Impossibile convalidare gli utenti selezionati.',
	'uservalidationbyemail:errors:could_not_delete_user' => 'Impossibile eliminare l\'utente',
	'uservalidationbyemail:errors:could_not_delete_users' => 'Impossibile eliminare tutti gli utenti selezionati.',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Impossibile rispedire la richiesta di convalidazione.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Impossibile rispedire tutte le richieste di convalidazione agli utenti selezionati.',

	'uservalidationbyemail:messages:validated_user' => 'Utente convalidato.',
	'uservalidationbyemail:messages:validated_users' => 'Tutti gli utenti selezionati convalidati.',
	'uservalidationbyemail:messages:deleted_user' => 'Utente eliminato.',
	'uservalidationbyemail:messages:deleted_users' => 'Tutti gli utenti selezionati sono stati eliminati.',
	'uservalidationbyemail:messages:resent_validation' => 'Richiesta di convalidazione rispedita.',
	'uservalidationbyemail:messages:resent_validations' => 'Richieste di convalidazione rispedite a tutti gli utenti selezionati.'

);
